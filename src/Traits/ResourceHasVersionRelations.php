<?php

declare(strict_types=1);

namespace Rimba\Versioning\Traits;

use Rimba\Versioning\Http\UI\Admin\Resources\Versions\RelationManagers\VersionsRelationManager;

trait ResourceHasVersionRelations
{
    public static function getVersionRelations(): array
    {
        return [
            VersionsRelationManager::class,
        ];
    }
}
