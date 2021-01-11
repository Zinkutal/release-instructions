<?php

namespace Release_Instructions;

/**
 * The file that defines the core command interface.
 *
 * @link       https://github.com/Zinkutal/release-instructions
 * @since      1.0.0
 *
 * @package    Release_Instructions
 * @subpackage Release_Instructions/includes
 */

/**
 * Core interface.
 *
 * Used to define RI plugin actions.
 *
 * @since      1.0.0
 * @package    Release_Instructions
 * @subpackage Release_Instructions/includes
 * @author     Alexander Kucherov <avdkucherov@gmail.com>
 */
interface Core
{
    /**
     * Runs a single release instruction.
     *
     * @param string $function Function name.
     * @return $this
     *
     * @since 1.0.0
     */
    public function execute(string $function = ''): Core_Command;

    /**
     * Executes the release instructions.
     *
     * @return $this
     *
     * @since 1.0.0
     */
    public function execute_all(): Core_Command;

    /**
     * Shows list of release instructions.
     *
     * @param bool $all Includes executed in a list.
     * @return $this
     *
     * @since 1.0.0
     */
    public function preview(bool $all = false): Core_Command;

    /**
     * Returns release instruction status(-es).
     *
     * @param string $function Function name.
     * @return bool|mixed Status(-es).
     *
     * @since 1.0.0
     */
    public function status_get(string $function = '');

    /**
     * Sets release instruction status.
     *
     * @param string $function Function name.
     * @param bool $flag Flag value.
     * @return bool Set/unset status.
     *
     * @since 1.0.0
     */
    public function status_set(string $function = '', bool $flag = true);
}
