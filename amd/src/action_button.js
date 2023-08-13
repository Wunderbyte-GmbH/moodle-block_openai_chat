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

/*
 * @package    block_openai_chat
 * @copyright  2023 Bernhard Aichinger-Ganas & Danilo Stoilovski, wunderbyte.at <info@wunderbyte.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalForm from 'core_form/modalform';

const SELECTORS = {
  ADDFILESMODALBUTTON: "#page-blocks-openai_chat-admin a.btn-openmodaladfinetuning"
};
/**
 * Init function.
 */
export function init() {
  // eslint-disable-next-line no-console
  console.log("init");

  const modalbtn = document.querySelector(SELECTORS.ADDFILESMODALBUTTON);

  if (modalbtn) {
    modalbtn.addEventListener('click', e => {
      addFilesModal(e);
    });
  }
}

/**
 *
 * @param {event} e
 */
function addFilesModal(e) {

  const blockid = e.target.dataset.blockid;

  // eslint-disable-next-line no-console
  console.log(blockid);

  const modalForm = new ModalForm({

    // Name of the class where form is defined (must extend \core_form\dynamic_form):
    formClass: "block_openai_chat\\form\\modal_add_files",
    // Add as many arguments as you need, they will be passed to the form:
    args: {
      "blockid": blockid
    },
    // Pass any configuration settings to the modal dialogue, for example, the title:
    modalConfig: {title: 'myform'},
    // DOM element that should get the focus after the modal dialogue is closed:
    returnFocus: e.currentTarget
  });
  // Listen to events if you want to execute something on form submit.
  // Event detail will contain everything the process() function returned:
  modalForm.addEventListener(modalForm.events.FORM_SUBMITTED, (e) => {
    window.console.log(e.detail);

    // Reload window after cancelling.
    window.location.reload();
  });

  // Show the form.
  modalForm.show();
}