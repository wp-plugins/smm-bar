<?php
/*
Plugin Name: SMM Bar
Description: Social Media Monitoring für WordPress. Kennzahlen zu Shares der Blogartikel auf Facebook, Google+, Twitter und Pinterest in der Admin Bar.
Author: Sergej M&uuml;ller
Author URI: http://wpseo.de
Plugin URI: http://wordpress.org/extend/plugins/smm-bar/
Version: 0.0.2
*/


/* Sicherheit */
if ( !class_exists('WP') ) {
	die();
}


/* Filter */
if ( !( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) or (defined('DOING_CRON') && DOING_CRON) or (defined('DOING_AJAX') && DOING_AJAX) or (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) ) ) {
	/* Autoload */
	spl_autoload_register('smmbar_autoload');
	
	/* Konstanten */
	define('SMM_BAR_DIR', dirname(__FILE__));
	define('SMM_BAR_FILE', __FILE__);
	
	/* Init */
	add_action(
		'plugins_loaded',
		array(
			'SMM_Bar',
			'init'
		)
	);
}


/* Autoload */
function smmbar_autoload($class) {
	if ( in_array($class, array('SMM_Bar', 'SMM_Bar_Twitter', 'SMM_Bar_Gplus', 'SMM_Bar_FB', 'SMM_Bar_Pinterest')) ) {
		require_once(
			sprintf(
				'%s/inc/%s.class.php',
				SMM_BAR_DIR,
				strtolower($class)
			)
		);
	}
}