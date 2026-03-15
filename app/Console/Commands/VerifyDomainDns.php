<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Domain;
use Illuminate\Console\Command;

class VerifyDomainDns extends Command
{
    protected $signature = 'domains:verify-dns';

    protected $description = 'Verifica resolución DNS de todos los dominios addon y external';

    public function handle(): void
    {
        $domains = Domain::whereIn('type', ['addon', 'external'])
            ->where('status', '!=', 'cancelled')
            ->get();

        foreach ($domains as $domain) {
            $resolved = gethostbyname($domain->domain);
            $expected = $domain->dns_expected_ip ?? config('app.server_ip');
            $status   = ($resolved === $expected) ? 'ok' : 'failing';
            $previous = $domain->dns_status;

            $domain->update(['dns_status' => $status, 'dns_verified_at' => now()]);

            if ($previous !== $status) {
                $domain->logEvent(
                    $status === 'ok' ? 'dns_verified' : 'dns_failed',
                    ['resolved_ip' => $resolved, 'expected_ip' => $expected]
                );
            }
        }

        $this->info("DNS verificado: {$domains->count()} dominios procesados.");
    }
}
