<?php

declare(strict_types=1);

namespace Rimba\Versioning\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Rimba\Versioning\Actions\CreateVersion;
use Rimba\Versioning\Enums\VersionIncrementType;
use Rimba\Versioning\Models\Version;

trait HasVersions
{
    public function versions(): MorphMany
    {
        return $this->morphMany(
            Version::class,
            'versionable',
        );
    }

    public function createVersion(
        array $attributes = [],
        VersionIncrementType $increment = VersionIncrementType::Patch,
    ): Version {
        return app(CreateVersion::class)->execute(
            model: $this,
            increment: $increment,
            attributes: $attributes,
        );
    }

    public function currentVersion(): ?Version
    {
        return $this->versions()
            ->current()
            ->latest('released_at')
            ->first();
    }

    public function latestVersion(): ?Version
    {
        return $this->versions()
            ->latestVersion()
            ->orderByDesc('revision')
            ->first();
    }
}
