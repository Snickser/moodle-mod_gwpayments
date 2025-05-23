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
 * Send expiry notifications task.
 *
 * @package   mod_gwpayments
 * @copyright 2025 Alex Orlov <snickser@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_gwpayments\task;

/**
 * Send expiry notifications task.
 *
 * @package   mod_gwpayments
 * @copyright 2025 Alex Orlov <snickser@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class send_expiry_notifications extends \core\task\scheduled_task {
    /**
     * Name for this task.
     *
     * @return string
     */
    public function get_name() {
        return get_string('sendexpirynotificationstask', 'mod_gwpayments');
    }

    /**
     * Run task for sending expiry notifications.
     */
    public function execute() {
        global $DB, $CFG;

        mtrace('Start');

        $ctime = time();

	$uds = $DB->get_records_sql('SELECT id,gwpaymentsid,userid,notified,timeexpire FROM {gwpayments_userdata}
	    WHERE timeexpire>?', [ $ctime ]);

	foreach ($uds as $data) {
	    // Get expirynotify.
	    $expirynotify = $DB->get_field('gwpayments', 'expirynotify', ['id' => $data->gwpaymentsid]);
            if (!$expirynotify) {
                continue;
            }

mtrace( $data->timeexpire - $ctime - $expirynotify );
mtrace( $data->timeexpire - $data->notified );

    	    if (
    		($data->timeexpire - $ctime - $expirynotify) > 0 ||
    		($data->timeexpire - $data->notified) <= $expirynotify)
    	    {
    		continue;
    	    }

	    // Get user data.
            if (!$user = $DB->get_record('user', ['id' => $data->userid])) {
                mtrace("$data->userid not found");
                continue;
            }

            mtrace("$user->id $user->email");

            $oldforcelang = force_current_language($user->lang);

            // Make message.
            $message = new \core\message\message();
            $message->component = 'mod_gwpayments';
            $message->name      = 'expiry_notification'; // The notification name from message.php.
            $message->userfrom  = \core_user::get_noreply_user(); // If the message is 'from' a specific user you can set them here.
            $message->userto    = \core_user::get_user($user->id);
            $message->subject   = get_string('expiredmessagesubject', 'mod_gwpayments');

            // Set the object with all informations to notify the user.
            $a = (object)[
                'firstname' => $user->firstname,
                'fullname'  => fullname($user),
            ];

            $messagebody = get_string('expiredmessagebody', 'mod_gwpayments', $a);

            $message->fullmessage       = $messagebody;
            $message->fullmessageformat = FORMAT_MARKDOWN;
            $message->fullmessagehtml   = "<p>$messagebody</p>";
            $message->notification      = 1; // Because this is a notification generated from Moodle, not a user-to-user message.
            $message->contexturl        = ''; // A relevant URL for the notification.
            $message->contexturlname    = ''; // Link title explaining where users get to for the contexturl.
            $content = ['*' => ['header' => '', 'footer' => '']]; // Extra content for specific processor.
            $message->set_additional_content('email', $content);

            // Actually send the message.
            message_send($message);

            force_current_language($oldforcelang);

            $DB->update_record('gwpayments_userdata', ['id' => $data->id, 'notified' => $ctime]);

	}

    }
}
