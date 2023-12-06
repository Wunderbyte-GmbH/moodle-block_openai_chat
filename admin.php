<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Demofile to see how wunderbyte_table works.
 *
 * @package     blocks_openai_chat
 * @copyright   2023 Bernhard Aichinger-Ganas & Danilo Stoilovski, wunderbyte.at <info@wunderbyte.at>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_openai_chat\tables\admin_table;

require_once(__DIR__ . '/../../config.php');
require_login();

global $DB, $PAGE;

$blockid = optional_param('blockid', 0, PARAM_INT);

$context = context_system::instance();
if (!has_capability('moodle/site:config', $context)) {
    $blockcontext = context_block::instance($blockid);
    // Make sure only an admin can see this.
    if (!has_capability('block/openai_chat:viewprotocoll', $blockcontext)) {
        die;
    }
}

$PAGE->set_context($context);

$url = "/blocks/openai_chat/admin.php";
$url .= !empty($blockid) ? "?blockid=' . $blockid" : "";

$PAGE->set_url($url);

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('block_openai_chat/trainamodelbutton', ['blockid' => $blockid]);

$columns = [
    'id' => get_string('id', 'block_openai_chat'),
    'user' => get_string('user'),
    'userrequest' => get_string('userrequest', 'block_openai_chat'),
    'request' => get_string('request', 'block_openai_chat'),
    'answer' => get_string('answer'),
    'usermodifiedfullname' => get_string('usermodified', 'block_openai_chat'),
    'timecreated' => get_string('timecreated', 'block_openai_chat'),
    'timemodified' => get_string('timemodified', 'block_openai_chat'),
    'model' => get_string('model', 'block_openai_chat'),

];
$table = new admin_table("blockid_$blockid" . "openai_chatprotocol");

$table->define_headers(array_values($columns));
$table->define_columns(array_keys($columns));
$table->define_sortablecolumns(array_keys($columns));

$table->define_filtercolumns([
    'id' => [
        'localizedname' => get_string('id', 'local_wunderbyte_table')
    ],
    'model' => [
                'localizedname' => get_string('model', 'block_openai_chat')
            ]
]);

$sqlinsert = $DB->sql_concat('u.firstname', "' '", 'u.lastname');
$sqlinsert2 = $DB->sql_concat('um.firstname', "' '", 'um.lastname');

// We only ask for the current block id

if (!empty($blockid)) {
    $limittoblock = " WHERE ocp.blockid = $blockid ";
} else {
    $limittoblock = "";
}

$from = " (SELECT ocp.*, $sqlinsert as user, um.firstname umfirstname, um.lastname umlastname, $sqlinsert2 as usermodifiedfullname, um.firstname umfirstname, um.lastname umlastname
            FROM {block_openai_chat_protocol} ocp
        LEFT JOIN {user} u ON ocp.userid=u.id
        LEFT JOIN {user} um ON ocp.usermodified=um.id
        $limittoblock
        ) as s1";

$table->set_filter_sql("*", $from, '1=1', '');
$table->define_cache('block_openai_chat', 'admintable');

$table->filteronloadinactive = true;
$table->pageable(true);

$table->stickyheader = true;
$table->showcountlabel = true;
$table->showdownloadbutton = true;
$table->showrowcountselect = true;

$table->out(10, true);

$PAGE->requires->js_call_amd('block_openai_chat/action_button', 'init');
echo $OUTPUT->footer();


