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

namespace block_openai_chat\completion;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once("$CFG->libdir/formslib.php");

/**
 * Modal form (dynamic form) for cashier manual rebooking.
 *
 * @copyright   Wunderbyte GmbH <info@wunderbyte.at>
 * @package     local_shopping_cart
 * @author      2023 Bernhard Aichinger-Ganas & Danilo Stoilovski, wunderbyte.at <info@wunderbyte.at>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rest_interface {
    private static function get_models(): array
    {
        $curl = curl_init();
        $apikey = get_config('block_openai_chat', 'apikey');
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.openai.com/v1/models',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $apikey
            ),
        ));
    
        $response = curl_exec($curl);
        curl_close($curl);
        
        $models = json_decode($response, true) ?? [];

        // Add our custom python model
        $custom_models = [
            'id' => 'custom',
        ];

        if (!isset($models['data'])) {
            $models['data'] = $custom_models;
        } else {
            $models['data'][] = $custom_models;
        }

        return $models;
    }

    public static function get_models_names() {
        $models = self::get_models();
        
        if (!isset($models['data'])) {
            return array();
        }

        $modelIdsAndNames = array();
        foreach ($models['data'] as $model) {
            $modelIdsAndNames[$model['id']] = $model['id'];
        }
    
        return $modelIdsAndNames;
    }
    
    public static function get_models_file() {
    $models = self::get_models();

    if ($models === null || !isset($models['data'])) {
        return array();
    }

    $modelIdsAndNames = array();
    foreach ($models['data'] as $model) {
        $modelName = $model['id'];
        
        if ($modelName === 'custom') {
            $formattedName = str_pad($modelName, 4, '0', STR_PAD_LEFT);
            $modelIdsAndNames[$formattedName] = 'python';
        } else if (stripos($modelName, 'turbo') !== false) {
            $formattedName = str_pad($modelName, 4, '0', STR_PAD_LEFT);
            $modelIdsAndNames[$formattedName] = 'chat';
        } else {
            $formattedName = str_pad($modelName, 3, '0', STR_PAD_LEFT);
            $modelIdsAndNames[$formattedName] = 'basic';
        }
    }

    return $modelIdsAndNames;
}

    
}