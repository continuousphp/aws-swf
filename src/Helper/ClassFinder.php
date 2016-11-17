<?php

namespace Continuous\Swf\Helper;

/**
 * Class ClassFinder
 * @package Continuous\Swf\Helper
 */
abstract class ClassFinder
{
    /**
     * The Composer classmap cache with lowercase index
     * @var mixed array|null
     */
    protected static $classmap = null;

    /**
     * @param string $namespace
     * @param string $needle
     * @param string $suffix
     */
    public static function findClass(string $namespace, string $needle, string $suffix = '')
    {
        $needle = ucfirst(strtolower($needle));
        $needle = str_replace('.', '\\', $needle, $count);

        if (0 === $count) {
            $needle .= '\\' . $needle;
        }

        $className = $namespace . '\\' . $needle . $suffix;

        if (true === class_exists($className)) {
            return $className;
        }

        if (null === static::$classmap) {
            $classmap = include dirname(__DIR__, 2)
                . DIRECTORY_SEPARATOR
                . 'vendor'
                . DIRECTORY_SEPARATOR
                . 'composer'
                . DIRECTORY_SEPARATOR
                . 'autoload_classmap.php'
            ;

            static::$classmap = [];

            foreach ($classmap as $k => $v) {
                if ($namespace !== substr($k, 0, strlen($namespace))) {
                    continue;
                }

                static::$classmap[mb_strtolower($k)] = $k;
            }

            unset($classmap);
        }

        if (true === isset(static::$classmap[mb_strtolower($className)])) {
            return static::$classmap[mb_strtolower($className)];
        }

        return null;
    }
}
