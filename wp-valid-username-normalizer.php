<?php
/*
Plugin Name: wp-valid-username-normalizer
Plugin URI: http://code.google.com/p/wp-valid-username-normalizer
Description: If you happen to have a wordpress user database table full of invalid usernames, containing invalid "funny characters" (such as spaces, national characters, etc.), this plugin will clean up that mess! This plugin will not only sanitize your users database table, it will also map your old invalid usernames to the new valid ones, so you do not have to instruct all your users to login with a new username.
Version: 1.0
Author: Alfred Godoy, Klandestino AB
Author URI: http://www.klandestino.se/
License: GPLv3 or later
*/
/*
   wp-valid-username-normalizer
    Copyright (C) 2012  Alfred Godoy <alfred@klandestino.se>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class WpValidUsernameNormalizer {

	static function fixDatabase() {
		global $wpdb;

		$result = $wpdb->get_results('SELECT `ID`, `user_login` FROM `' . $wpdb->users .'` ORDER BY `ID`');
	
		// $users array is for duplicate checking.	
		$users = array();
		foreach ($result as $user) { // First add all existing users to the dupe checking array:
			$users[$user->user_login] = true;
		}
		foreach ($result as $user) {
			if (!preg_match('/^[a-z0-9]+$/', $user->user_login)) {

				// Normalizer:
				$newuser = WpValidUsernameNormalizer::normalize($user->user_login);
				// Dupe check:
				$counter = '';
				while (isset($users[$newuser . $counter])) {
					$counter = $counter + 1;
					echo('Duplicate: ' . $newuser . $counter . '<br />');
				}
				$newuser = $newuser . $counter;

				// Add the normalized user to the dupe checking array.
				$users[$newuser] = true;

				// Do the replace stuff in the database:
				add_user_meta($user->ID, 'wpvalidusernamenormalizer_invalidname', $user->user_login, true);
				$wpdb->query('UPDATE `' . $wpdb->users . '` SET `user_login`="' . addslashes($newuser) . '" WHERE `ID`=' . $user->ID);
			}
		}
	}

	static function rewriteLoginUsername() {
		global $wpdb;

		if (isset($_POST) && isset($_POST['log']) && isset($_POST['pwd'])) {

			if (!preg_match('/^[a-z0-9]+$/', $_POST['log'])) {
				$result = $wpdb->get_results('SELECT `ID`, `user_login` FROM `' . $wpdb->users . '` WHERE `user_login` LIKE "' . WpValidUsernameNormalizer::normalize($_POST['log']) . '%"');
				foreach ($result as $user) {
					$invalid = get_user_meta($user->ID, 'wpvalidusernamenormalizer_invalidname', true);
					if ($invalid) {
						if ($_POST['log'] == $invalid) {
							$_POST['log'] = $user->user_login;
							return;
						}
					}
				}
			}

		}
	}

	static function normalize($user) {
		$user = strtolower(htmlentities($user));
		while (preg_match('/&([^;]+);/', $user, $regs)) {
			$user = str_replace($regs[0], substr($regs[1], 0, 1), $user);
		}
		$user = preg_replace('/[^a-z0-9]/', '', $user);
		return sanitize_user($user);
	}

}

// Of course, it is stupid to run fixDatabase on every init.
// Someone should do anything about this.
add_action('init', array('WpValidUsernameNormalizer', 'fixDatabase'));
add_action('init', array('WpValidUsernameNormalizer', 'rewriteLoginUsername'));

