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
 * Event observers used in newsletter.
 *
 * @package block_openai_chat
 * @copyright 2023 Bernhard Aichinger-Ganas & Danilo Stoilovski, wunderbyte.at <info@wunderbyte.at>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once 'protocol.php';

use block_openai_chat\event\answer_received;

defined('MOODLE_INTERNAL') || die();


    class block_openai_chat_observer {

        public static function answer_received(answer_received $event) {
            global $USER;

            $eventdata = $event->get_data();
            $data = (object)[
                'userid' => $USER->id,
                'userrequest' =>  $eventdata["other"]["userrequest"],
                'request' =>  $eventdata["other"]["curlbody"]["prompt"] ?? $eventdata["other"]["curlbody"]["messages"],
                'answer' =>  $eventdata["other"]["response"],
                'blockid' =>  $eventdata["other"]["blockid"],
            ];

            block_openai_chat_protocol::save_entry($data);
        }
    }
?>