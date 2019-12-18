<?php

namespace Drift\Preload\Event;

use Drift\HttpKernel\Event\PreloadEvent;
use React\Promise\FulfilledPromise;

/**
 * Class PreloadServicesCollector.
 */
class PreloadServicesCollector
{
    /**
     * @var array
     */
    private $services = [];

    /**
     * Inject service.
     *
     * @param string      $id
     * @param object      $object
     * @param string|null $method
     */
    public function inject(
        string $id,
        $object,
        ?string $method
    ) {
        $this->services[$id] = [$object, $method];
    }

    /**
     * Preload.
     *
     * @param PreloadEvent
     */
    public function preLoad(PreloadEvent $_)
    {
        $promise = new FulfilledPromise();

        foreach ($this->services as list($service, $methods)) {
            if (empty($methods)) {
                continue;
            }

            $methods = explode(',', $methods);
            $methods = array_map('trim', $methods);

            foreach ($methods as $method) {
                $promise = $promise->then(function () use ($service, $method) {
                    return $service->$method();
                });
            }
        }

        return $promise;
    }

    /**
     * Get preloaded services.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->services);
    }

    /**
     * Get preloaded services ids.
     *
     * @return string[]
     */
    public function getPreloadedServiceIds(): array
    {
        return array_keys($this->services);
    }
}
