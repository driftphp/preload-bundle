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

use Drift\Preload\Event\PreloadServicesCollector;

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
            "services" => [
                'service1',
                'service2',
                'non-existing-service'
            ]
        ];

        $configuration['services'] = [
            '_defaults' => [
                'autowire' => false,
                'autoconfigure' => false,
                'public' => true,
            ],
            'service1' => [
                'class' => AService::class,
            ],
            'service2' => [
                'class' => AService::class,
            ],
            'service3' => [
                'class' => AService::class,
                'tags' => [
                    ['name' => 'preload']
                ]
            ],
            'service4' => [
                'class' => AService::class,
            ],
        ];

        return $configuration;
    }

    /**
     * Test
     */
    public function testServicesArePreloaded()
    {
        self::$kernel->preload();
        $collector = $this->get(PreloadServicesCollector::class);
        $this->assertEquals(
            3,
            $collector->count()
        );

        $services = $collector->getPreloadedServiceIds();
        $this->assertTrue(in_array('service1', $services));
        $this->assertTrue(in_array('service2', $services));
        $this->assertTrue(in_array('service3', $services));
        $this->assertFalse(in_array('service4', $services));
        $this->assertFalse(in_array('non-existing-serviceAsyncKernelFunctionalTest.php', $services));
    }
}
