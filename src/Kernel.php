<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    protected function scheduleCommands(Schedule $schedule)
    {
        // Schedule the command to run on December 30th each year
        $schedule->command('app:scheduled-task')->cron('30 14 22 08 *');
    }
}
