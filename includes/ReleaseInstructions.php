<?php

namespace ReleaseInstructions;

/**
 * The file that defines the core plugin class.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/Zinkutal/release-instructions
 * @since      1.0.0
 *
 * @package    ReleaseInstructions
 */

use ReleaseInstructions\Tools\Utils;
use ReleaseInstructions\Command\CoreCommand;

/**
 * The core plugin class.
 *
 * This is used to define plugin functionality methods,
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    ReleaseInstructions
 * @author     Alexander Kucherov <avdkucherov@gmail.com>
 */
class ReleaseInstructions
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @var Loader $loader Maintains and registers all hooks for the plugin.
     *
     * @since 1.0.0
     * @access protected
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @var string $release_instructions The string used to uniquely identify this plugin.
     *
     * @since 1.0.0
     * @access protected
     */
    protected $release_instructions;

    /**
     * The current version of the plugin.
     *
     * @var string $version The current version of the plugin.
     *
     * @since 1.0.0
     * @access protected
     */
    protected $version;

    /**
     * The core functionality of the plugin.
     *
     * @var CoreCommand $core Core functionality.
     *
     * @since 1.0.0
     * @access protected
     */
    protected $ri;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the release instructions and the plugin version that can be used throughout the plugin.
     * Load the dependencies, utilities and logger.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        if (defined('RELEASE_INSTRUCTIONS_VERSION')) {
            $this->version = RELEASE_INSTRUCTIONS_VERSION;
        } else {
            $this->version = '1.0.1';
        }

        $this->release_instructions = 'release-instructions';
        $this->loadDependencies();
        $this->ri = new CoreCommand();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Loader. Orchestrates the hooks of the plugin.
     * - Utils. Adds helper functions.
     * - Logger. Logs all actions and events.
     * - Core_Command. Defines all core commands.
     * - CLI_Command. Defines all cli commands.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since 1.0.0
     * @access private
     */
    private function loadDependencies(): ReleaseInstructions
    {
        /**
         * The class responsible for defining command line commands.
         */
        if ((new Utils())::isCLI()) {
            if (function_exists('plugin_dir_path')) {
                require_once plugin_dir_path(__DIR__) . 'includes/Command/CLICommand.php';
            }
        }

        $this->loader = new Loader();
        $this->loader->addFilter('extra_plugin_headers', $this, 'addRiHeader');

        return $this;
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 1.0.0
     */
    public function run(): ReleaseInstructions
    {
        $this->loader->run();

        return $this;
    }

    /**
     * Extends plugin headers to support Release Instructions plugin.
     */
    public function addRiHeader(): array
    {
        return array('RI' => 'RI');
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return string The name of the plugin.
     *
     * @since 1.0.0
     */
    public function getReleaseInstructions(): string
    {
        return $this->release_instructions;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return Loader Orchestrates the hooks of the plugin.
     *
     * @since 1.0.0
     */
    public function getLoader(): Loader
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return string The version number of the plugin.
     *
     * @since 1.0.0
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Retrieve core functionality.
     *
     * @return CoreCommand Core functionality.
     *
     * @since 1.0.0
     */
    public function getRi(): CoreCommand
    {
        return $this->ri;
    }
}