<?php

namespace Release_Instructions;

/**
 * Load core interface.
 */
if (function_exists('plugin_dir_path')) {
    require plugin_dir_path(__FILE__) . 'interface-icore.php';
}

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
class Core implements ICore
{

    /**
     * The logger which is responsible for logging all actions and events.
     *
     * @since    1.0.0
     * @access   protected
     * @var Logger $logger Logs all actions for this plugin.
     */
    protected $logger;

    /**
     * Define the core functionality.
     *
     * Loads logger.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->logger = new Logger();
    }

    /**
     * Interlayer for a logger.
     *
     * @param string $message Message text.
     * @param string $status Status text.
     * @return $this
     *
     * @since     1.0.0
     */
    protected function log(string $message = '', string $status = ''): Core
    {
        $this->logger::log($message, $status);
        return $this;
    }

    /**
     * List all plugins with defined RI support.
     *
     * @return array Plugin list.
     *
     * @since     1.0.0
     * @access  protected
     */
    protected function get_plugins(): array
    {
        return array_filter(
            get_plugins(),
            function ($plugin) {
                return $plugin['RI'];
            }
        );
    }

    /**
     * List all plugins and included release instructions.
     *
     * @return array Plugin + files list.
     *
     * @since     1.0.0
     * @access protected
     */
    protected function get_files(): array
    {
        if ($ri_files = wp_cache_get('plugins', 'ri')) {
            return $ri_files ?: array();
        }

        $ri_files = array();
        if (!function_exists('plugin_dir_path')) {
            return $ri_files;
        }

        foreach ($this->get_plugins() as $plugin_key => $plugin) {
            $dir = WP_PLUGIN_DIR . '/' . plugin_dir_path($plugin_key) . 'ri';
            if (is_dir($dir)) {
                if ($files = glob($dir . '/*[.ri.inc]')) {
                    foreach ($files as $file) {
                        $ri_files[] = array(
                            'plugin' => $plugin_key,
                            'name' => $file,
                        );
                    }
                }
            }
        }

        wp_cache_set('plugins', $ri_files, 'ri');
        return $ri_files;
    }

    /**
     * Returns the list of all the RIs available.
     *
     * @param bool $exclude_executed Self-describing: excludes executed RIs.
     * @return array Returns assoc array, with a list of RI-supported plugins, with all RIs attached.
     *
     * @since     1.0.0
     * @access    protected
     */
    protected function get_updates($exclude_executed = true): array
    {
        $cache_key = 'updates_' . md5(json_encode($this->get_files()) . $exclude_executed);
        if ($updates = wp_cache_get($cache_key, 'ri')) {
            return $updates ?: array();
        }

        $updates = array();
        foreach ($this->get_files() as $file) {
            $plugin = $this->get_plugins()[$file['plugin']];
            (new Utils())::file_include($file['name']);

            $separator = '_';
            $function_name = trim(preg_replace('@[^a-z0-9_]+@', $separator, strtolower($plugin['Name'])), $separator);

            $regexp = '/^' . $function_name . '_ri_(?P<version>\d+)$/';
            $functions = get_defined_functions();

            foreach (preg_grep('/_\d+$/', $functions['user']) as $function) {
                // If this function is a module update function, add it to the list of
                // module updates.
                if (preg_match($regexp, $function, $matches)) {
                    $updates[$file['plugin']][$function] = $matches['version'];
                }
            }

            // Ensure that updates are applied in numerical order.
            foreach ($updates as &$plugin_updates) {
                ksort($plugin_updates);
            }
            unset($plugin_updates);
        }

        if ($exclude_executed) {
            foreach ($updates as $plugin => $functions) {
                foreach ($functions as $function => $version) {
                    if ($this->status_get($function)) {
                        unset($updates[$plugin][$function]);
                    }
                }
            }
        }

        wp_cache_set($cache_key, $updates, 'ri');
        return $updates;
    }

    /**
     * Runs RI. Updates RI status.
     *
     * @param string $function Function name.
     * @return $this
     *
     * @since     1.0.0
     * @access  protected
     */
    protected function function_execute($function = ''): Core
    {
        // Message.
        $this->log($this->logger::get_delimiter() . "\n")->log('Running ' . $function . '()' . "\n");

        // Execute.
        $is_executed = false;
        if (function_exists($function)) {
            if (!($message = $function())) {
                $message = 'Release instruction ' . $function . '() was executed.';
            }

            // Mark as executed.
            $is_executed = $this->status_set($function);
        } else {
            $message = 'Release instruction ' . $function . '() does not exist.';
        }

        // Message.
        return $this->log("\n")->log($message, $is_executed ? 'status' : 'notice')->log($this->logger::get_delimiter() . "\n\n");
    }

    /**
     * {@inheritdoc}
     */
    public function execute(string $function = ''): Core
    {
        // Preload the files.
        $updates = $this->get_updates(false);

        // Function - direct matching case.
        if (false === strpos($function, '*')) {
            $this->function_execute($function);
        } // Wildcard.
        else {
            $pattern = '@^' . str_replace('*', '.*', $function) . '$@';

            // Now run the updates.
            foreach ($updates as $plugin => $functions) {
                foreach ($functions as $_function => $version) {
                    if (preg_match($pattern, $_function)) {
                        $this->function_execute($_function);
                    }
                }
            }
        }

        // Message.
        return $this->log('Release instruction execution is finished.', 'success');
    }

    /**
     * {@inheritdoc}
     */
    public function execute_all(): Core
    {
        // Retrieve all RIs.
        $updates = $this->get_updates(false);

        // Now run the updates.
        foreach ($updates as $plugin => $functions) {
            foreach ($functions as $function => $version) {
                // Skip if already executed.
                if ($this->status_get($function)) {
                    continue;
                }

                $this->function_execute($function);
            }
        }

        // Message.
        return $this->log('Release instructions were executed.', 'success');
    }

    /**
     * {@inheritdoc}
     */
    public function preview(bool $all = false): Core
    {
        // Message.
        $message = $all ? 'List of all release instructions:' : 'Release instructions to be executed (in order):';
        $this->log($message . "\n");

        $count = 0;
        $scheduled_exists = false;
        foreach ($this->get_updates($all ? false : true) as $plugin => $functions) {
            foreach ($functions as $function => $version) {
                $message = $function . '()' . "\n";
                if (!($is_executed = $this->status_get($function))) {
                    $scheduled_exists = true;
                }
                $status = $all ? ($is_executed ? 'x' : ' ') : '';

                // Message.
                $this->log($message, $status);
                $count++;
            }
        }

        // Notice.
        if (!$count || !$scheduled_exists) {
            $this->log('Nothing to execute.' . "\n", 'notice');
        }

        // Message.
        return $this->log('End of list.' . "\n");
    }

    /**
     * {@inheritdoc}
     */
    public function status_get(string $function = '')
    {
        $ri_executed = get_site_option('ri_executed', array());
        return $function ? !empty($ri_executed[$function]) : $ri_executed;
    }

    /**
     * {@inheritdoc}
     */
    public function status_set(string $function = '', bool $flag = true)
    {
        $ri_executed = $this->status_get();
        $ri_executed[$function] = $flag ? true : false;
        return update_site_option('ri_executed', $ri_executed);
    }
}
