<?php

namespace MvLabsDriversLicenseValidation;

use Resque_Event;
use Resque\Plugins\Retry;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap()
    {
        $retryPlugin = new Retry();

        Resque_Event::listen('beforePerform', [$retryPlugin, 'beforePerform']);
        Resque_Event::listen('afterPerform', [$retryPlugin, 'afterPerform']);
        Resque_Event::listen('onFailure', [$retryPlugin, 'onFailure']);
    }
}
