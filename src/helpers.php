<?php

use JoshuaMc1\Config\Config;

if (!function_exists('config')) {
    /**
     * Get the value of the given key from the config array.
     *
     * If the key does not exist, the given default value is returned.
     *
     * The key can contain dots to traverse nested arrays.
     *
     * @param string $key   The key to retrieve from the config array
     * @param mixed  $default The value to return if the key does not exist
     *
     * @return mixed The value of the key, or the given default
     */
    function config(string $key, $default = null)
    {
        return Config::get($key, $default);
    }
}

if (!function_exists('env')) {
    /**
     * Get the value of the given environment variable, or the given default if
     * the variable does not exist.
     *
     * @param string $key   The name of the environment variable to retrieve
     * @param mixed  $default The value to return if the environment variable does
     *                        not exist
     *
     * @return mixed The value of the environment variable, or the given default
     */
    function env(string $key, $default = null)
    {
        return Config::env($key, $default);
    }
}
