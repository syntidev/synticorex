<?php
// ============================================================
// AGREGAR EN: routes/api.php
// ============================================================

// SYNTI — Asistente de ayuda
Route::prefix('synti')->middleware(['auth:sanctum', 'throttle:30,1'])->group(function () {
    Route::post('/ask',      [SyntiHelpController::class, 'ask']);
    Route::post('/feedback', [SyntiHelpController::class, 'feedback']);
});


<?php
// ============================================================
// ARCHIVO: app/Models/AiChatLog.php
// ============================================================

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiChatLog extends Model
{
    protected $fillable = [
        'tenant_id',
        'product',
        'question',
        'answer',
        'helpful',
    ];

    protected $casts = [
        'helpful' => 'integer',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}


<?php
// ============================================================
// AGREGAR EN: app/Console/Kernel.php (dentro de $commands)
// ============================================================

protected $commands = [
    \App\Console\Commands\IndexDocs::class,
    // ... tus otros commands
];


{{--
============================================================
AGREGAR EN: resources/views/dashboard/index.blade.php
Justo antes del cierre </body>
============================================================
--}}
@include('dashboard.partials.synti-assistant')


{{--
============================================================
INSTRUCCIONES COMPLETAS DE INSTALACIÓN
============================================================

PASO 1 — Migración
Copiar archivo de migración a:
database/migrations/2026_03_08_000001_create_ai_docs_table.php

Luego:
php artisan migrate

---

PASO 2 — Modelos y Comando
Copiar a sus rutas:
app/Models/AiDoc.php                          ← del archivo AiDoc_y_SyntiHelpController.php
app/Models/AiChatLog.php                      ← de este archivo
app/Http/Controllers/SyntiHelpController.php  ← del archivo AiDoc_y_SyntiHelpController.php
app/Console/Commands/IndexDocs.php            ← archivo IndexDocs.php

---

PASO 3 — Registrar comando en Kernel
Agregar en app/Console/Kernel.php → array $commands:
\App\Console\Commands\IndexDocs::class,

---

PASO 4 — Rutas API
Agregar en routes/api.php las rutas de este archivo.
Agregar el use al inicio:
use App\Http\Controllers\SyntiHelpController;

---

PASO 5 — Indexar docs
php artisan ai:index-docs

Verifica:
php artisan tinker
>>> App\Models\AiDoc::count()
// Debe mostrar ~31

---

PASO 6 — Widget en dashboard
Crear archivo:
resources/views/dashboard/partials/synti-assistant.blade.php

Agregar al final de dashboard/index.blade.php:
@include('dashboard.partials.synti-assistant')

---

PASO 7 — Probar
Abre tu dashboard → presiona Alt+H → escribe una pregunta.

---

AUTOMATIZACIÓN: cada vez que actualices docs/
php artisan ai:index-docs
(o con GitHub Action como definimos en el plan maestro)
--}}
