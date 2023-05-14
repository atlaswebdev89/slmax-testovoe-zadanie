<?php

namespace app\users;

use app\users\Users;
use Exception;

class UsersList
{
    public static $className = __CLASS__;

    public function __construct()
    {
        echo get_class($this);
        if (!class_exists(Users::class)) {
            throw new Exception("Not found class: " . Users::class);
        }
    }

    public static function getClass(): string
    {
        return __CLASS__;
    }

    public function getClassDynamic(object $Object = null): string
    {
        $value     = $Object ?? $this;
        $className = get_class($value);
        $arr       = explode('\\', $className);

        return $arr[count($arr) - 1];
    }

    public static function getClassName(): string
    {
        return self::$className;
    }


    public static function test()
    {
    }
}