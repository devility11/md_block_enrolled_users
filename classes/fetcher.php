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
 * File containing enrolled users
 *
 * @package    block_enrolled_users
 * @copyright  2019 Norbert Czirjak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_enrolled_users;

defined('MOODLE_INTERNAL') || die();

class fetcher {
 
    /**
     * Get the actual enrolled users
     * 
     * @global type $DB
     * @param string $contextId
     * @return array
     */
    public function getEnrolledUsers(string $contextId): array {
        global $DB;
        
        if(!$contextId) { return array(); }
        
        $result = array();
        $result = $DB->get_records_sql('
            SELECT 
                u.*
            FROM 
                mdl_user u, mdl_role_assignments r
            WHERE
                u.id=r.userid AND r.contextid = ?', array($contextId));
        
        return $result;
    }
}
