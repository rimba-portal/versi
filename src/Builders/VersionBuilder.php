<?php

declare(strict_types=1);

namespace Rimba\Versioning\Builders;

use Illuminate\Database\Eloquent\Builder;
use Rimba\Versioning\Enums\VersionStatus;

class VersionBuilder extends Builder
{
    public function latestVersion(): static
    {
        return $this
            ->orderByDesc('major')
            ->orderByDesc('minor')
            ->orderByDesc('patch');
    }

    public function major(int $major): static
    {
        return $this->where('major', $major);
    }

    public function minor(int $major, int $minor): static
    {
        return $this
            ->where('major', $major)
            ->where('minor', $minor);
    }

    public function patch(int $major, int $minor, int $patch): static
    {
        return $this
            ->where('major', $major)
            ->where('minor', $minor)
            ->where('patch', $patch);
    }

    public function status(VersionStatus $status): static
    {
        return $this->where('status', $status->value);
    }

    public function draft(): static
    {
        return $this->status(VersionStatus::Draft);
    }

    public function review(): static
    {
        return $this->status(VersionStatus::Review);
    }

    public function released(): static
    {
        return $this->status(VersionStatus::Released);
    }

    public function deprecated(): static
    {
        return $this->status(VersionStatus::Deprecated);
    }

    public function archived(): static
    {
        return $this->status(VersionStatus::Archived);
    }

    public function effective(): static
    {
        return $this
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('effective_from')
                    ->orWhere('effective_from', '<=', now());
            })
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('effective_until')
                    ->orWhere('effective_until', '>', now());
            });
    }

    public function current(): static
    {
        return $this
            ->released()
            ->effective();
    }
}
