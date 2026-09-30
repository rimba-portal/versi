<?php

namespace Rimba\Versioning\Http\UI\Admin\Resources\Versions\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVersions extends ListRecords
{
    protected static string $resource = \Rimba\Versioning\Http\UI\Admin\Resources\Versions\VersionResource::class;

    protected static ?string $title = 'Semantic Version Files';

    protected ?string $subheading = 'Audit underlying storage details, sizes, and deployment paths.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
