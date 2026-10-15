<?php

declare(strict_types=1);

namespace Rimba\Versioning\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Rimba\Versioning\Enums\ContentType;
use Rimba\Versioning\Enums\VersionIncrementType;
use Rimba\Versioning\Enums\VersionStatus;
use Rimba\Versioning\Models\Version;
use Rimba\Versioning\Services\SemanticVersionService;

final class CreateVersion
{
    public function __construct(
        private readonly SemanticVersionService $semanticVersionService,
    ) {}

    public function execute(
        Model $model,
        VersionIncrementType $increment = VersionIncrementType::Patch,
        array $attributes = [],
    ): Version {
        if (! method_exists($model, 'versions')) {
            throw new \InvalidArgumentException(
                sprintf('%s does not provide a versions() relationship.', $model::class),
            );
        }

        $latest = $model->versions()
            ->latestVersion()
            ->orderByDesc('revision')
            ->first();

        if ($latest instanceof Version) {
            [$major, $minor, $patch] = $this->semanticVersionService->next(
                major: (int) $latest->major,
                minor: (int) $latest->minor,
                patch: (int) $latest->patch,
                increment: $increment,
            );

            $revision = (int) $latest->revision + 1;
        } else {
            [$major, $minor, $patch] = [1, 0, 0];
            $revision = 1;
        }

        $status = $attributes['status'] ?? VersionStatus::Draft;
        $status = $status instanceof VersionStatus
            ? $status
            : VersionStatus::from((string) $status);

        $version = $model->versions()->make([
            ...$attributes,
            'version' => $this->semanticVersionService->format($major, $minor, $patch),
            'revision' => $revision,
            'major' => $major,
            'minor' => $minor,
            'patch' => $patch,
            'status' => $status,
            'content_type' => $attributes['content_type'] ?? ContentType::Url,
            'upload_by' => $attributes['upload_by'] ?? $this->uploadIdentifier(),
        ]);

        if ($status === VersionStatus::Released) {
            $version->released_at ??= now();
            $version->effective_from ??= now();
        }

        $version->save();

        return $version;
    }

    private function uploadIdentifier(): string
    {
        $user = Auth::user();

        if (! $user) {
            return 'system';
        }

        return method_exists($user, 'getUploadIdentifier')
            ? (string) $user->getUploadIdentifier()
            : (string) $user->getAuthIdentifier();
    }
}
