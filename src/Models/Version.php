<?php

declare(strict_types=1);

namespace Rimba\Versioning\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Rimba\Versioning\Builders\VersionBuilder;
use Rimba\Versioning\Enums\ContentType;
use Rimba\Versioning\Enums\VersionIncrementType;
use Rimba\Versioning\Enums\VersionStatus;
use Rimba\Versioning\Services\SemanticVersionService;

#[Fillable([
    'versionable_type',
    'versionable_id',
    'version',
    'revision',
    'major',
    'minor',
    'patch',
    'status',
    'content_type',
    'content_url',
    'checksum',
    'effective_from',
    'effective_until',
    'released_at',
    'upload_by',
    'notes',

    // virtual attribute only
    'revision_type',
])]
class Version extends Model
{
    protected ?VersionIncrementType $revisionType = null;

    public function newEloquentBuilder($query): VersionBuilder
    {
        return new VersionBuilder($query);
    }

    public function versionable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function revisionType(): Attribute
    {
        return Attribute::make(get: function (): ?VersionIncrementType {
            return $this->revisionType;
        }, set: function (string|VersionIncrementType|null $value) {
            if ($value instanceof VersionIncrementType) {
                $this->revisionType = $value;

                return;
            }

            $this->revisionType = $value
                ? VersionIncrementType::from(strtolower($value))
                : null;

            return [];
        });
    }

    protected function casts(): array
    {
        return [
            'effective_from' => 'datetime',
            'effective_until' => 'datetime',
            'released_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Version $version): void {
            static::fillDefaultContentFields($version);
            static::fillDefaultVersionFields($version);
            static::fillUploadBy($version);
        });
    }

    protected static function fillDefaultContentFields(Version $version): void
    {
        $version->content_type ??= ContentType::Url->value;
        $version->status ??= VersionStatus::Released->value;
        $version->effective_from ??= now();

        if ($version->status === VersionStatus::Released->value) {
            $version->released_at ??= now();
        }
    }

    protected static function fillDefaultVersionFields(Version $version): void
    {
        if (! empty($version->version)) {
            static::syncPartsFromVersion($version);
            $version->revision ??= static::nextRevision($version);

            return;
        }

        $latest = static::latestForSameVersionable($version);

        if (! $latest instanceof Version) {
            $version->major = 1;
            $version->minor = 0;
            $version->patch = 0;
            $version->revision = $version->revision ?: 1;
            $version->version = app(SemanticVersionService::class)->format(
                $version->major,
                $version->minor,
                $version->patch,
            );

            return;
        }

        [$major, $minor, $patch] = static::nextSemanticVersion(
            latest: $latest,
            increment: $version->revision_type ?? VersionIncrementType::Patch,
        );

        $version->major = $major;
        $version->minor = $minor;
        $version->patch = $patch;
        $version->revision = $version->revision ?: ((int) $latest->revision + 1);
        $version->version = app(SemanticVersionService::class)->format(
            $version->major,
            $version->minor,
            $version->patch,
        );
    }

    protected static function syncPartsFromVersion(Version $version): void
    {
        [$major, $minor, $patch] = app(SemanticVersionService::class)
            ->parse($version->version);

        $version->major ??= $major;
        $version->minor ??= $minor;
        $version->patch ??= $patch;
    }

    protected static function nextSemanticVersion(
        Version $latest,
        VersionIncrementType $increment,
    ): array {
        $semanticVersionService = app(SemanticVersionService::class);

        return match ($increment) {
            VersionIncrementType::Major => $semanticVersionService->incrementMajor(
                (int) $latest->major,
            ),

            VersionIncrementType::Minor => $semanticVersionService->incrementMinor(
                (int) $latest->major,
                (int) $latest->minor,
            ),

            VersionIncrementType::Patch => $semanticVersionService->incrementPatch(
                (int) $latest->major,
                (int) $latest->minor,
                (int) $latest->patch,
            ),
        };
    }

    protected static function latestForSameVersionable(Version $version): ?Version
    {
        if (! $version->versionable_type || ! $version->versionable_id) {
            return null;
        }

        return static::query()
            ->where('versionable_type', $version->versionable_type)
            ->where('versionable_id', $version->versionable_id)
            ->latestVersion()
            ->orderByDesc('revision')
            ->first();
    }

    protected static function nextRevision(Version $version): int
    {
        $latest = static::latestForSameVersionable($version);

        if (! $latest instanceof Version) {
            return 1;
        }

        return (int) $latest->revision + 1;
    }

    protected static function fillUploadBy(Version $version): void
    {
        if (! empty($version->upload_by)) {
            return;
        }

        if ($user = Auth::user()) {
            $version->upload_by = method_exists($user, 'getUploadIdentifier')
                ? $user->getUploadIdentifier()
                : (string) $user->getKey();

            return;
        }

        $version->upload_by = 'System';
    }
}
