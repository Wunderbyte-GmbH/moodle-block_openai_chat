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
 * Class providing completions for early models (GPT-3 and older)
 *
 * @package    block_openai_chat
 * @copyright  2023 Bernhard Aichinger-Ganas & Danilo Stoilovski, wunderbyte.at <info@wunderbyte.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

namespace block_openai_chat\completion;

use block_openai_chat\event\answer_received;
use context_system;
use moodle_exception;

defined('MOODLE_INTERNAL') || die;

class python extends \block_openai_chat\completion {

    private string $pathtopython;
    private string $pathtoenvi;
    private string $pathtoscript;

    public function __construct($model, $message, $history, $block_settings) {
        global $CFG;

        $config = get_config('mlbackend_python');
        // $this->pathtopython = empty($CFG->pathtopython) ? "/usr/bin/python3" : $CFG->pathtopython;
        $this->pathtopython =  "/usr/bin/python3";

        $this->pathtoscript = $CFG->dirroot . "/blocks/openai_chat/python/custom.py"; // TODO read at runtime
        $this->pathtoenvi = $CFG->dirroot . "/blocks/openai_chat/python/setenvi.py";
        parent::__construct($model, $message, $history, $block_settings);
    }

    /**
     * Given everything we know after constructing the parent, create a completion by constructing the prompt and making the api call
     * @return JSON: The API response from OpenAI
     */
    public function create_completion() {
        if ($this->sourceoftruth) {
            $this->prompt .= get_string('sourceoftruthreinforcement', 'block_openai_chat');
        }
        $this->prompt .= "\n\n";
        $history_string = $this->format_history();
        $history_string .= $this->username . ": ";
        return $this->exec_script($history_string);
    }

    /**
     * Format the history JSON into a string that we can pass in the prompt
     * @return string: The string representing the chat history to add to the prompt
     */
    private function format_history() {
        $history_string = '';
        foreach ($this->history as $message) {
            $history_string .= $message["user"] . ": " . $message["message"] . "\n";
        }
        return $history_string;
    }

    /**
     * Make the actual API call to OpenAI
     * @return JSON: The response from OpenAI
     */
    private function exec_script($history_string) {
        $arguments = escapeshellarg($this->sourceoftruth . $this->prompt . $history_string . $this->message . "\n" . $this->assistantname . ':');
        $apikey = get_config('block_openai_chat', 'apikey');
        $cmd = $this->pathtopython . ' ' . $this->pathtoscript . ' ' . $arguments . ' 2>&1';
        $cmd2 = $this->pathtopython . ' ' . $this->pathtoenvi . ' ' . $apikey . ' 2>&1';

        $output = null;
        $exitcode = null;

        /**
         * @var JSON
         */
        $response1 = exec($cmd2); // set key as environment variable
        $response = exec($cmd, $output, $exitcode);

        if (!$response) {
            throw new \moodle_exception('Could not execute script');
        }

        // We build the curlbody to make sure we trigger a good event.

        $curlbody = [
            "prompt" => escapeshellarg($this->sourceoftruth . $this->prompt . $history_string . $this->message . "\n" . $this->assistantname . ':'),
        ];

        $event = answer_received::create(array(
            'context' => context_system::instance(),
            'other' => [
                'curlbody' => $curlbody,
                'response' => $response,
                'userrequest' => $this->message,
            ]
        ));
        $event->trigger();

        return $response;
    }
}