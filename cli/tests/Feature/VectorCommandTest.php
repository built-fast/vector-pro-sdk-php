<?php

declare(strict_types=1);

it('runs the vector command', function (): void {
    $this->artisan('vector')->assertExitCode(0);
});
