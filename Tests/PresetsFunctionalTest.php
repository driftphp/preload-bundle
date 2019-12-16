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
 * Class PresetsFunctionalTest.
 */
class PresetsFunctionalTest extends PreloadFunctionalTest
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
            "presets" => [
                '@symfony'
            ]
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
        $this->assertTrue(in_array('router', $services));
        $this->assertTrue(in_array('event_dispatcher', $services));
    }
}
