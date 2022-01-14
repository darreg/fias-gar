<?php

declare(strict_types=1);

namespace App\DataLoad\Domain\Version\Entity;

interface VersionRepositoryInterface
{
    public function find(string $id): ?Version;

    public function get(string $id): Version;

    public function add(Version $version): void;

    public function remove(Version $version): void;
}
