<?php

abstract class Singleton
{
    protected static $instance;

    protected function __construct()  {}
    final private function __clone()  {}
    final private function __wakeup() {}

    final public static function getInstance()
    {
        static $instances = array();

        $calledClass = get_called_class();

        if (!isset($instances[$calledClass]))
        {
            $instances[$calledClass] = new $calledClass();
        }

        return $instances[$calledClass];
    }
}

