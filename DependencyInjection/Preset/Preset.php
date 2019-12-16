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

namespace Drift\Preload\DependencyInjection\Preset;

/**
 * Interface Preset
 */
interface Preset
{
    /**
     * Get services to preload
     */
    public static function getServicesToPreload() : array;
}