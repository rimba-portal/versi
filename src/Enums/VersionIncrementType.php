<?php

declare(strict_types=1);

namespace Rimba\Versioning\Enums;

enum VersionIncrementType: string
{
    case Major = 'major';
    case Minor = 'minor';
    case Patch = 'patch';
}
