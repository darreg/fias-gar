<?php

namespace App;

use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Bus\Query\QueryHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/' . $this->environment . '/*.yaml');

        if (is_file(\dirname(__DIR__) . '/config/services.yaml')) {
            $container->import('../config/services.yaml');
            $container->import('../config/{services}_' . $this->environment . '.yaml');
        } elseif (is_file($path = \dirname(__DIR__) . '/config/services.php')) {
            (require $path)($container->withPath($path), $this);
        }

        $container->import('./**/services.yaml');
        $container->import('./**/{services}_' . $this->environment . '.yaml');
        $container->import('./**/{package}_' . $this->environment . '.*.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/{routes}/' . $this->environment . '/*.yaml');
        $routes->import('../config/{routes}/*.yaml');

        if (is_file(\dirname(__DIR__) . '/config/routes.yaml')) {
            $routes->import('../config/routes.yaml');
        } elseif (is_file($path = \dirname(__DIR__) . '/config/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }

        $routes->import('./**/routes.yaml');
        $routes->import('./**/{routes}_' . $this->environment . '.yaml');
    }

    protected function build(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(CommandHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => 'command.bus']);
        $container->registerForAutoconfiguration(QueryHandlerInterface::class)
            ->addTag('messenger.message_handler', ['bus' => 'query.bus']);
    }
}
