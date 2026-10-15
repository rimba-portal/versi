<?php

declare(strict_types=1);

namespace Rimba\Versioning\Services;

use InvalidArgumentException;
use Rimba\Versioning\Enums\VersionIncrementType;

final class SemanticVersionService
{
    public function next(
        int $major,
        int $minor,
        int $patch,
        VersionIncrementType $increment,
    ): array {
        return match ($increment) {
            VersionIncrementType::Major => [$major + 1, 0, 0],
            VersionIncrementType::Minor => [$major, $minor + 1, 0],
            VersionIncrementType::Patch => [$major, $minor, $patch + 1],
        };
    }

    public function format(int $major, int $minor, int $patch): string
    {
        return sprintf('%d.%d.%d', $major, $minor, $patch);
    }

    public function parse(string $version): array
    {
        if (preg_match('/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)$/', $version, $matches) !== 1) {
            throw new InvalidArgumentException(
                "Invalid semantic version: {$version}",
            );
        }

        return [
            (int) $matches[1],
            (int) $matches[2],
            (int) $matches[3],
        ];
    }
}
