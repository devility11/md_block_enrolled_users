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
 * Online users block.
 *
 * @package    block_enrolled_users
 * @copyright  2019 Norbert Czirjak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_enrolled_users\fetcher;

/**
 * This block needs to be reworked.
 * The new roles system does away with the concepts of rigid student and
 * teacher roles.
 */
class block_enrolled_users extends block_base {
    function init() {
        $this->title = get_string('pluginname','block_enrolled_users');
    }

    function has_config() {
        return true;
    }
	    
    function get_content() {
        global $USER, $CFG, $DB, $OUTPUT, $COURSE;

        if ($this->content !== NULL) {
            return $this->content;
        }
        
        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        if (empty($this->instance)) {
            return $this->content;
        }

        $fetcher = new fetcher();
        
        $context = context_course::instance($COURSE->id);
        $contextId = $context->id;
        
        $enrolledUsers = $fetcher->getEnrolledUsers($contextId);
        $courseName = $COURSE->fullname;

        // Verify if we can see the list of users, if not just print number of users
        if (!has_capability('block/online_users:viewlist', $this->page->context)) {
            return $this->content;
        }

        if (!empty($enrolledUsers)) {
            $this->content->text .= "<ul class='list'>\n";
            foreach ($enrolledUsers as $user) {
                $this->content->text .= '<li class="listentry">';
                    $this->content->text .= '<div class="user">';
                    $this->content->text .= $OUTPUT->user_picture($user, array('size'=>16, 'alttext'=>false, 'link'=>false)) .$user->lastname.' '.$user->firstname.' ('.$user->country.')';
                    $this->content->text .= '</div>';
                $this->content->text .= "</li>\n";
            }
            
            $this->content->text .= '</ul><div class="clearer"><!-- --></div>';
            $this->content->text .= '<div>';
                $this->content->text .= '<a href="'.$CFG->wwwroot.'/webservice/rest/server.php?wstoken='.$CFG->wstoken.'&wsfunction=enrolled_users&moodlewsrestformat=json&courseid='.$this->page->course->id.'" target="_blank">Download user data in csv</a>';
            $this->content->text .= '</div>';
        }

        return $this->content;
    }
}


