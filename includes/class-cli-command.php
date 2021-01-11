<?php
/**
 * The file that defines the cli plugin functionality.
 *
 * @link       https://github.com/Zinkutal/release-instructions
 * @since      1.0.0
 *
 * @package    Release_instructions
 * @subpackage Release_instructions/includes
 */

WP_CLI::add_command('release-instructions', 'Release_instructions_Command');

/**
 * Release Instructions commands for the WP-CLI framework.
 *
 * Extends WP-CLI functionality to call core plugin class methods.
 *
 * @when after_wp_load
 * @alias ri
 *
 * @since      1.0.0
 * @package    Release_instructions
 * @subpackage Release_instructions/includes
 * @author     Alexander Kucherov <avdkucherov@gmail.com>
 * @see        https://github.com/wp-cli/wp-cli
 */
class Release_instructions_Command extends WP_CLI_Command
{
    protected $bar;

    public function __construct($bar)
    {
        $this->bar = $bar;
    }

    public function __invoke($args)
    {
        WP_CLI::success($this->bar . ':' . $args[0]);
    }

    /**
     *
     * @subcommand get-option
     * @alias option
     *
     **/
    function get_option($args, $assoc_args)
    {
        $default = 'example.com';
        if ($assoc_args['default']) {
            $default = $assoc_args['default'];
        }
        $return = get_option($args[0], $default);
        WP_CLI::success($return);
    }

}