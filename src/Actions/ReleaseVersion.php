<?php

declare(strict_types=1);

namespace Rimba\Versioning\Actions;

use Rimba\Versioning\Enums\VersionStatus;
use Rimba\Versioning\Models\Version;

final class ReleaseVersion
{
    public function execute(Version $version): Version
    {
        $version->forceFill([
            'status' => VersionStatus::Released,
            'released_at' => $version->released_at ?? now(),
            'effective_from' => $version->effective_from ?? now(),
        ])->save();

        return $version->refresh();
    }
}
