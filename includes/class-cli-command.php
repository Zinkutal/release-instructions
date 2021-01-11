<?php

namespace Release_Instructions;

/**
 * The file that defines the cli plugin functionality.
 *
 * @link       https://github.com/Zinkutal/release-instructions
 * @since      1.0.0
 *
 * @package    Release_Instructions
 * @subpackage Release_Instructions/includes
 */

\WP_CLI::add_command('ri', (new CLI_Command()));

/**
 * Implements Release Instructions commands for the WP-CLI framework.
 *
 * @since      1.0.0
 * @package    Release_Instructions
 * @subpackage Release_Instructions/includes
 * @author     Alexander Kucherov <avdkucherov@gmail.com>
 * @see        https://github.com/wp-cli/wp-cli
 */
class CLI_Command
{
    /**
     * Core Command functionality.
     *
     * @var Core_Command Responsible for running release-instructions.
     *
     * @since 1.0.0
     * @access protected
     */
    protected $core;

    /**
     * CLI_Command constructor.
     *
     * Initializes Core_Command.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->core = new Core_Command();
    }

    /**
     * Runs a single RI.
     *
     * ## OPTIONS
     *
     * <function>
     * : The name of the function(ri).
     *
     * ## EXAMPLES
     *
     *     wp ri function release_instructions_ri_1
     *
     * @when after_wp_load
     * @subcommand function
     *
     * @since 1.0.0
     */
    public function execute($args)
    {
        list($function) = $args;
        $this->core->execute((string)$function);
    }

    /**
     * Executes scheduled RIs.
     *
     * ## EXAMPLES
     *
     *     wp ri run
     *
     * @when after_wp_load
     * @subcommand run
     *
     * @since 1.0.0
     */
    public function execute_all()
    {
        $this->core->execute_all();
    }

    /**
     * Previews scheduled release instructions.
     *
     * ## OPTIONS
     *
     * [<all>]
     * : Flag (1 or 0).
     *
     * ## EXAMPLES
     *
     *     wp ri preview
     *     wp ri preview 1
     *     wp ri preview 0
     *
     * @when after_wp_load
     * @subcommand preview
     *
     * @since 1.0.0
     */
    public function peview($args)
    {
        if ($args) {
            list($all) = $args;
        }
        $this->core->preview(isset($all) ? (bool)$all : false);
    }

    /**
     * Sets status for the release instruction function.
     *
     * ## OPTIONS
     *
     * <function>
     * : The name of the function(ri).
     *
     * [<flag>]
     * : Flag (1 or 0).
     *
     * ## EXAMPLES
     *
     *     wp ri status release_instructions_ri_1
     *     wp ri status release_instructions_ri_1 1
     *     wp ri status release_instructions_ri_1 0
     *
     * @when after_wp_load
     * @subcommand status
     *
     * @since 1.0.0
     */
    public function status($args)
    {
        if (count($args) > 1) {
            list($function, $flag) = $args;
            $this->core->status_set((string)$function, (bool)$flag);
            $this->core->log(sprintf('Status for %s() was set to "%d".', $function, $flag ? 1 : 0), 'success');
            return;
        }

        list($function,) = $args;
        $status = $this->core->status_get((string)$function);
        $this->core->log(sprintf('Status for %s() is "%d".', $function, $status ? 1 : 0), 'success');
    }

}