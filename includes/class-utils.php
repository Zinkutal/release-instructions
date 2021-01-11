<?php

namespace Release_Instructions;

/**
 * The file that defines the utility class for core plugin class.
 *
 * @link       https://github.com/Zinkutal/release-instructions
 * @since      1.0.0
 *
 * @package    Release_Instructions
 * @subpackage Release_Instructions/includes
 */

/**
 * Utility class.
 *
 * Used to define helper methods used by main plugin class.
 *
 * @since      1.0.0
 * @package    Release_Instructions
 * @subpackage Release_Instructions/includes
 * @author     Alexander Kucherov <avdkucherov@gmail.com>
 */
class Utils
{

    /**
     * Get current php mode.
     *
     * @return bool CLI mode.
     */
    public static function is_cli(): bool
    {
        return defined('WP_CLI') && WP_CLI;
    }

    /**
     * Include's file if exists.
     *
     * @param string $file File's name.
     * @return string|false Returns filename on success.
     */
    public static function file_include(string $file)
    {
        if (function_exists('plugin_dir_path')) {
            if (is_file($file)) {
                require_once $file;
                return $file;
            }
        }

        return false;
    }

}
