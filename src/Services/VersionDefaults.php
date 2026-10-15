<?php

declare(strict_types=1);

namespace Rimba\Versioning\Services;

use Rimba\Versioning\Enums\ContentType;
use Rimba\Versioning\Enums\VersionStatus;
use Rimba\Versioning\Models\Version;

final class VersionDefaults
{
    public function apply(Version $version): void
    {
        if (filled($version->version)) {
            return;
        }

        $latest = Version::query()
            ->where('versionable_type', $version->versionable_type)
            ->where('versionable_id', $version->versionable_id)
            ->latestVersion()
            ->orderByDesc('revision')
            ->first();

        if ($latest) {
            $major = $latest->major;
            $minor = $latest->minor;
            $patch = $latest->patch + 1;
            $revision = $latest->revision + 1;
        } else {
            $major = 1;
            $minor = 0;
            $patch = 0;
            $revision = 1;
        }

        $version->forceFill([
            'version' => "{$major}.{$minor}.{$patch}",
            'revision' => $version->revision ?? $revision,
            'major' => $version->major ?? $major,
            'minor' => $version->minor ?? $minor,
            'patch' => $version->patch ?? $patch,
            'status' => $version->status ?? VersionStatus::Draft,
            'content_type' => $version->content_type ?? ContentType::Url,
            'upload_by' => $version->upload_by ?? 'system',
        ]);
    }
}
