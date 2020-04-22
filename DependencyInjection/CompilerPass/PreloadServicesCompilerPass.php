<?php

/*
 * This file is part of the Drift Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Drift\Preload\DependencyInjection\CompilerPass;

use Drift\HttpKernel\AsyncKernelEvents;
use Drift\Preload\Event\PreloadServicesCollector;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class PreloadServicesCompilerPass.
 */
class PreloadServicesCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(PreloadServicesCollector::class)) {
            $collector = new Definition(PreloadServicesCollector::class);

            $collector->setPublic('test' === $container->getParameter('kernel.environment'));
            $collector->addTag('kernel.event_listener', [
                'event' => AsyncKernelEvents::PRELOAD,
                'method' => 'preload',
            ]);

            $container->setDefinition(PreloadServicesCollector::class, $collector);

            $serviceIds = array_merge(
                $this->getPreloadTaggedServices($container),
                $container->getParameter('preload.services'),
                $this->getAllPresetsServices($container)
            );

            foreach ($serviceIds as $serviceId => $method) {
                $this->makeServicePreloaded(
                    $container,
                    $collector,
                    $serviceId,
                    $method
                );
            }
        }
    }

    /**
     * Make a service a preload one.
     *
     * @param ContainerBuilder $container
     * @param Definition       $collector
     * @param string           $serviceId
     * @param string|null      $method
     */
    private function makeServicePreloaded(
        ContainerBuilder $container,
        Definition $collector,
        string $serviceId,
        ?string $method
    ) {
        if (!$container->has($serviceId)) {
            return;
        }

        $collector->addMethodCall('inject', [
            $serviceId,
            new Reference($serviceId),
            $method,
        ]);
    }

    /**
     * Return an array of all presets services.
     *
     * @param ContainerBuilder $container
     *
     * @return array
     */
    private function getAllPresetsServices(ContainerBuilder $container): array
    {
        $presets = $container->getParameter('preload.presets');
        $services = [];

        foreach ($presets as $preset) {
            $services = array_merge(
                $services,
                $preset::getServicesToPreload()
            );
        }

        return $services;
    }

    /**
     * Get services tagged.
     *
     * @param ContainerBuilder $container
     *
     * @return array
     */
    private function getPreloadTaggedServices(ContainerBuilder $container): array
    {
        $services = $container->findTaggedServiceIds('preload');

        return array_map(function (array $options) {
            return $options[0]['method'] ?? null;
        }, $services);
    }
}
