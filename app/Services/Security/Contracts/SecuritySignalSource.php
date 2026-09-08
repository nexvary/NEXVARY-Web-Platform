<?php

namespace App\Services\Security\Contracts;

interface SecuritySignalSource
{
    public function key(): string;

    public function enabled(): bool;

    /** @return array<int, array<string, mixed>> */
    public function signals(): array;

    /** @return array<string, mixed> */
    public function health(): array;
}
