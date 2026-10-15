<?php

declare(strict_types=1);

namespace Rimba\Versioning\Http\UI\Admin\Resources\Versions\Pages;

use Filament\Resources\Pages\ListRecords;
use Rimba\Versioning\Http\UI\Admin\Resources\Versions\VersionResource;

class ListVersions extends ListRecords
{
    protected static string $resource = VersionResource::class;

    protected static ?string $title = 'Versions';

    protected ?string $subheading =
        'Audit version history, release state, content references, and effective dates.';
}
