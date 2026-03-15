<?php

declare(strict_types=1);

namespace App\Filament\Resources\LandingSectionResource\Pages;

use App\Filament\Resources\LandingSectionResource;
use Filament\Resources\Pages\ListRecords;

class ListLandingSections extends ListRecords
{
    protected static string $resource = LandingSectionResource::class;
}
