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
 * gwpayments configuration form
 *
 * File         mod_form.php
 * Encoding     UTF-8
 *
 * @package     mod_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/page/locallib.php');
require_once($CFG->libdir.'/filelib.php');

/**
 * gwpayments configuration form
 *
 * @package     mod_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_gwpayments_mod_form extends moodleform_mod {

    /**
     * Form definition.
     */
    protected function definition() {
        global $CFG, $DB, $COURSE;
        $mform = $this->_form;

        $config = get_config('gwpayments');

//echo serialize($config);
//die;

        // -------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('name'), array('size' => '48'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        $this->standard_intro_elements();

        $mform->addElement('advcheckbox', 'printintro', get_string('printintro', 'page'), 0);
        $mform->setType('printintro', PARAM_INT);

        // -------------------------------------------------------
        $mform->addElement('header', 'content', get_string('contentheader', 'mod_gwpayments'));

//        $mform->addElement('textarea', 'page', get_string('content'), ['rows' => "10", 'cols' => "10"]);

        $mform->addElement('float', 'cost', get_string('cost', 'mod_gwpayments'), ['size' => 7]);
//        $mform->setType('cost', PARAM_FLOAT);
        $mform->addRule('cost', null, 'required', null, 'client');
//        $mform->addRule('cost', null, 'numeric', null, 'client');
        $mform->setDefault('cost', $config->cost);
        $mform->addHelpButton('cost', 'cost', 'mod_gwpayments');

        $mform->addElement('advcheckbox', 'showcost',
                get_string('showcost', 'mod_gwpayments'),
                get_string('showcost', 'mod_gwpayments'));
        $mform->setType('showcost', PARAM_INT);

        // This is used for expiry determination.
        $mform->addElement('duration', 'costduration', get_string('costduration', 'mod_gwpayments'), array('optional' => true));
//        $mform->setDefault('costduration', 86400);
        $mform->addHelpButton('costduration', 'costduration', 'mod_gwpayments');

        $mform->addElement('advcheckbox', 'showduration',
                get_string('showduration', 'mod_gwpayments'),
                get_string('showduration', 'mod_gwpayments'));
        $mform->setType('showduration', PARAM_INT);
/*
        $mform->addElement('text', 'vat', get_string('vat', 'mod_gwpayments'), array('size' => 4));
        $mform->setType('vat', PARAM_RAW);
        $mform->setDefault('vat', $config->vat);
        $mform->addHelpButton('vat', 'vat', 'mod_gwpayments');
*/
        $supportedcurrencies = \mod_gwpayments\local\helper::get_possible_currencies();
        $mform->addElement('select', 'currency', get_string('currency', 'mod_gwpayments'), $supportedcurrencies);
        $mform->setDefault('currency', $config->currency);
        $mform->addRule('currency', null, 'required', null, 'client');
        $mform->addHelpButton('currency', 'currency', 'mod_gwpayments');

        $accounts = \core_payment\helper::get_payment_accounts_menu($this->context);
        if (count($accounts) == 0) {
            // Add warning!
            $mform->addElement('static', 'accountid_text', get_string('paymentaccount', 'payment'),
                html_writer::span(get_string('noaccountsavilable', 'payment'), 'alert alert-danger'));
        }
        $accounts = ((count($accounts) > 1) ? ['' => ''] : []) + $accounts;
        $mform->addElement('select', 'accountid', get_string('paymentaccount', 'payment'), $accounts);
        $mform->setType('accountid', PARAM_INT);
        $mform->addHelpButton('accountid', 'paymentaccount', 'mod_gwpayments');
        $mform->disabledIf('accountid', 'hidepaymentaccount', "neq", 0);
        $mform->addRule('accountid', null, 'required', null, 'client');

        $mform->addElement('advcheckbox', 'hidepaymentaccount',
                get_string('hidepaymentaccount', 'mod_gwpayments'),
                get_string('hidepaymentaccount', 'mod_gwpayments'));
        $mform->setType('hidepaymentaccount', PARAM_INT);
        $mform->addHelpButton('hidepaymentaccount', 'hidepaymentaccount', 'mod_gwpayments');

        $mform->addElement('text', 'addpaymentlink',
                get_string('addpaymentlink', 'mod_gwpayments'), ['size' => 48]);
        $mform->setType('addpaymentlink', PARAM_TEXT);
        $mform->addHelpButton('addpaymentlink', 'addpaymentlink', 'mod_gwpayments');

	$options = [1 => get_string('yes'), 0 => get_string('no'), 2 => get_string('hide')];
        $mform->addElement('select', 'studentdisplayonpayments',
                get_string('studentdisplayonpayments', 'mod_gwpayments'), $options);
        $mform->setType('studentdisplayonpayments', PARAM_INT);
        $mform->setDefault('studentdisplayonpayments', $config->studentdisplayonpayments);
        $mform->addHelpButton('studentdisplayonpayments', 'studentdisplayonpayments', 'mod_gwpayments');

        $mform->addElement('advcheckbox', 'disablepaymentonmisconfig',
                get_string('disablepaymentonmisconfig', 'mod_gwpayments'),
                get_string('disablepaymentonmisconfig', 'mod_gwpayments'));
        $mform->setType('disablepaymentonmisconfig', PARAM_INT);
        $mform->setDefault('disablepaymentonmisconfig', $config->disablepaymentonmisconfig);
        $mform->addHelpButton('disablepaymentonmisconfig', 'disablepaymentonmisconfig', 'mod_gwpayments');

        $mform->addElement('advcheckbox', 'showamount',
                get_string('showamount', 'mod_gwpayments'),
                get_string('showamount', 'mod_gwpayments'));
        $mform->setType('showamount', PARAM_INT);

        $mform->addElement('advcheckbox', 'showallpayments',
                get_string('showallpayments', 'mod_gwpayments'),
                get_string('showallpayments', 'mod_gwpayments'));
        $mform->setType('showallpayments', PARAM_INT);


        $mform->setExpanded('content');

        // -------------------------------------------------------
        $this->standard_coursemodule_elements();

        // -------------------------------------------------------
        $completion = new completion_info($COURSE);
        if ($completion->is_enabled()) {
            $this->_form->setConstant('completion', COMPLETION_TRACKING_AUTOMATIC);
            $this->_form->freeze('completion');
        } else {
            $mform->addElement('static', 'completiondisabled', get_string('completiondisabled:label', 'mod_gwpayments'),
                    get_string('completiondisabled:warning', 'mod_gwpayments'));
            $mform->closeHeaderBefore('completiondisabled');
        }
        // -------------------------------------------------------
        $this->add_action_buttons();
    }

    /**
     * Can be overridden to add custom completion rules if the module wishes
     * them. If overriding this, you should also override completion_rule_enabled.
     * <p>
     * Just add elements to the form as needed and return the list of IDs. The
     * system will call disabledIf and handle other behaviour for each returned
     * ID.
     * @return array Array of string IDs of added items, empty array if none
     */
    public function add_completion_rules() {
        global $OUTPUT;
        $img = html_writer::img($OUTPUT->pix_icon('t/check', ''), '');
        $img = html_writer::img($OUTPUT->pix_icon('i/completion-auto-', 'moodle'), '');
        $mform =& $this->_form;

        $mform->addElement('static', '_completionsubmit', '', $img . ' ' . get_string('completionsubmit', 'mod_gwpayments'));
        $mform->addElement('hidden', 'completionsubmit', 1);
        $mform->setType('completionsubmit', PARAM_INT);
        return array('_completionsubmit', 'completionsubmit');
    }

    /**
     * Called during validation. Override to indicate, based on the data, whether
     * a custom completion rule is enabled (selected).
     *
     * @param array $data Input data (not yet validated)
     * @return bool True if one or more rules is enabled, false if none are;
     *   default returns false
     */
    public function completion_rule_enabled($data) {
        return !empty($data['completionsubmit']);
    }

    /**
     * Allows module to modify data returned by get_moduleinfo_data() or prepare_new_moduleinfo_data() before calling set_data()
     * This method is also called in the bulk activity completion form.
     *
     * Only available on moodleform_mod.
     *
     * @param array $defaultvalues passed by reference
     */
    public function data_preprocessing(&$defaultvalues) {
/*
        if (!empty($defaultvalues['advoptions'])) {
            $advoptions = (array) unserialize_array($defaultvalues['advoptions']);
            if (isset($advoptions['printintro'])) {
                $defaultvalues['printintro'] = $advoptions['printintro'];
            }
        }
*/
    }

    /**
     * Allows module to modify the data returned by form get_data().
     * This method is also called in the bulk activity completion form.
     *
     * Only available on moodleform_mod.
     *
     * @param stdClass $data the form data to be modified.
     */
    public function data_postprocessing($data) {
        parent::data_postprocessing($data);
        // Set up completion section even if checkbox is not ticked.
        if (!empty($data->completionunlocked)) {
            if (empty($data->completionsubmit)) {
                $data->completionsubmit = 1;
            }
        }
    }

    /**
     * Perform form validation
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Validating Entered gwpayments, we are looking for obvious problems only,
        // teachers are responsible for testing if it actually works.
	if( $data['cost'] < 0.01 ) $errors['cost'] = get_string('mincosterror', 'mod_gwpayments');

	if( $data['hidepaymentaccount'] && empty($data['addpaymentlink']) )
	    $errors['addpaymentlink'] = get_string('addpaymentlinkempty', 'mod_gwpayments');

        return $errors;
    }

}
