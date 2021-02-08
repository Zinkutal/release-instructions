<?php

namespace ReleaseInstructions\Admin;

/**
 * The file that defines the admin area class.
 *
 * @link       https://github.com/Zinkutal/release-instructions
 * @since      1.1.0
 *
 * @package    ReleaseInstructions
 * @subpackage ReleaseInstructions/Admin
 */

// @todo: Replace dirty dependencies below with a proper hook init.
if (!class_exists('WP_List_Table')) {
    if (!defined('ABSPATH')) {
        exit;
    }
    require_once(ABSPATH . 'wp-admin/includes/class-wp-screen.php');//added
    require_once(ABSPATH . 'wp-admin/includes/screen.php');//added
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
    require_once(ABSPATH . 'wp-admin/includes/template.php');
}

if (function_exists('plugin_dir_path')) {
    require_once plugin_dir_path(__FILE__) . 'View/ListFunctions.php';
}

use WP_List_Table;

use function ReleaseInstructions\Admin\View\_get_all_release_instruction;
use function ReleaseInstructions\Admin\View\_get_release_instruction_count;

/**
 * List Table class.
 *
 * Used to define core command methods to extend child command methods. Example: CLI_Command
 *
 * @since      1.1.0
 * @package    ReleaseInstructions
 * @subpackage ReleaseInstructions/Admin
 * @author     Alexander Kucherov <avdkucherov@gmail.com>
 */
class ListTable extends WP_List_Table
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct(
            array(
                'singular' => 'release-instruction',
                'plural' => 'release-instructions',
                'ajax' => false,
                //'screen'   => null,
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get_columns(): array
    {
        return array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title', ''),
            'version' => __('Version', ''),
            'status' => __('Status', ''),
            'plugin' => __('Plugin', ''),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function column_default($item, $column_name): string
    {
        switch ($column_name) {
            case 'title':
            case 'plugin':
            case 'version':
            case 'status':
                return $item[$column_name] ?: '';
            default:
                return print_r($item, true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepare_items(): void
    {
        $this->_column_headers = array($this->get_columns(), $hidden = array(), $this->get_sortable_columns());

        $per_page = 20;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;

        // only necessary because we have sample data
        $args = array(
            'offset' => $offset,
            'number' => $per_page,
        );

        if (isset($_REQUEST['orderby'], $_REQUEST['order'])) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order'] = $_REQUEST['order'];
        }

        $this->items = _get_all_release_instruction($args);

        $this->set_pagination_args(
            array(
                'total_items' => _get_release_instruction_count(),
                'per_page' => $per_page
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get_table_classes(): array
    {
        return array('widefat', 'fixed', 'striped', $this->_args['plural']);
    }

    /**
     * {@inheritdoc}
     */
    public function no_items(): void
    {
        _e('No Release Instructions found.', '');
    }

    /**
     * @param $item
     * @return string
     */
    public function column_title($item): string
    {
        $actions = array();
        $actions['run'] = sprintf(
            '<a href="%s" data-id="%s" title="%s">%s</a>',
            admin_url('admin.php?page=release-instructions&action=run&id=' . $item['title']),
            $item['title'],
            __('Run this item', ''),
            __('Run', '')
        );
        $actions['reset'] = sprintf(
            '<a href="%s" data-id="%s" title="%s">%s</a>',
            admin_url('admin.php?page=release-instructions&action=reset&id=' . $item['title']),
            $item['title'],
            __('Reset this item', ''),
            __('Reset', '')
        );

        return sprintf(
            '<a href="%1$s"><strong>%2$s</strong></a> %3$s',
            admin_url('admin.php?page=release-instructions&action=view&id=' . $item['title']),
            $item['title'],
            $this->row_actions($actions)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get_sortable_columns(): array
    {
        return array(
            'title' => array('title', true),
            'version' => array('version', true),
            'plugin' => array('plugin', true),
            'status' => array('status', true),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function get_bulk_actions(): array
    {
        return array(
            'reset' => __('Reset', ''),
            'execute' => __('Execute', ''),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="release-instruction-id[]" value="%s" />',
            $item['title']
        );
    }

}
