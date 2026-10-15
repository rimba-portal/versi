<?php

declare(strict_types=1);

namespace Rimba\Versioning\Http\UI\Admin\Resources\Versions\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Rimba\Versioning\Actions\CreateVersion;
use Rimba\Versioning\Enums\ContentType;
use Rimba\Versioning\Enums\VersionIncrementType;
use Rimba\Versioning\Enums\VersionStatus;

class VersionsRelationManager extends RelationManager
{
    protected static string $relationship = 'versions';

    protected static ?string $recordTitleAttribute = 'version';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('status')
                ->options(VersionStatus::class)
                ->default(VersionStatus::Draft->value)
                ->required(),

            Select::make('content_type')
                ->options(ContentType::class)
                ->default(ContentType::Url->value)
                ->required(),

            TextInput::make('content_url')
                ->label('Content URL / Route Name')
                ->required(),

            DateTimePicker::make('effective_from'),
            DateTimePicker::make('effective_until'),

            Textarea::make('notes')
                ->maxLength(65535)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('version')
                    ->badge()
                    ->sortable(),

                TextColumn::make('revision')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('content_type')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('content_url')
                    ->label('Content')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('effective_from')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('released_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(VersionStatus::class),

                SelectFilter::make('content_type')
                    ->options(ContentType::class),
            ])
            ->headerActions([
                Action::make('createVersion')
                    ->label('New Version')
                    ->icon('heroicon-o-plus')
                    ->schema([
                        Select::make('increment')
                            ->options(VersionIncrementType::class)
                            ->default(VersionIncrementType::Patch->value)
                            ->required(),

                        Select::make('status')
                            ->options(VersionStatus::class)
                            ->default(VersionStatus::Draft->value)
                            ->required(),

                        Select::make('content_type')
                            ->options(ContentType::class)
                            ->default(ContentType::Url->value)
                            ->required(),

                        TextInput::make('content_url')
                            ->label('Content URL / Route Name')
                            ->required(),

                        DateTimePicker::make('effective_from'),
                        DateTimePicker::make('effective_until'),

                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->action(function (array $data): void {
                        $versionIncrementType = VersionIncrementType::from($data['increment']);
                        unset($data['increment']);

                        app(CreateVersion::class)->execute(
                            model: $this->getOwnerRecord(),
                            increment: $versionIncrementType,
                            attributes: $data,
                        );

                        Notification::make()
                            ->success()
                            ->title('Version created')
                            ->send();
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
