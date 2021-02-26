<?php

namespace ReleaseInstructions\Command;

/**
 * The file that defines the core command interface.
 *
 * @link       https://github.com/Zinkutal/release-instructions
 * @since      1.0.0
 *
 * @package    ReleaseInstructions
 * @subpackage ReleaseInstructions/Command
 */

/**
 * Core interface.
 *
 * Used to define RI plugin actions.
 *
 * @since      1.0.0
 * @package    ReleaseInstructions
 * @subpackage ReleaseInstructions/Command
 * @author     Alexander Kucherov <avdkucherov@gmail.com>
 */
interface CommandInterface
{
    /**
     * Runs a single release instruction.
     *
     * @param string $function Function name.
     * @return self
     *
     * @since 1.0.0
     */
    public function execute(string $function = ''): CoreCommand;

    /**
     * Executes the release instructions.
     *
     * @return self
     *
     * @since 1.0.0
     */
    public function executeAll(): CoreCommand;

    /**
     * Shows list of release instructions.
     *
     * @param bool $all Includes executed in a list.
     * @return self
     *
     * @since 1.0.0
     */
    public function preview(bool $all = false): CoreCommand;

    /**
     * Returns release instruction status(-es).
     *
     * @param string $function Function name.
     * @return bool|mixed Status(-es).
     *
     * @since 1.0.0
     */
    public function getStatus(string $function = '');

    /**
     * Sets release instruction status.
     *
     * @param string $function Function name.
     * @param bool $flag Flag value.
     * @return bool Set/unset status.
     *
     * @since 1.0.0
     */
    public function setStatus(string $function = '', bool $flag = true): bool;
}
