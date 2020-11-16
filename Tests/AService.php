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

namespace Drift\Preload\Tests;

use Drift\React;
use React\EventLoop\LoopInterface;
use React\Promise\FulfilledPromise;
use function React\Promise\resolve;

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
        return resolve()
            ->then(function () {
                return React\usleep(100000, $this->loop);
            })
            ->then(function () {
                $this->increase();
            });
    }
}
