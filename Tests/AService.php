<?php

namespace Drift\Preload\Tests;

use Drift\React;
use React\EventLoop\LoopInterface;
use React\Promise\FulfilledPromise;

/**
 * Class AService.
 */
class AService
{
    /**
     * @var LoopInterface
     *
     * Loop
     */
    private $loop;

    /**
     * @var int
     */
    public $int = 0;

    /**
     * AService constructor.
     *
     * @param LoopInterface $loop
     */
    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    /**
     * Increase.
     */
    public function increase()
    {
        ++$this->int;
    }

    /**
     * Increase.
     */
    public function asyncIncrease()
    {
        return (new FulfilledPromise())
            ->then(function () {
                return React\usleep(100000, $this->loop);
            })
            ->then(function () {
                $this->increase();
            });
    }
}
