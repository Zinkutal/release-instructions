<?php

/**
 * The file that defines the logger class for core plugin class.
 *
 * @link       https://github.com/Zinkutal/release-instructions
 * @since      1.0.0
 *
 * @package    Release_instructions
 * @subpackage Release_instructions/includes
 */

/**
 * Logger class.
 *
 * Used to define logger methods used by main plugin class.
 *
 * @since      1.0.0
 * @package    Release_instructions
 * @subpackage Release_instructions/includes
 * @author     Alexander Kucherov <avdkucherov@gmail.com>
 */
class Release_instructions_Logger
{

    /**
     * @var string
     */
    private static $delimiter = '##############################';

    public static function delimiter()
    {
        return self::$delimiter;
    }

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function log(string $message, string $type)
    {
        echo '<pre>';
        echo ($type ? '[' . $type . ']: ' : '') . $message;
        echo '</pre>';
    }

}
