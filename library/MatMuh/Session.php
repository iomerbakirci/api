<?php

namespace MatMuh;

use Zend\Session\Container;
use Zend\Session\SessionManager;

class Session
{
    public static function get($key)
    {
        $container = new Container('userData');
        return $container->offsetGet($key);
    }

    public static function set($key, $value)
    {
        $container = new Container('userData');
        $container->offsetSet($key, $value);
    }
}