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
 * paymentdetails overview for one specific user.
 *
 * File         paymentdetails.php
 * Encoding     UTF-8
 *
 * @package     mod_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_gwpayments\output\component;

use renderable;
use templatable;
use context;
use stdClass;

/**
 * paymentdetails overview for one specific user.
 *
 * @package     mod_gwpayments
 *
 * @copyright   2021 Ing. R.J. van Dongen
 * @author      Ing. R.J. van Dongen <rogier@sebsoft.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class paymentdetails implements renderable, templatable {

    /**
     * @var context
     */
    protected $context;
    /**
     * @var int
     */
    protected $userid;

    /**
     * Create new instance
     *
     * @param context $context
     * @param int $userid
     */
    public function __construct(context $context, $userid = null) {
        global $USER;
        if (empty($userid)) {
            $userid = $USER->id;
        }
        $this->context = $context;
        $this->userid = $userid;
    }

    /**
     * Export variables for template use.
     *
     * @param \renderer_base $output
     */
    public function export_for_template(\renderer_base $output) {
        global $DB;
        $rs = new stdClass;

        if ($this->context instanceof \context_module) {
            $sql = 'SELECT DISTINCT ud.*
                    FROM mdl_course_modules cm
                    JOIN mdl_gwpayments_userdata ud on ud.gwpaymentsid=cm.instance
                    JOIN mdl_user u on ud.userid=u.id
                    WHERE cm.id=:cmid and userid=:userid';
            $params = [
                'userid' => $this->userid,
                'cmid' => $this->context->instanceid,
            ];
        }

        $rs->payments = array_values($DB->get_records_sql($sql, $params));

        foreach ($rs->payments as &$payment) {
            $payment->strcost = \core_payment\helper::get_cost_as_string($payment->cost, $payment->currency);
            $payment->expires = $payment->timeexpire > 0;
        }
        unset($payment);

        $rs->haspayments = count($rs->payments) > 0;

        return $rs;
    }

}
