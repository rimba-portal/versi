<?php

declare(strict_types=1);

namespace Rimba\Versioning\Http\UI\Admin\Resources\Versions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class VersionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('versionable_type')
                ->label('Owner Type'),

            TextEntry::make('versionable_id')
                ->label('Owner ID'),

            TextEntry::make('version')
                ->badge(),

            TextEntry::make('revision')
                ->numeric(),

            TextEntry::make('status')
                ->badge(),

            TextEntry::make('content_type')
                ->badge(),

            TextEntry::make('content_url')
                ->columnSpanFull(),

            TextEntry::make('checksum')
                ->placeholder('—'),

            TextEntry::make('effective_from')
                ->dateTime()
                ->placeholder('—'),

            TextEntry::make('effective_until')
                ->dateTime()
                ->placeholder('—'),

            TextEntry::make('released_at')
                ->dateTime()
                ->placeholder('—'),

            TextEntry::make('upload_by')
                ->placeholder('—'),

            TextEntry::make('notes')
                ->placeholder('—')
                ->columnSpanFull(),

            TextEntry::make('created_at')
                ->dateTime(),

            TextEntry::make('updated_at')
                ->dateTime(),
        ]);
    }
}
