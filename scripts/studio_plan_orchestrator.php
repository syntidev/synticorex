<?php

declare(strict_types=1);

/**
 * Product MVP execution orchestrator.
 *
 * Commands:
 *   php scripts/studio_plan_orchestrator.php next --plan=studio
 *   php scripts/studio_plan_orchestrator.php status --plan=cat
 *   php scripts/studio_plan_orchestrator.php list --plan=food
 *   php scripts/studio_plan_orchestrator.php done S1.1 --plan=studio
 *   php scripts/studio_plan_orchestrator.php reset S1.1 --plan=studio
 */

$root = dirname(__DIR__);
$command = $argv[1] ?? 'next';
$taskId = $argv[2] ?? null;
$planKey = parsePlanKey($argv);
$planPath = resolvePlanPath($root, $planKey);

if (!is_file($planPath)) {
    fwrite(STDERR, "Plan file not found: {$planPath}" . PHP_EOL);
    exit(1);
}

$content = file_get_contents($planPath);
if ($content === false) {
    fwrite(STDERR, "Unable to read plan file." . PHP_EOL);
    exit(1);
}

$plan = json_decode($content, true);
if (!is_array($plan) || !isset($plan['tasks']) || !is_array($plan['tasks'])) {
    fwrite(STDERR, "Invalid plan JSON structure." . PHP_EOL);
    exit(1);
}

$productLabel = strtoupper((string) ($plan['meta']['product'] ?? $planKey));

switch ($command) {
    case 'next':
        showNextTask($plan['tasks'], $productLabel);
        exit(0);

    case 'status':
        showStatus($plan['tasks'], $productLabel);
        exit(0);

    case 'list':
        listTasks($plan['tasks']);
        exit(0);

    case 'done':
        if ($taskId === null || $taskId === '') {
            fwrite(STDERR, "Usage: php scripts/studio_plan_orchestrator.php done <TASK_ID>" . PHP_EOL);
            exit(1);
        }

        $updated = markTask($plan['tasks'], $taskId, 'done');
        writePlan($planPath, $plan, $updated);
        echo "Task {$taskId} marked as done." . PHP_EOL;
        showNextTask($updated, $productLabel);
        exit(0);

    case 'reset':
        if ($taskId === null || $taskId === '') {
            fwrite(STDERR, "Usage: php scripts/studio_plan_orchestrator.php reset <TASK_ID>" . PHP_EOL);
            exit(1);
        }

        $updated = markTask($plan['tasks'], $taskId, 'pending', false);
        writePlan($planPath, $plan, $updated);
        echo "Task {$taskId} reset to pending." . PHP_EOL;
        showNextTask($updated, $productLabel);
        exit(0);

    default:
        fwrite(STDERR, "Unknown command: {$command}" . PHP_EOL);
        fwrite(STDERR, "Available commands: next, status, list, done, reset" . PHP_EOL);
        exit(1);
}

function parsePlanKey(array $argv): string
{
    $validPlans = ['studio', 'cat', 'food'];

    foreach ($argv as $arg) {
        if (!is_string($arg)) {
            continue;
        }

        if (str_starts_with($arg, '--plan=')) {
            $value = strtolower(trim(substr($arg, 7)));
            if (in_array($value, $validPlans, true)) {
                return $value;
            }

            fwrite(STDERR, "Invalid plan key: {$value}. Allowed: studio, cat, food" . PHP_EOL);
            exit(1);
        }
    }

    return 'studio';
}

function resolvePlanPath(string $root, string $planKey): string
{
    $planMap = [
        'studio' => 'STUDIO_EXECUTION_PLAN.json',
        'cat' => 'CAT_EXECUTION_PLAN.json',
        'food' => 'FOOD_EXECUTION_PLAN.json',
    ];

    return $root
        . DIRECTORY_SEPARATOR . '.doc'
        . DIRECTORY_SEPARATOR . '.La_Verdad_Del_Proyecto'
        . DIRECTORY_SEPARATOR . $planMap[$planKey];
}

/**
 * @param array<int, array<string, mixed>> $tasks
 */
function showNextTask(array $tasks, string $productLabel): void
{
    $next = findNextPending($tasks);

    if ($next === null) {
        echo "All tasks are done. {$productLabel} MVP execution queue is complete." . PHP_EOL;
        return;
    }

    echo "NEXT TASK ({$productLabel} HIGH PRIORITY ORDER)" . PHP_EOL;
    echo "- ID: " . (string) $next['id'] . PHP_EOL;
    echo "- Sprint: " . (string) $next['sprint'] . PHP_EOL;
    echo "- Priority: " . (string) $next['priority'] . PHP_EOL;
    echo "- Title: " . (string) $next['title'] . PHP_EOL;
    echo "- Description: " . (string) $next['description'] . PHP_EOL;

    $deps = $next['depends_on'] ?? [];
    if (is_array($deps) && count($deps) > 0) {
        echo "- Depends on: " . implode(', ', $deps) . PHP_EOL;
    }
}

