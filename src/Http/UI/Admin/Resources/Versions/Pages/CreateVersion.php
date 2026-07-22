<?php

declare(strict_types=1);

namespace Rimba\Versioning\Http\UI\Admin\Resources\Versions\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Versioning\Http\UI\Admin\Resources\Versions\VersionResource;

class CreateVersion extends CreateRecord
{
    protected static string $resource = VersionResource::class;
}
