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
 * Class SymfonyPreset.
 */
final class SymfonyPreset implements Preset
{
    /**
     * Get services to preload.
     */
    public static function getServicesToPreload(): array
    {
        return [
            'router' => 'getMatcher,getGenerator',
            'event_dispatcher' => null,
            'request_stack' => null,
            'http_kernel' => null,
            'kernel' => null,
            'twig' => null,
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
        new \Symfony\Component\HttpFoundation\Response('', 200);
        new \Symfony\Component\HttpFoundation\Request();

        return [
            'Symfony\Component\HttpKernel\Event\KernelEvent',
            'Symfony\Component\HttpKernel\Event\RequestEvent',
            'Symfony\Component\HttpKernel\Event\ViewEvent',
            'Symfony\Component\HttpKernel\Event\ResponseEvent',
            'Symfony\Component\HttpKernel\Event\TerminateEvent',
            'Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent',
            'Symfony\Component\HttpKernel\Event\ControllerEvent',
            'Symfony\Component\HttpKernel\Event\FinishRequestEvent',
            "Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata",

            "Symfony\Component\HttpFoundation\Request",
            "Symfony\Component\HttpFoundation\Response",
            "Symfony\Component\HttpFoundation\ResponseHeaderBag",
            "Symfony\Component\HttpFoundation\HeaderUtils",
            "Symfony\Component\HttpFoundation\AcceptHeader",
            "Symfony\Component\HttpFoundation\AcceptHeaderItem",

            'Symfony\Component\HttpFoundation\ParameterBag',
            'Symfony\Component\HttpFoundation\FileBag',
            'Symfony\Component\HttpFoundation\ServerBag',
            'Symfony\Component\HttpFoundation\HeaderBag',

            "RingCentral\Psr7\Stream",
            "RingCentral\Psr7\Uri",
            "RingCentral\Psr7\BufferStream",
            "Psr\Http\Message\UriInterface",
        ];
    }
}
