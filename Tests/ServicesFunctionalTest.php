<?php

/*
 * This file is part of the Drift Http Kernel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Drift\Preload\Tests;

use function Clue\React\Block\await;
use Drift\Preload\Event\PreloadServicesCollector;
use function Drift\React\sleep as async_sleep;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

/**
 * Class ServicesFunctionalTest.
 */
class ServicesFunctionalTest extends PreloadFunctionalTest
{
    /**
     * Decorate configuration.
     *
     * @param array $configuration
     *
     * @return array
     */
    protected static function decorateConfiguration(array $configuration): array
    {
        $configuration['preload'] = [
            'services' => [
                'service1' => null,
                'service2' => 'increase',
                'service3' => 'asyncIncrease',
                'non-existing-service' => null,
            ],
        ];

        $configuration['services'] = [
            '_defaults' => [
                'autowire' => false,
                'autoconfigure' => false,
                'public' => true,
            ],
            'reactphp.event_loop' => [
                'class' => LoopInterface::class,
                'public' => true,
                'factory' => [
                    Factory::class,
                    'create',
                ],
            ],
            'service1' => [
                'class' => AService::class,
                'arguments' => ['@event_loop'],
            ],
            'service2' => [
                'class' => AService::class,
                'arguments' => ['@event_loop'],
            ],
            'service3' => [
                'class' => AService::class,
                'arguments' => ['@event_loop'],
            ],
            'service4' => [
                'class' => AService::class,
                'arguments' => ['@event_loop'],
                'tags' => [
                    ['name' => 'preload', 'method' => 'increase'],
                    ['name' => 'another'],
                ],
            ],
            'service5' => [
                'class' => AService::class,
                'arguments' => ['@event_loop'],
                'tags' => [
                    ['name' => 'preload', 'method' => 'asyncIncrease,increase'],
                ],
            ],
            'service6' => [
                'class' => AService::class,
                'arguments' => ['@event_loop'],
                'tags' => [
                    ['name' => 'preload'],
                ],
            ],
            'service7' => [
                'class' => AService::class,
                'arguments' => ['@event_loop'],
            ],
        ];

        return $configuration;
    }

    /**
     * Test.
     */
    public function testServicesArePreloaded()
    {
        self::$kernel->preload();
        $collector = $this->get(PreloadServicesCollector::class);
        $loop = $this->get('reactphp.event_loop');
        $this->assertEquals(
            6,
            $collector->count()
        );

        await(async_sleep(1, $loop), $loop);

        $services = $collector->getPreloadedServiceIds();
        $this->assertTrue(in_array('service1', $services));
        $this->assertTrue(in_array('service2', $services));
        $this->assertTrue(in_array('service3', $services));
        $this->assertTrue(in_array('service4', $services));
        $this->assertTrue(in_array('service5', $services));
        $this->assertTrue(in_array('service6', $services));
        $this->assertFalse(in_array('service7', $services));
        $this->assertFalse(in_array('non-existing-service', $services));

        $this->assertEquals(0, $this->get('service1')->int);
        $this->assertEquals(1, $this->get('service2')->int);
        $this->assertEquals(1, $this->get('service3')->int);
        $this->assertEquals(1, $this->get('service4')->int);
        $this->assertEquals(2, $this->get('service5')->int);
        $this->assertEquals(0, $this->get('service6')->int);
    }
}
