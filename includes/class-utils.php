<?php

/**
 * The file that defines the utility class for core plugin class.
 *
 * @link       https://github.com/Zinkutal/release-instructions
 * @since      1.0.0
 *
 * @package    Release_instructions
 * @subpackage Release_instructions/includes
 */

/**
 * Utility class.
 *
 * Used to define helper methods used by main plugin class.
 *
 * @since      1.0.0
 * @package    Release_instructions
 * @subpackage Release_instructions/includes
 * @author     Alexander Kucherov <avdkucherov@gmail.com>
 */
class Release_instructions_Utils
{

    /**
     * Get current php mode.
     *
     * @return     bool     PHP mode.
     */
    public static function is_cli(): bool
    {
        return defined('WP_CLI') && WP_CLI;
    }

    /**
     * Include's file if exists.
     *
     * @param string $file File's name.
     * @return string|false
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
