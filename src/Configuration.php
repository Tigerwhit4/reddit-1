<?php

use Sarracenia\Kernel\Event\RequestEvent;
use Dionaea\Modules\Configuration as BaseConfiguration,
    Dionaea\Arnica\ArnicaExtension,
    Dionaea\Debug\DebugExtension,
    Dionaea\Twig\TwigExtension;
use Reddit\Auth\AuthenticationManager;

class Configuration extends BaseConfiguration
{
    public function registerExtensions()
    {
        return array(
            new DebugExtension(),
            new ArnicaExtension(array(
                'host' => '',
                'user' => '',
                'pass' => '',
                'name' => '',
            )),
            new TwigExtension(),
        );
    }

    public function registerModules()
    {
        return array(
            new Reddit\RedditModule(),
        );
    }

    public function onRequest()
    {
        $repository = $this->app['arnica.manager']->getRepository('Reddit:User');
        $this->app['auth'] = $this->app->share(function($c) use($repository) {
            return new AuthenticationManager(
                $c['request'], $repository
            );
        });

        $twigEnvironment = $this->get('twig.env');
        $extension = new Reddit\Twig\RedditExtension($this->get('arnica.manager'), $this->get('auth'));
        $twigEnvironment->addExtension($extension);
    }
}
