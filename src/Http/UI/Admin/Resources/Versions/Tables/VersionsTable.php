<?php

declare(strict_types=1);

namespace Rimba\Versioning\Http\UI\Admin\Resources\Versions\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Rimba\Versioning\Enums\ContentType;
use Rimba\Versioning\Enums\VersionStatus;

final class VersionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('versionable_type')
                    ->label('Owner')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->searchable(),

                TextColumn::make('versionable_id')
                    ->label('Owner ID')
                    ->sortable(),

                TextColumn::make('version')
                    ->badge()
                    ->sortable()
                    ->searchable(),

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

                TextColumn::make('released_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(VersionStatus::class),

                SelectFilter::make('content_type')
                    ->options(ContentType::class),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
