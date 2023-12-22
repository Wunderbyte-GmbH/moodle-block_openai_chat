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
 * Language strings
 *
 * @package    block_openai_chat
 * @copyright  2023 Bernhard Aichinger-Ganas & Danilo Stoilovski, wunderbyte.at <info@wunderbyte.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 $string['pluginname'] = 'OpenAI Chat-Block';
 $string['openai_chat'] = 'OpenAI Chat';
 $string['openai_chat:addinstance'] = 'Einen neuen OpenAI Chat-Block hinzufügen';
 $string['openai_chat:myaddinstance'] = 'Einen neuen OpenAI Chat-Block zur "Meine Moodle"-Seite hinzufügen';
 $string['privacy:metadata'] = 'Der OpenAI Chat-Block speichert keine persönlichen Benutzerdaten und sendet standardmäßig keine persönlichen Daten an OpenAI. Chat-Nachrichten, die von Benutzern eingereicht werden, werden jedoch vollständig an OpenAI gesendet und unterliegen dann der Datenschutzrichtlinie von OpenAI (https://openai.com/api/policies/privacy/), die Nachrichten möglicherweise zur Verbesserung der API speichert.';

 $string['blocktitle'] = 'Blocktitel';

 $string['restrictusage'] = 'Chat-Nutzung auf eingeloggte Benutzer beschränken';
 $string['restrictusagedesc'] = 'Wenn dieses Feld aktiviert ist, können nur eingeloggte Benutzer die Chat-Box verwenden.';
 $string['apikey'] = 'OpenAI API-Schlüssel';
 $string['apikeydesc'] = 'Der API-Schlüssel für Ihr OpenAI-Konto';
 $string['prompt'] = 'Abschlussanweisung';
 $string['promptdesc'] = 'Die Anweisung, die dem KI-Modell vor dem Gesprächstranskript gegeben wird';
 $string['assistantname'] = 'Assistentenname';
 $string['assistantnamedesc'] = 'Der Name, den die KI intern für sich selbst verwenden wird';
 $string['username'] = 'Benutzername';
 $string['usernamedesc'] = 'Der Name, den die KI intern für den Benutzer verwenden wird';
 $string['sourceoftruth'] = 'Informationsquelle';
 $string['sourceoftruthdesc'] = 'Obwohl die KI standardmäßig sehr leistungsfähig ist, besteht die Wahrscheinlichkeit, dass sie bei unbekannten Fragen eher falsche Informationen selbstbewusst gibt, anstatt die Antwort zu verweigern. Hier können Sie häufig gestellte Fragen und ihre Antworten hinzufügen, auf die die KI zugreifen kann. Bitte geben Sie Fragen und Antworten im folgenden Format an:<pre>Q: Frage 1<br />A: Antwort 1<br /><br />Q: Frage 2<br />A: Antwort 2</pre>';
 $string['welcometext'] = 'Die erste Nachricht des Chatbots';
 $string['welcometext_help'] = 'Sie können den String {Vorname} verwenden, er wird durch den Vornamen des aktiven Benutzers ersetzt.';
 $string['showlabels'] = 'Labels anzeigen';
 $string['advanced'] = 'Erweitert';
 $string['advanceddesc'] = 'Erweiterte Argumente, die an OpenAI gesendet werden. Berühren Sie das nicht, es sei denn, Sie wissen, was Sie tun!';
 $string['allowinstancesettings'] = 'Einstellungen auf Instanzebene';
 $string['allowinstancesettingsdesc'] = 'Diese Einstellung ermöglicht es Lehrern oder anderen Personen mit der Fähigkeit, in einem Kontext einen Block hinzuzufügen, Einstellungen auf Blockebene anzupassen. Durch die Aktivierung können zusätzliche Kosten entstehen, da Nicht-Administratoren teurere Modelle oder andere Einstellungen wählen können.';
 $string['model'] = 'Modell';
 $string['modeldesc'] = 'Das Modell, das die Vervollständigung generieren wird. Einige Modelle eignen sich für natürliche Sprachaufgaben, andere spezialisieren sich auf Code.';
 $string['temperature'] = 'Temperatur';
 $string['temperaturedesc'] = 'Steuerung der Zufälligkeit: Eine Verringerung führt zu weniger zufälligen Vervollständigungen. Wenn die Temperatur gegen null geht, wird das Modell deterministisch und wiederholt sich.';
 $string['maxlength'] = 'Maximale Länge';
 $string['maxlengthdesc'] = 'Die maximale Anzahl an Tokens, die generiert werden sollen. Anfragen können bis zu 2.048 oder 4.000 Tokens verwenden, die zwischen Anfang und Vervollständigung aufgeteilt werden. Das genaue Limit variiert je nach Modell. (Ein Token entspricht ungefähr 4 Zeichen normalen englischen Texts)';
 $string['topp'] = 'Top P';
 $string['toppdesc'] = 'Steuerung der Vielfalt über Nukleus-Sampling: 0,5 bedeutet, dass die Hälfte aller gewichteten Wahrscheinlichkeitsoptionen berücksichtigt werden.';
 $string['frequency'] = 'Frequenzstrafe';
 $string['frequencydesc'] = 'Wie stark neue Tokens basierend auf ihrer vorhandenen Häufigkeit im Text bisher bestraft werden sollen. Verringert die Wahrscheinlichkeit des Modells, die gleiche Zeile wortwörtlich zu wiederholen.';
 $string['presence'] = 'Präsenzstrafe';
 $string['presencedesc'] = 'Wie stark neue Tokens basierend darauf bestraft werden sollen, ob sie bisher im Text erscheinen. Erhöht die Wahrscheinlichkeit des Modells, über neue Themen zu sprechen.';

 $string['config_sourceoftruth'] = 'Informationsquelle';
 $string['config_sourceoftruth_help'] = "Sie können hier Informationen hinzufügen, auf die die KI bei der Beantwortung von Fragen zurückgreifen wird. Die Informationen sollten im Frage-und-Antwort-Format genau wie folgt sein:\n\nQ: Wann ist Abschnitt 3 fällig?<br />A: Donnerstag, 16. März.\n\nQ: Wann sind die Sprechstunden?<br />A: Sie finden Professor Shown in ihrem Büro zwischen 14:00 und 16:00 Uhr an Dienstagen und Donnerstagen.";
 $string['config_prompt'] = "Abschlussanweisung";
 $string['config_prompt_help'] = "Dies ist die Anweisung, die der KI vor dem Gesprächstranskript gegeben wird. Sie können die Persönlichkeit der KI beeinflussen, indem Sie diese Beschreibung ändern. Standardmäßig lautet die Anweisung\n\n\"Unten ist ein Gespräch zwischen einem Benutzer und einem Support-Assistenten für eine Moodle-Seite, auf der Benutzer online lernen.\".\n\nWenn leer, wird die systemweite Anweisung verwendet.";
 $string['config_username'] = "Benutzername";
 $string['config_username_help'] = "Dies ist der Name, den die KI für den Benutzer verwenden wird. Wenn leer, wird der systemweite Benutzername verwendet.";
 $string['config_assistantname'] = "Assistentenname";
 $string['config_assistantname_help'] = "Dies ist der Name, den die KI für den Assistenten verwenden wird. Wenn leer, wird der systemweite Assistentenname verwendet.";
 $string['config_apikey'] = "API-Schlüssel";
 $string['config_apikey_help'] = "Sie können hier einen API-Schlüssel für diesen Block angeben. Wenn leer, wird der systemweite Schlüssel verwendet.";
 $string['config_model'] = "Modell";
 $string['config_model_help'] = "Das Modell, das die Vervollständigung generieren wird.";
 $string['config_temperature'] = "Temperatur";
 $string['config_temperature_help'] = "Steuerung der Zufälligkeit: Eine Verringerung führt zu weniger zufälligen Vervollständigungen. Wenn die Temperatur gegen null geht, wird das Modell deterministisch und wiederholt sich.";
 $string['config_maxlength'] = "Maximale Länge";
 $string['config_maxlength_help'] = "Die maximale Anzahl an Tokens, die generiert werden sollen.";
 $string['config_topp'] = "Top P";
 $string['config_topp_help'] = "Steuerung der Vielfalt über Nukleus-Sampling: 0,5 bedeutet, dass die Hälfte aller gewichteten Wahrscheinlichkeitsoptionen berücksichtigt werden.";
 $string['config_frequency'] = "Frequenzstrafe";
 $string['config_frequency_help'] = "Wie stark neue Tokens basierend auf ihrer vorhandenen Häufigkeit im Text bisher bestraft werden sollen.";
 $string['config_presence'] = "Präsenzstrafe";
 $string['config_presence_help'] = "Wie stark neue Tokens basierend darauf bestraft werden sollen, ob sie bisher im Text erscheinen.";

 $string['defaultprompt'] = "Unten ist ein Gespräch zwischen einem Benutzer und einem Support-Assistenten für eine Moodle-Seite, auf der Benutzer online lernen:";
 $string['id'] = 'ID';
 $string['userrequest'] = 'Benutzeranfrage';
 $string['request'] = 'Anfrage, die die KI erhalten hat';
 $string['answer'] = 'Antwort';
 $string['usermodified'] = 'Zuletzt vom Benutzer geändert';
 $string['timecreated'] = 'Erstellungszeitpunkt';
 $string['timemodified'] = 'Letzte Änderungszeit';
 $string['defaultassistantname'] = 'Assistent';
 $string['defaultusername'] = 'Benutzer';
 $string['askaquestion'] = 'Stellen Sie eine Frage...';
 $string['apikeymissing'] = 'Bitte fügen Sie Ihren OpenAI-API-Schlüssel zu den globalen Blockeinstellungen hinzu.';
 $string['erroroccurred'] = 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es später erneut.';
 $string['sourceoftruthpreamble'] = "Unten finden Sie eine Liste von Fragen und ihren Antworten. Diese Informationen sollten als Referenz für alle Anfragen verwendet werden:\n\n";
 $string['sourceoftruthreinforcement'] = 'Der Assistent wurde trainiert, um Antworten zu geben, indem er versucht, die Informationen aus der obigen Referenz zu nutzen.';
 $string['thisconversationisrecorded'] = 'Diese Unterhaltung wird aufgezeichnet und ist vom Administrator einsehbar.';
 $string['donotsharepersonaldata'] = 'Nachricht eingeben (keine persönlichen Daten)';
 $string['thisaimakesmistakes'] = 'Diese KI macht manchmal Fehler. <a href="https://study-now.eu/pluginfile.php/159925/mod_folder/content/0/ELG%20Infobot%20-%20Factsheet.pdf">Das ist der Grund</a>';
 $string['descriptionModal'] = 'Dies ist ein Modal für die Erstellung einer Einbettung für Ihren Chatbot. Sie können entweder Text eingeben oder eine Datei hochladen';

 $string['addknowledge'] = 'Wissen hinzufügen';

