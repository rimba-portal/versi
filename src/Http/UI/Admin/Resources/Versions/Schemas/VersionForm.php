<?php

declare(strict_types=1);

namespace Rimba\Versioning\Http\UI\Admin\Resources\Versions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Rimba\Versioning\Enums\ContentType;
use Rimba\Versioning\Enums\VersionStatus;

final class VersionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('version')
                ->disabled()
                ->dehydrated(false),

            TextInput::make('revision')
                ->numeric()
                ->disabled()
                ->dehydrated(false),

            Select::make('status')
                ->options(VersionStatus::class)
                ->required(),

            Select::make('content_type')
                ->options(ContentType::class)
                ->required(),

            Textarea::make('content_url')
                ->label('Content URL / Route Name')
                ->required()
                ->columnSpanFull(),

            DateTimePicker::make('effective_from'),
            DateTimePicker::make('effective_until'),
            DateTimePicker::make('released_at'),

            TextInput::make('checksum'),

            Textarea::make('notes')
                ->columnSpanFull(),
        ]);
    }
}
