<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Progress
 * @subpackage Progress/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Progress
 * @subpackage Progress/includes
 * @author     Md Junayed <admin@easeare.com>
 */
class Progress_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}progress_entries_v2`" );

		$progress_entries = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}progress_entries_v2` (
			`ID` INT NOT NULL AUTO_INCREMENT,
			`entryname` VARCHAR(255) NOT NULL,
			`leftslot` VARCHAR(255) NOT NULL,
			`rightslot` VARCHAR(255) NOT NULL,
			`number` INT NOT NULL,
			`min` INT NOT NULL,
			`max` INT NOT NULL,
			`seconds` INT NOT NULL,
			`countup` INT NOT NULL,
			`textcolor` VARCHAR(55) NOT NULL,
			`numbercolor` VARCHAR(55) NOT NULL,
			`border_switch` BOOLEAN NOT NULL,
			`bordercolor` VARCHAR(55) NOT NULL,
			`fontsize` 	INT NOT NULL,
			`create_date` DATE NOT NULL,
			PRIMARY KEY (`ID`)) ENGINE = InnoDB";
			dbDelta($progress_entries);
	}

}
