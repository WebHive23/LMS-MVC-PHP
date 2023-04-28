<?php

namespace App\Core;

use BadMethodCallException;

abstract class Controller
{
    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  string  $method
     * @param  array  $arguments
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        throw new BadMethodCallException(sprintf(
            'Method "%s" does not exist in class "%s"',
            $name,
            get_class($this)
        ));
    }
}
