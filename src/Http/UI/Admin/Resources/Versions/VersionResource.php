<?php

namespace Rimba\Versioning\Http\UI\Admin\Resources\Versions;

use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VersionResource extends Resource
{
    protected static ?string $model = \Rimba\Versioning\Models\Version::class;

    protected static string|UnitEnum|null $navigationGroup = 'Versioning';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play';

    protected static ?int $navigationSort = 24;

    protected static ?string $recordTitleAttribute = 'version';

    public static function form(Schema $schema): Schema { return \Rimba\Versioning\Http\UI\Admin\Resources\Versions\Schemas\VersionForm::configure($schema); }

    public static function infolist(Schema $schema): Schema { return \Rimba\Versioning\Http\UI\Admin\Resources\Versions\Schemas\VersionInfolist::configure($schema); }

    public static function table(Table $table): Table { return \Rimba\Versioning\Http\UI\Admin\Resources\Versions\Tables\VersionsTable::configure($table); }

    public static function getRelations(): array 
    { 
        return [ 
            // 
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Rimba\Versioning\Http\UI\Admin\Resources\Versions\Pages\ListVersions::route('/'),
             'create' => \Rimba\Versioning\Http\UI\Admin\Resources\Versions\Pages\CreateVersion::route('/create'),
             'view' => \Rimba\Versioning\Http\UI\Admin\Resources\Versions\Pages\ViewVersion::route('/{record}'),
             'edit' => \Rimba\Versioning\Http\UI\Admin\Resources\Versions\Pages\EditVersion::route('/{record}/edit'),
            //
        ];
    }
}
