<?php

namespace App;

use App\Libs\CoreConfig;
use kalanis\kw_confs\Config;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function initStorage(): void
    {
        if (class_exists('\kalanis\kw_mapper\Storage\Database\ConfigStorage')) {
            // just init my storage
            require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'storage.php';
        }
    }

    public function initConfs(): void
    {
        Config::loadClass(new CoreConfig());
    }
}
