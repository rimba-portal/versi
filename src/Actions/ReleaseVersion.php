<?php

declare(strict_types=1);

namespace Rimba\Versioning\Actions;

use Rimba\Versioning\Enums\VersionStatus;
use Rimba\Versioning\Models\Version;

class ReleaseVersion
{
    public function execute(
        Version $version
    ): Version {

        $version->update([
            'status' => VersionStatus::Released,
            'released_at' => now(),
        ]);

        return $version;
    }
}
