<?php

declare(strict_types=1);

namespace Rimba\Versioning\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Rimba\Versioning\Models\Version;

trait HasVersions
{
    public function versions(): MorphMany
    {
        return $this->morphMany(
            Version::class,
            'versionable'
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
            ->latest('id')
            ->first();
    }
}
