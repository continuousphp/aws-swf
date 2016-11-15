<?php

namespace Continuous\Swf\Helper;

/**
 * Class Config
 * @package Continuous\Swf\Helper
 */
abstract class Config
{
    const PATH = 'config' . DIRECTORY_SEPARATOR . 'autoload';

    static protected $config = null;

    /**
     * @param bool $useCache
     * @return \stdClass
     */
    public static function getAll(bool $useCache = true)
    {
        if (true === $useCache && static::$config !== null) {
            return static::$config;
        }

        $path = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . static::PATH;

        $files = array_filter(
            scandir($path),
            function ($element) {
                return '.php' === substr($element, -4);
            }
        );

        $configArray = [];

        foreach ($files as $file) {
            $configArray += require($path . DIRECTORY_SEPARATOR . $file);
        }

        return static::$config = json_decode(json_encode($configArray));
    }

    /**
     * @param string $section
     * @param bool $useCache
     * @return mixed
     * @throws SectionConfigNotExistsException
     */
    public static function getSection(string $section, bool $useCache = true)
    {
        $config = static::getAll($useCache);

        if (false === property_exists($config, $section)) {
            throw new SectionConfigNotExistsException(sprintf(
                'Config section "%s" not exists in current environment, you should try without cache.',
                $section
            ));
        }

        return $config->$section;
    }

    /**
     * @param \stdClass $object
     * @return \stdClass
     */
    public static function awsConverter(\stdClass $object) : array
    {
        if (!empty($object->key) && !empty($object->secret)) {
            $object->credentials = [
                'key'    => $object->key,
                'secret' => $object->secret,
            ];
        }

        if (property_exists($object, 'profile') && '' === $object->profile) {
            unset($object->profile);
        }

        unset($object->key);
        unset($object->secret);

        return json_decode(json_encode($object), true);
    }
}
