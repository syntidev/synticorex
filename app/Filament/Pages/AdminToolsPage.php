<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class AdminToolsPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Herramientas';

    protected static UnitEnum|string|null $navigationGroup = 'Sistema';

    protected static ?string $title = 'Herramientas Administrativas';

    protected string $view = 'filament.pages.admin-tools';
}
