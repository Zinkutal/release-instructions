<?php

namespace ReleaseInstructions\Tools;

/**
 * The file that defines the utility class for core plugin class.
 *
 * @link  https://github.com/Zinkutal/release-instructions
 * @since 1.0.0
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
     * @return boolean CLI mode.
     */
    public static function isCLI(): bool
    {
        return \defined('WP_CLI') && WP_CLI;
    }

    /**
     * Include's file if exists.
     *
     * @param  string $file File's name.
     * @return string|false Returns filename on success.
     */
    public static function fileInclude(string $file)
    {
        if (\function_exists('plugin_dir_path') && is_file($file)) {
            include_once $file;
            return $file;
        }

        return false;
    }

    /**
     * Gets cache.
     *
     * @param $key
     * @param string $group
     * @param false  $force
     * @param null   $found
     *
     * @return false|mixed
     *
     * @since 1.0.1
     */
    public static function cacheGet($key, $group = '', $force = false, $found = null)
    {
        if (self::isMultisite() && \function_exists('get_current_blog_id')) {
            $key .= '_' . get_current_blog_id();
        }

        if (\function_exists('wp_cache_get')) {
            return wp_cache_get($key, $group, $force, $found);
        }

        return false;
    }

    /**
     * Sets cache.
     *
     * @param string|integer $key
     * @param mixed          $data
     * @param string         $group
     * @param integer        $expire
     *
     * @return true
     *
     * @since 1.0.1
     */
    public static function cacheSet($key, $data, $group = 'default', $expire = 0): bool
    {
        if (self::isMultisite() && \function_exists('get_current_blog_id')) {
            $key .= '_' . get_current_blog_id();
        }

        if (\function_exists('wp_cache_set')) {
            return wp_cache_set($key, $data, $group, $expire);
        }

        return true;
    }

    /**
     * Gets db option.
     *
     * @param $option
     * @param mixed  $default
     *
     * @return true|mixed
     *
     * @since 1.0.1
     */
    public static function getOption($option, $default = false)
    {
        if (self::isMultisite() && \function_exists('get_blog_option') && \function_exists('get_current_blog_id')) {
            return get_blog_option(get_current_blog_id(), $option, $default);
        }

        if (\function_exists('get_site_option')) {
            return get_site_option($option, $default);
        }

        return true;
    }

    /**
     * Sets db option.
     *
     * @param string $option
     * @param mixed  $value
     *
     * @return boolean
     *
     * @since 1.0.1
     */
    public static function setOption(string $option, $value): bool
    {
        if (self::isMultisite() && \function_exists('update_blog_option') && \function_exists('get_current_blog_id')) {
            return update_blog_option(get_current_blog_id(), $option, $value);
        }

        if (\function_exists('update_site_option')) {
            return update_site_option($option, $value);
        }

        return false;
    }

    /**
     * Returns list of enabled plugins, including network.
     *
     * @param  string $plugin_folder
     * @return array
     */
    public static function getPlugins(string $plugin_folder = ''): array
    {
        if (!\function_exists('get_plugins')) {
            return [];
        }

        $plugins = get_plugins($plugin_folder);
        $active  = self::getOption('active_plugins', []);
        if (self::isMultisite() && \function_exists('get_site_option')) {
            $active = array_merge($active, array_keys(get_site_option('active_sitewide_plugins', [])));
        }

        return array_filter(
            $plugins,
            static function ($key) use ($active) {
                return \in_array($key, $active, false);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Checks multi-siting.
     *
     * @return boolean
     *
     * @since 1.0.2
     */
    public static function isMultisite(): bool
    {
        return \function_exists('is_multisite') ? is_multisite() : false;
    }
}
