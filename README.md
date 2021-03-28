# WordPress Plugin Release Instructions

**Motivation:** automated custom code deployment for WordPress. Example, migrate custom taxonomy, update records in a database, etc.

## Contents

1. Release instructions are applied via WP-CLI `wp ri run` and the executed RI are being stored in the wp option *ri_executed*, see [#WP-CLI commands]() for an example.

2. Not to check all the plugins at once there's a header that lets system know that the plugin contains the release instructions: `RI`, see [#Installation]() for an example.

3. Release instructions should be stored per issue in the files located in `WP_PLUGIN_PATH/ri/ISSUE.ri.inc`, e.g. path `WP_PLUGIN_DIR/release-instructions/ri/test.ri.inc`.
   There is NO restriction on the file name but for readability’s sake you may want to stick to issue name.

4. File with the release instructions should contain the functions named according to the convention, if the file is ISSUE.ri.inc then we try to execute functions:
   - `*plugin_machinename*_ri_*number*()`; for the example above they should be `release_instructions_ri_001()` or `release_instructions_ri_1()`, - they will be executed in the numeric order so there's a way to add additions.
   - `*issue*_*plugin_machinename*_ri_*number*()`; something like `test_release_instructions_ri_001()` `test_release_instructions_ri_1` |  or `test001_release_instructions_ri_001()` | `test001_release_instructions_ri_1()` and so on.

## Features

* [WP-CLI](https://wp-cli.org) support.

### WP-CLI commands
`wp ri` - see list of subcommands.

```bash#
usage: wp ri function <function>
   or: wp ri preview [<all>]
   or: wp ri run 
   or: wp ri status <function> [<flag>]

See 'wp help ri <command>' for more information on a specific command.
```

`wp help ri` - see docs.

```bash#
NAME

  wp ri

DESCRIPTION

  Implements Release Instructions commands for the WP-CLI framework.

SYNOPSIS

  wp ri <command>

SUBCOMMANDS

  function      Runs a single RI.
  preview       Previews scheduled release instructions.
  run           Executes scheduled RIs.
  status        Sets status for the release instruction function.
```

###### Examples, after plugin installation

* `wp ri preview` - see a list of RIs ready for a run.
```bash#
Release instructions to be executed (in order):

release_instructions_ri_001()

End of list.
```
* `wp ri run` - run automated custom code deployment / execute RIs.
```bash#
##############################

Running release_instructions_ri_001()

Hello World!

[status]: Release instruction release_instructions_ri_001() was executed.
##############################


Success: Release instructions were executed.
```
* `wp ri preview all` - see all release instructions with their statuses.
```bash#
List of all release instructions:

[x]: release_instructions_ri_001()

Warning: Nothing to execute.

End of list.
```
* `wp ri status release_instructions_ri_001` - see status of a certain RI.
```bash#
Success: Status for release_instructions_ri_001() is "1".
```
* `wp ri status release_instructions_ri_001 0` - update status of a certain RI, _example:_ unset RI after execution.
```bash#
Success: Status for release_instructions_ri_001() was set to "0".
```
* `wp ri function release_instructions_ri_001` - force run single RI. If RI was executed successfully earlier instead of `[status]` message you will see a `Warning`.
```bash#
##############################

Running release_instructions_ri_001()

Hello World!

[status]: Release instruction release_instructions_ri_001() was executed.
##############################


Success: Release instruction execution is finished.
```
## Installation

* Activate the plugin.
* _Update/create_ yours custom plugin.
* Define that plugin supports Release Instructions, _example (`release-instructions.php`):_
```php
/**
 * ...
 * 
 * @wordpress-plugin
 * RI:  true
 * ...
 */
```
* Create `ri` subdirectory.
* Add a file(-s) with a following pattern `*any_text_here*.ri.inc`.
* Add functions with a following pattern `*plugin_name*_ri_*number*()`, _example (`ri/test.ri.inc`): `release_instructions_ri_001()`._
Where `*number*` - integer number for an ordered execution.

## WordPress.org Preparation

Pre-installed [WP-CLI](https://wp-cli.org/#installing).

## License

The WordPress Plugin Release Instructions is licensed under the GPL v3.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the root of the plugin’s directory. The file is named `LICENSE` and also `LICENSE.txt`.

## Important Notes

### What About Other Features?

There is a possibility of adding admin panel for cases when developer somehow misses access to CLI.
Maybe a nice thing to have, but not required for now.

# Credits

* Inspired by [Hook Update API](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Extension%21module.api.php/function/hook_update_N/) and [Release Instructions](https://www.drupal.org/project/ri) module for [Drupal 7/8](https://www.drupal.org/).
* The Boilerplate is based on the [Plugin API](http://codex.wordpress.org/Plugin_API), [Coding Standards](http://codex.wordpress.org/WordPress_Coding_Standards), and [Documentation Standards](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/).
