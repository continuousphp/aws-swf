<?php

namespace Continuous\Swf\Helper;

abstract class ValueObject
{
    /**
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public function __get(string $key)
    {
        if (!property_exists($this, $key)) {
            throw new \Exception('This property non exist');
        }

        return $this->$key;
    }

    /**
     * @param $key
     * @param $value
     * @throws \Exception
     */
    public function __set($key, $value)
    {
        throw new \Exception('In ValueObject you are not allowed to setup property');
    }
}
