<?php

namespace Bitrix\Components\Comments\Comments\Traits;

trait NewInstance
{
    private static $instance = null;

    public static function i(...$args)
    {
        if (null === static::$instance) {
            return static::$instance = static::new($args);
        }

        return static::$instance;
    }

    public static function new(...$args)
    {
        return new static($args);
    }

    public static function make(...$args)
    {
        return self::new($args);
    }
}