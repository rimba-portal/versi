<?php

declare(strict_types=1);

namespace Rimba\Versioning\Http\UI\Admin\Resources\Versions;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Versioning\Http\UI\Admin\Resources\Versions\Pages\CreateVersion;
use Rimba\Versioning\Http\UI\Admin\Resources\Versions\Pages\EditVersion;
use Rimba\Versioning\Http\UI\Admin\Resources\Versions\Pages\ListVersions;
use Rimba\Versioning\Http\UI\Admin\Resources\Versions\Pages\ViewVersion;
use Rimba\Versioning\Http\UI\Admin\Resources\Versions\Schemas\VersionForm;
use Rimba\Versioning\Http\UI\Admin\Resources\Versions\Schemas\VersionInfolist;
use Rimba\Versioning\Http\UI\Admin\Resources\Versions\Tables\VersionsTable;
use Rimba\Versioning\Models\Version;
use UnitEnum;

class VersionResource extends Resource
{
    protected static ?string $model = Version::class;

    protected static string|UnitEnum|null $navigationGroup = 'Versioning';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowSmallRight;

    public static function form(Schema $schema): Schema
    {
        return VersionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VersionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VersionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVersions::route('/'),
            'create' => CreateVersion::route('/create'),
            'view' => ViewVersion::route('/{record}'),
            'edit' => EditVersion::route('/{record}/edit'),
        ];
    }
}
