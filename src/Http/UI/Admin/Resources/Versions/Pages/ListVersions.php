<?php

declare(strict_types=1);

namespace Rimba\Versioning\Http\UI\Admin\Resources\Versions\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Versioning\Http\UI\Admin\Resources\Versions\VersionResource;

class ListVersions extends ListRecords
{
    protected static string $resource = VersionResource::class;

    protected static ?string $title = 'Semantic Version Files';

    protected ?string $subheading = 'Audit underlying storage details, sizes, and deployment paths.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
