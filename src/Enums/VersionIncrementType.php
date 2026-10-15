<?php

declare(strict_types=1);

namespace Rimba\Versioning\Enums;

use Filament\Support\Contracts\HasLabel;

enum VersionIncrementType: string implements HasLabel
{
    case Major = 'major';
    case Minor = 'minor';
    case Patch = 'patch';

    public function getLabel(): ?string
    {
        return ucfirst($this->value);
    }
}
