<?php

declare(strict_types=1);

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

use function Termwind\render;

final class VectorCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'vector';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run Vector CLI';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        render(<<<'HTML'
            <div class="py-1 ml-2">
                <div class="px-1 bg-blue-300 text-black">Vector CLI</div>
                <em class="ml-1">
                  Hello from Vector!
                </em>
            </div>
        HTML);

        return self::SUCCESS;
    }
}
