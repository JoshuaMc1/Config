<?php

namespace JoshuaMc1\Config;

class Config
{
    private static $env = [];
    private static $config = [];

    /**
     * Load the environment variables from the given .env file and the config files
     * from the given directory.
     *
     * @param string $envPath    The path to the .env file to load. Defaults to '.env'
     * @param string $configPath The path to the directory containing the config files
     *                            to load. Defaults to 'config'
     *
     * @throws \Exception If the .env file does not exist at the specified path
     */
    public static function load(string $envPath = '.env', string $configPath = 'config')
    {
        self::loadEnv($envPath);
        self::loadConfig($configPath);
    }

    /**
     * Load the environment variables from the given .env file into the class.
     *
     * @param string $envPath The path to the .env file to load. Defaults to '.env'
     *
     * @throws \Exception If the .env file does not exist at the specified path
     */
    private static function loadEnv(string $envPath)
    {
        if (!file_exists($envPath)) {
            throw new \Exception("The .env file does not exist at $envPath");
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            self::$env[trim($key)] = trim($value);
        }
    }

    /**
     * Load the config files from the given directory into the class.
     *
     * The directory is scanned for .php files and each file is required and
     * added to the class under the key that is the filename without the
     * extension.
     *
     * @param string $configPath The path to the directory containing the config
     *                            files to load
     *
     * @throws \Exception If the directory does not exist at the specified path
     */
    private static function loadConfig(string $configPath)
    {
        if (!is_dir($configPath)) {
            throw new \Exception("The directory $configPath does not exist");
        }

        $files = scandir($configPath);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $key = pathinfo($file, PATHINFO_FILENAME);
                self::$config[$key] = require $configPath . DIRECTORY_SEPARATOR . $file;
            }
        }
    }

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
    public static function env(string $key, $default = null)
    {
        return self::$env[$key] ?? $default;
    }

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
    public static function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $segment) {
            if (!isset($value[$segment])) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}
