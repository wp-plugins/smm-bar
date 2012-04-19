<?php


/* Sicherheit */
if ( !class_exists('SMM_Bar') ) {
	die();
}


/**
* SMM_Bar_Pinterest
*/

final class SMM_Bar_Pinterest {
	
	
	/**
	* Initialisiert den Zähler
	*
	* @since   0.0.2
	* @change  0.0.2
	*
	* @param   array   $data       Array mit vorhandenen Kennzahlen
	* @param   string  $permalink  Permalink des Artikels
	* @return  array   $data       Array mit erweiterten Kennzahlen
	*/
	
	public static function init($data, $permalink) {
		$data['pinterest'] = array(
			'name'  => 'Pinterest',
			'count' => (int) self::_count($permalink)
		);
		
		return $data;
	}
	
	
	/**
	* Fragt die API ab
	*
	* @since   0.0.2
	* @change  0.0.2
	*
	* @param   string  $permalink  Permalink des Artikels
	* @return  mixed   $count      Ermittelte Anzahl
	*/
	
	private static function _count($permalink) {
		/* Anfrage */
		$response = wp_remote_get(
			esc_url_raw(
				sprintf(
					'https://api.pinterest.com/v1/urls/count.json?callback=&url=%s',
					urlencode($permalink)
				),
				array('http', 'https')
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
		if ( ! $body = wp_remote_retrieve_body($response) ) {
			return null;
		}
		
		/* JSON */
		$json = json_decode(
			str_replace(
				array('(', ')'),
				'',
				$body
			)
		);
		
		/* Leer? */
		if ( empty($json->count) ) {
			return null;
		}
		
		return $json->count;
	} 
}