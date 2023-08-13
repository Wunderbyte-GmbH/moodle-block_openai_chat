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

namespace block_openai_chat\form;

defined('MOODLE_INTERNAL') || die();

global $CFG;

use block_openai_chat\completion\python;
use coding_exception;
use context;
use context_block;
use core_form\dynamic_form;
use dml_exception;
use invalid_dataroot_permissions;
use moodle_url;
use stdClass;

/**
 * Modal form (dynamic form) for cashier manual rebooking.
 *
 * @copyright   Wunderbyte GmbH <info@wunderbyte.at>
 * @package     block_openai_chat
 * @author      2023 Bernhard Aichinger-Ganas & Danilo Stoilovski, wunderbyte.at <info@wunderbyte.at>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class modal_add_files extends dynamic_form {

    /**
     * {@inheritdoc}
     * @see moodleform::definition()
     */
    public function definition() {

        $mform = $this->_form;

        $blockid = $this->_ajaxformdata['blockid'];

        $mform->addElement('hidden', 'blockid', $blockid);

        $options = array('subdirs' => 1, 'maxfiles' => -1, 'accepted_types'=>'*');
        $mform->addElement('filemanager', 'attachments', '', null, $options);
    }

    /**
     * Check access for dynamic submission.
     *
     * @return void
     */
    protected function check_access_for_dynamic_submission(): void {
        require_capability('block/openai_chat:viewprotocoll', $this->get_context_for_dynamic_submission());
    }

    /**
     * Process the form submission, used if form was submitted via AJAX
     *
     * This method can return scalar values or arrays that can be json-encoded, they will be passed to the caller JS.
     *
     * Submission data can be accessed as: $this->get_data()
     *
     * @return mixed
     */
    public function process_dynamic_submission() {
        global $USER,$CFG;

        $blockid = $this->_ajaxformdata['blockid'];

        if ($data = $this->get_data()) {
            // ... store or update $entry.
            $context = $this->get_context_for_dynamic_submission();
            // Now save the files in correct part of the File API.
            file_save_draft_area_files(
                // The $data->attachments property contains the itemid of the draft file area.
                $data->attachments,

                // The combination of contextid / component / filearea / itemid
                // form the virtual bucket that file are stored in.
                $context->id,
                'block_openai_chat',
                'attachments',
                1,
                [
                    'subdirs' => 1,
                    'maxbytes' => 200000,
                    'maxfiles' => -1,
                ]
            );
        }

        $textfilepaths = $this->return_array_of_filepaths('text/plain', $blockid);
        $pdffilepaths = $this->return_array_of_filepaths('application/pdf', $blockid);
        python::save_embeddings($blockid, $textfilepaths, $pdffilepaths);

        return $data;
    }


    /**
     * Load in existing data as form defaults
     *
     * Can be overridden to retrieve existing values from db by entity id and also
     * to preprocess editor and filemanager elements
     *
     * Example:
     *     $this->set_data(get_entity($this->_ajaxformdata['cmid']));
     */
    public function set_data_for_dynamic_submission(): void {
        global $DB;

        $data = new stdClass();
        $context = $this->get_context_for_dynamic_submission();

        // Get an unused draft itemid which will be used for this form.
        $draftitemid = file_get_submitted_draft_itemid('attachments');

        // Copy the existing files which were previously uploaded
        // into the draft area used by this form.
        file_prepare_draft_area(
            // The $draftitemid is the target location.
            $draftitemid,

            // The combination of contextid / component / filearea / itemid
            // form the virtual bucket that files are currently stored in
            // and will be copied from.
            $context->id,
            'block_openai_chat',
            'attachments',
            1,
            [
                'subdirs' => 1,
                'maxbytes' => 20000,
                'maxfiles' => -1,
            ]
        );

        // Set the itemid of draft area that the files have been moved to.
        $data->attachments = $draftitemid;
        $this->set_data($data);
    }

    /**
     * Returns form context
     *
     * If context depends on the form data, it is available in $this->_ajaxformdata or
     * by calling $this->optional_param()
     *
     * @return context
     */
    protected function get_context_for_dynamic_submission(): context {

        $blockid = $this->_ajaxformdata['blockid'];

        return context_block::instance($blockid);
    }

    /**
     * Returns url to set in $PAGE->set_url() when form is being rendered or submitted via AJAX
     *
     * This is used in the form elements sensitive to the page url, such as Atto autosave in 'editor'
     *
     * If the form has arguments (such as 'id' of the element being edited), the URL should
     * also have respective argument.
     *
     * @return moodle_url
     */
    protected function get_page_url_for_dynamic_submission(): moodle_url {

        // We don't need it, as we only use it in modal.
        return new moodle_url('/');
    }

    /**
     * Get data from form function
     *
     * @return stdClass
     */
    public function get_data() {
        $data = parent::get_data();
        return $data;
    }

    /**
     * Return the text fromthe saved files.
     * @return string|string[]|null
     * @throws dml_exception
     * @throws coding_exception
     */
    public function get_text_from_saved_files() {
        $context = $this->get_context_for_dynamic_submission();

        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'block_openai_chat', 'attachments');
        $contents = '';

        foreach ($files as $file) {
            $contents .= " " . $file->get_content();
        }
        $contents = preg_replace( "/<br>|\n/", " ", $contents );
        $contents = str_replace("  ", " ", $contents);
        return $contents;
    }


    /**
     * @param string $filetype
     * @return array
     * @throws dml_exception
     * @throws coding_exception
     * @throws invalid_dataroot_permissions
     */
    public function return_array_of_filepaths(string $filetype) {
        $context = $this->get_context_for_dynamic_submission();

        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'block_openai_chat', 'attachments');

        $returnarray = [];
        foreach ($files as $file) {
            $mimetype = $file->get_mimetype();
            if ($mimetype == $filetype) {
                $returnarray[] = $file->copy_content_to_temp();
            }
        }

        return $returnarray;
    }

}
