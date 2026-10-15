<?php

declare(strict_types=1);

use Rimba\Versioning\Enums\VersionIncrementType;
use Rimba\Versioning\Enums\VersionStatus;

return [
    'version' => [
        'increment' => VersionIncrementType::Patch->value,
        'status' => VersionStatus::Draft->value,
    ],
];
