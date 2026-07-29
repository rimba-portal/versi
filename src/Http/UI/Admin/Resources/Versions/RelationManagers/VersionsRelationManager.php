<?php

declare(strict_types=1);

namespace Rimba\Versioning\Http\UI\Admin\Resources\Versions\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Rimba\Versioning\Enums\ContentType;
use Rimba\Versioning\Enums\VersionIncrementType;
use Rimba\Versioning\Enums\VersionStatus;

class VersionsRelationManager extends RelationManager
{
    protected static string $relationship = 'versions';

    protected static ?string $recordTitleAttribute = 'version';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('revision_type')
                    ->label('Revision Type')
                    ->options([
                        VersionIncrementType::Major->value => 'Major',
                        VersionIncrementType::Minor->value => 'Minor',
                        VersionIncrementType::Patch->value => 'Patch',
                    ])
                    ->default(VersionIncrementType::Patch->value)
                    ->dehydrated(false)
                    ->helperText('Used only to calculate the next version number.'),

                Forms\Components\Select::make('content_type')
                    ->options(ContentType::options())
                    ->default(ContentType::Url->value)
                    ->required(),

                Forms\Components\TextInput::make('content_url')
                    ->label('Content URL / Route Name')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options(VersionStatus::class)
                    ->default(VersionStatus::Released->value)
                    ->required(),

                Forms\Components\DateTimePicker::make('effective_from'),

                Forms\Components\DateTimePicker::make('effective_until'),

                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('version')
                    ->sortable()
                    ->searchable()
                    ->badge(),

                Tables\Columns\TextColumn::make('revision')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('content_type')
                    ->badge()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('content_url')
                    ->label('Content')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('effective_from')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('released_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(VersionStatus::class),

                Tables\Filters\SelectFilter::make('content_type')
                    ->options(ContentType::options()),
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }
}
