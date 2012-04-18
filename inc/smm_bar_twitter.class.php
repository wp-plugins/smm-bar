<?php


/* Sicherheit */
if ( !class_exists('SMM_Bar') ) {
	die();
}


/**
* SMM_Bar_Twitter
*/

final class SMM_Bar_Twitter {
	
	
	/**
	* Initialisiert den Zähler
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   array   $data       Array mit vorhandenen Kennzahlen
	* @param   string  $permalink  Permalink des Artikels
	* @return  array   $data       Array mit erweiterten Kennzahlen
	*/
	
	public static function init($data, $permalink) {
		$data['twitter'] = array(
			'name'  => 'Twitter',
			'count' => (int) self::_count($permalink)
		);
		
		return $data;
	}
	
	
	/**
	* Fragt die API ab
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   string  $permalink  Permalink des Artikels
	* @return  mixed   $count      Ermittelte Anzahl
	*/
	
	private static function _count($permalink) {
		/* Anfrage */
		$response = wp_remote_get(
			sprintf(
				'https://urls.api.twitter.com/1/urls/count.json?url=%s',
				urlencode($permalink)
			),
			array(
				'sslverify' => false
			)
		);
		
		/* Fehler? */
		if ( is_wp_error($response) ) {
			return null;
		}
		
		/* Auslesen */
		if ( ! $json = wp_remote_retrieve_body($response) ) {
			return null;
		}
		
		/* JSON */
		$data = json_decode($json);
		
		/* Leer? */
		if ( empty($data->count) ) {
			return null;
		}
		
		return $data->count;
	} 
}