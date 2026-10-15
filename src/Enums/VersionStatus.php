<?php

declare(strict_types=1);

namespace Rimba\Versioning\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum VersionStatus: string implements HasColor, HasLabel
{
    case Draft = 'draft';
    case Review = 'review';
    case Released = 'released';
    case Deprecated = 'deprecated';
    case Archived = 'archived';

    public function getLabel(): ?string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Review => 'warning',
            self::Released => 'success',
            self::Deprecated => 'danger',
            self::Archived => 'gray',
        };
    }
}
