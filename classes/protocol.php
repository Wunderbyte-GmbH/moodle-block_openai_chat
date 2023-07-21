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

defined('MOODLE_INTERNAL') || die();

class block_openai_chat_protocol {
    /**
     * Saves entry to protocol table
     * @param stdClass $data 
     * @return bool Returns success
     */
    public static function save_entry(stdClass $data): bool {
        global $DB, $USER;

        $data->usermodified = $USER->id;
        $data->timecreated = time();
        $data->timemodified = $data->timemodified ?? time();
        $data->model = get_config('block_openai_chat', 'model');
        
        if(property_exists($data, 'id')) {
            $result = $DB->update_record('block_openai_chat_protocol', $data); 
        } else {
            $result = $DB->insert_record('block_openai_chat_protocol', $data);
        }
        cache_helper::purge_by_event('changesinadmintable');
        return $result;
    }

    /**
     * Returns an array of entrys
     *
     * @param integer $id
     * @return array 
     */
    public function return_entrys(int $id) {
        global $DB; 

        return $DB->get_records('block_openai_chat_protocol', ['id' => $id]);
    }
}