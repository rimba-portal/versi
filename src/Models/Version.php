<?php

declare(strict_types=1);

namespace Rimba\Versioning\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Route;
use Rimba\Versioning\Builders\VersionBuilder;
use Rimba\Versioning\Enums\ContentType;
use Rimba\Versioning\Enums\VersionStatus;

#[Fillable([
    'versionable_type',
    'versionable_id',
    'version',
    'revision',
    'major',
    'minor',
    'patch',
    'status',
    'original_name',
    'mime_type',
    'content_size',
    'content_type',
    'content_url',
    'checksum',
    'effective_from',
    'effective_until',
    'released_at',
    'upload_by',
    'notes',
])]
class Version extends Model
{
    public function newEloquentBuilder($query): VersionBuilder
    {
        return new VersionBuilder($query);
    }

    public function versionable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'revision' => 'integer',
            'major' => 'integer',
            'minor' => 'integer',
            'patch' => 'integer',
            'content_size' => 'integer',
            'status' => VersionStatus::class,
            'content_type' => ContentType::class,
            'effective_from' => 'datetime',
            'effective_until' => 'datetime',
            'released_at' => 'datetime',
        ];
    }

    public function url(): ?string
    {
        if (blank($this->content_url)) {
            return null;
        }

        return match ($this->content_type) {
            ContentType::Route,
            ContentType::FilamentPage,
            ContentType::FilamentResource,
            ContentType::Dashboard,
            ContentType::Report => Route::has($this->content_url)
                ? route($this->content_url)
                : null,
            default => $this->content_url,
        };
    }

    public function openInNewTab(): bool
    {
        return match ($this->content_type) {
            ContentType::Url,
            ContentType::Markdown,
            ContentType::Document,
            ContentType::Folder,
            ContentType::Api,
            ContentType::File,
            ContentType::Video,
            ContentType::Html => true,
            default => false,
        };
    }
}
