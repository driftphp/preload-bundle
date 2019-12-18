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

namespace Drift\Preload\Preset;

/**
 * Class ReactPreset.
 */
final class ReactPreset implements Preset
{
    /**
     * Get services to preload.
     */
    public static function getServicesToPreload(): array
    {
        return [
        ];
    }

    /**
     * Get namespaces to preload.
     *
     * Return an array of namespaces to preload
     *
     * @param string $projectDir
     *
     * @return array
     */
    public static function getNamespacesToPreload(string $projectDir): array
    {
        return [
            'React\Http\Io\ServerRequest',
            'React\Http\Io\EmptyBodyStream',
            'React\Http\Response',
            'React\Promise\CancellationQueue',
        ];
    }
}
