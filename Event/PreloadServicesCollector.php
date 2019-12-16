<?php


namespace Drift\Preload\Event;

/**
 * Class PreloadServicesCollector
 */
class PreloadServicesCollector
{
    /**
     * @var array
     */
    private $services = [];

    /**
     * Inject service
     *
     * @param string $id
     * @param object $object
     */
    public function inject(
        string $id,
        $object
    )
    {
        $this->services[$id] = $object;
    }

    /**
     * Preload
     */
    public function preLoad()
    {
        // Just an empty callable
    }

    /**
     * Get preloaded services
     *
     * @return int
     */
    public function count() : int
    {
        return count($this->services);
    }

    /**
     * Get preloaded services ids
     *
     * @return string[]
     */
    public function getPreloadedServiceIds() : array
    {
        return array_keys($this->services);
    }
}