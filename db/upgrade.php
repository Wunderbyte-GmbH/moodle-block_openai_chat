<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file keeps track of upgrades to the newsletter module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package    block_openai_chat
 * @copyright 2023 Bernhard Aichinger-Ganas & Danilo Stoilovski, wunderbyte.at <info@wunderbyte.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute openai_chat upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_block_openai_chat_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();
    if ($oldversion < 2023071800) {

        // Define table block_openai_chat_protocol to be created.
        $table = new xmldb_table('block_openai_chat_protocol');

        // Adding fields to table block_openai_chat_protocol.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userrequest', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('request', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('answer', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('status', XMLDB_TYPE_INTEGER, '2', null, null, null, '0');
        $table->add_field('json', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table block_openai_chat_protocol.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for block_openai_chat_protocol.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Openai_chat savepoint reached.
        upgrade_block_savepoint(true, 2023071800, 'openai_chat');
    }

    if ($oldversion < 2023072000) {

        // Define field model to be added to block_openai_chat_protocol.
        $table = new xmldb_table('block_openai_chat_protocol');
        $field = new xmldb_field('model', XMLDB_TYPE_TEXT, null, null, null, null, null, 'timemodified');

        // Conditionally launch add field model.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Openai_chat savepoint reached.
        upgrade_block_savepoint(true, 2023072000, 'openai_chat');
    }

    if ($oldversion < 2023081000) {

        // Define field blockid to be added to block_openai_chat_protocol.
        $table = new xmldb_table('block_openai_chat_protocol');
        $field = new xmldb_field('blockid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'userid');

        // Conditionally launch add field blockid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Openai_chat savepoint reached.
        upgrade_block_savepoint(true, 2023081000, 'openai_chat');
    }



    return true;
}
