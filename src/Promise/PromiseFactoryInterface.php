<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Cycle\Mapper;

use Spiral\Cycle\Exception\MapperException;
use Spiral\Cycle\Promise\PromiseInterface;

/**
 * Provides mapper ability to initiate proxied version of it's entities.
 */
interface PromiseFactoryInterface
{
    /**
     * Create entity proxy.
     *
     * @param array $scope
     * @return PromiseInterface|null
     *
     * @throws MapperException
     */
    public function initProxy(array $scope): ?PromiseInterface;
}