/**
 * @param array<int, array<string, mixed>> $tasks
 */
function showStatus(array $tasks, string $productLabel): void
{
    $total = count($tasks);
    $done = 0;

    foreach ($tasks as $task) {
        if (($task['status'] ?? 'pending') === 'done') {
            $done++;
        }
    }

    $pending = $total - $done;
    $percent = $total === 0 ? 0 : (int) round(($done / $total) * 100);

    echo "{$productLabel} MVP EXECUTION STATUS" . PHP_EOL;
    echo "- Total tasks: {$total}" . PHP_EOL;
    echo "- Done: {$done}" . PHP_EOL;
    echo "- Pending: {$pending}" . PHP_EOL;
    echo "- Progress: {$percent}%" . PHP_EOL;

    $next = findNextPending($tasks);
    if ($next !== null) {
        echo "- Next: " . $next['id'] . ' ' . $next['title'] . PHP_EOL;
    }
}

/**
 * @param array<int, array<string, mixed>> $tasks
 */
function listTasks(array $tasks): void
{
    foreach ($tasks as $task) {
        $status = strtoupper((string) ($task['status'] ?? 'pending'));
        echo '[' . $status . '] '
            . (string) $task['id']
            . ' | '
            . (string) $task['sprint']
            . ' | '
            . (string) $task['priority']
            . ' | '
            . (string) $task['title']
            . PHP_EOL;
    }
}

/**
 * @param array<int, array<string, mixed>> $tasks
 */
function findNextPending(array $tasks): ?array
{
    foreach ($tasks as $task) {
        if (($task['status'] ?? 'pending') !== 'done') {
            return $task;
        }
    }

    return null;
}

/**
 * @param array<int, array<string, mixed>> $tasks
 * @return array<int, array<string, mixed>>
 */
function markTask(array $tasks, string $taskId, string $newStatus, bool $validateDependencies = true): array
{
    $index = null;
    foreach ($tasks as $i => $task) {
        if (($task['id'] ?? '') === $taskId) {
            $index = $i;
            break;
        }
    }

    if ($index === null) {
        fwrite(STDERR, "Task not found: {$taskId}" . PHP_EOL);
        exit(1);
    }

    if ($newStatus === 'done' && $validateDependencies) {
        $deps = $tasks[$index]['depends_on'] ?? [];
        if (is_array($deps) && count($deps) > 0) {
            foreach ($deps as $depId) {
                if (!isDependencyDone($tasks, (string) $depId)) {
                    fwrite(STDERR, "Blocked: dependency {$depId} is not done. Complete dependencies first." . PHP_EOL);
                    exit(1);
                }
            }
        }

        $next = findNextPending($tasks);
        if ($next !== null && ($next['id'] ?? '') !== $taskId) {
            fwrite(STDERR, "Blocked: next allowed task is {$next['id']}. Do not skip execution order." . PHP_EOL);
            exit(1);
        }

        $tasks[$index]['status'] = 'done';
        $tasks[$index]['completed_at'] = gmdate('c');

        return $tasks;
    }

    if ($newStatus === 'pending') {
        $tasks[$index]['status'] = 'pending';
        unset($tasks[$index]['completed_at']);

        return $tasks;
    }

    fwrite(STDERR, "Invalid status transition." . PHP_EOL);
    exit(1);
}

/**
 * @param array<int, array<string, mixed>> $tasks
 */
function isDependencyDone(array $tasks, string $dependencyId): bool
{
    foreach ($tasks as $task) {
        if (($task['id'] ?? '') === $dependencyId) {
            return ($task['status'] ?? 'pending') === 'done';
        }
    }

    return false;
}

/**
 * @param array<string, mixed> $plan
 * @param array<int, array<string, mixed>> $tasks
 */
function writePlan(string $planPath, array $plan, array $tasks): void
{
    $plan['tasks'] = $tasks;
    $plan['meta']['updated_at'] = gmdate('Y-m-d');

    $json = json_encode($plan, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        fwrite(STDERR, "Failed to encode updated plan JSON." . PHP_EOL);
        exit(1);
    }

    $ok = file_put_contents($planPath, $json . PHP_EOL);
    if ($ok === false) {
        fwrite(STDERR, "Failed to write updated plan file." . PHP_EOL);
        exit(1);
    }
}
