<?php

namespace ReleaseInstructions\Admin\View;

/**
 * @todo: Update phpdoc.
 */

use ReleaseInstructions\Admin\ListTable;
use ReleaseInstructions\Command\CoreCommand;
use ReleaseInstructions\Tools\Utils;

/**
 * Get all release-instructions.
 * @param $args array
 *
 * @return array
 * @todo: Update phpdoc.
 *
 */
function _get_all_release_instruction($args = array()): array
{
    $cache_key = 'items_' . md5(json_encode($args));
    $items = Utils::cacheGet($cache_key);

    if (false === $items) {
        $defaults = array(
            'number' => 20,
            'offset' => 0,
            'orderby' => 'title',
            'order' => 'ASC',
        );
        $args = (array)wp_parse_args($args, $defaults);

        $items = [];
        if ($data = (new CoreCommand())->getItems()) {
            usort(
                $data,
                static function ($a, $b) use ($args) {
                    return strcmp($a[$args['orderby']], $b[$args['orderby']]);
                }
            );

            if ($args['order'] !== 'ASC') {
                $data = array_reverse($data);
            }

            $data = array_chunk($data, $args['number']);
            $items = $data[$args['offset']] ?? [];
        }

        Utils::cacheSet($cache_key, $items);
    }

    return $items;
}

/**
 * Fetch all release-instructions from database.
 * @todo: Update phpdoc.
 */
function _get_release_instruction_count(): int
{
    return count((new CoreCommand())->getItems());
}

/**
 * Render Admin page.
 * @todo: Update phpdoc.
 */
function render_release_instructions()
{
    $admin = new ListTable();
    $admin->prepare_items();
    // @todo: Implement search by title functionality.
    ?>
    <form method="post">
        <input type="hidden" name="page" value="my_list_test"/>
        <?php
        $admin->search_box('search', 'title'); ?>
    </form>
    <?php
    $admin->display();
}
