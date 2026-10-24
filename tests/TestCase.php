<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Fortify\Features;

abstract class TestCase extends BaseTestCase
{
    public function createApplication(): Application
    {
        /** @var Application $app */
        $app = require Application::inferBasePath().'/bootstrap/app.php';

        $this->traitsUsedByTest = class_uses_recursive(static::class);

        // Passkeys ship commented out in config/fortify.php; enable them before Fortify registers its routes so they stay tested.
        $app->afterBootstrapping(LoadConfiguration::class, function (Application $app): void {
            $app->make(Repository::class)->push('fortify.features', Features::passkeys([
                'confirmPassword' => true,
            ]));
        });

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
