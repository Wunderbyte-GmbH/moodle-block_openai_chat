
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
 * @package    blocks_openai_chat
 * @copyright  2023 Bernhard Aichinger-Ganas & Danilo Stoilovski, wunderbyte.at <info@wunderbyte.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalForm from 'core_form/modalform';
import Ajax from 'core/ajax';
import Url from 'core/url';
import {showNotification} from 'local_shopping_cart/notifications';
import ModalForm from 'core_form/modalform';
import {reinit} from 'local_shopping_cart/cart';
import {get_string as getString} from 'core/str';

export const init = (userid = 0) => { 
  console.log('init');
  const manualrebookbtn = document.querySelector('A');

  if (manualrebookbtn) {
    manualrebookbtn.addEventListener('click', (e) => input_modal());
  }
}

export function input_modal() {
  const modalForm = new ModalForm({
    formClass: "block_openai_chat\\classes\\form\\modal_input",
  });

  modalForm.addEventListener(modalForm.events.FORM_SUBMITTED, (e) => {
    const response = e.detail;
    console.log('rebookOrderidModal response: ', response);
  });

  modalForm.show();
}