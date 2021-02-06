<?php

namespace ReleaseInstructions\Tools;

/**
 * The file that defines the utility class for core plugin class.
 *
 * @link       https://github.com/Zinkutal/release-instructions
 * @since      1.0.0
 *
 * @package    ReleaseInstructions
 * @subpackage ReleaseInstructions/Tools
 */

/**
 * Utility class.
 *
 * Used to define helper methods used by main plugin class.
 *
 * @since      1.0.0
 * @package    ReleaseInstructions
 * @subpackage ReleaseInstructions/Tools
 * @author     Alexander Kucherov <avdkucherov@gmail.com>
 */
class Utils
{

    /**
     * Get current php mode.
     *
     * @return bool CLI mode.
     */
    public static function isCLI(): bool
    {
        return defined('WP_CLI') && WP_CLI;
    }

    /**
     * Include's file if exists.
     *
     * @param string $file File's name.
     * @return string|false Returns filename on success.
     */
    public static function fileInclude(string $file)
    {
        if (function_exists('plugin_dir_path')) {
            if (is_file($file)) {
                require_once $file;
                return $file;
            }
        }

        return false;
    }

    /**
     * @param $key
     * @param string $group
     * @param false $force
     * @param null $found
     *
     * @return false|mixed
     *
     * @since 1.0.1
     */
    public static function cacheGet($key, $group = '', $force = false, $found = null)
    {
        if (function_exists('wp_cache_get')) {
            return wp_cache_get($key, $group, $force, $found);
        }

        return false;
    }

    /**
     * @param string|int $key
     * @param mixed $data
     * @param string $group
     * @param int $expire
     *
     * @return true
     *
     * @since 1.0.1
     */
    public static function cacheSet($key, $data, $group = 'default', $expire = 0): bool
    {
        if (function_exists('wp_cache_set')) {
            return wp_cache_set($key, $data, $group, $expire);
        }

        return true;
    }

    /**
     * @param $option
     * @param mixed $default
     *
     * @return true|mixed
     *
     * @since 1.0.1
     */
    public static function getOption($option, $default = false)
    {
        if (function_exists('get_site_option')) {
            return get_site_option($option, $default);
        }

        return true;
    }

    /**
     * @param string $option
     * @param mixed $value
     *
     * @return bool
     *
     * @since 1.0.1
     */
    public static function setOption(string $option, $value): bool
    {
        if (function_exists('update_site_option')) {
            return update_site_option($option, $value);
        }

        return false;
    }

    /**
     * @param string $plugin_folder
     * @return array
     */
    public static function getPlugins(string $plugin_folder = ''): array
    {
        if (function_exists('get_plugins')) {
            return get_plugins($plugin_folder);
        }

        return [];
    }
}
