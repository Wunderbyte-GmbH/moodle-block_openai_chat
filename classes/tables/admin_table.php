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
 * The Wunderbyte table class is an extension of the tablelib table_sql class.
 *
 * @package block_openai_chat
 * @copyright 2023 Bernhard Aichinger-Ganas & Danilo Stoilovski, wunderbyte.at <info@wunderbyte.at>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// phpcs:ignoreFile

namespace block_openai_chat\tables;

defined('MOODLE_INTERNAL') || die();

use local_wunderbyte_table\wunderbyte_table;
use stdClass;

/**
 * Wunderbyte table demo class.
 */
class admin_table extends wunderbyte_table {

    /**
     * Decodes the Unix Timestamp
     *
     * @param stdClass $values
     * @return string
     */
    public function col_timemodified($values) {
        return userdate($values->timemodified);
    }

    /**
     * Decodes the Unix Timestamp
     *
     * @param stdClass $values
     * @return string
     */
    public function col_timecreated($values) {
        return userdate($values->timemodified);
    }

    public function col_answer($values) {

        $jsonobject = json_decode($values->answer);
        $text = $jsonobject->choices[0]->text ?? 'No valid response';

        return '
        <a type="button" data-toggle="modal" data-target="#staticBackdrop">
        ' . $text . '
        </a>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            ' . $values->answer . '
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>';



    }

}
