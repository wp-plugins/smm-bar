<?php


/* Sicherheit */
if ( !class_exists('SMM_Bar') ) {
	die();
}


/**
* SMM_Bar_FB
*/

final class SMM_Bar_FB {
	
	
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
		$data['facebook'] = array(
			'name'  => 'Facebook',
			'count' => (int) self::_count($permalink)
		);
		
		return $data;
	}
	
	
	/**
	* Fragt die API ab
	*
	* @since   0.0.1
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
					'https://graph.facebook.com/%s',
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
		if ( ! $json = wp_remote_retrieve_body($response) ) {
			return null;
		}
		
		/* JSON */
		$data = json_decode($json);
		
		/* Leer? */
		if ( empty($data->shares) ) {
			return null;
		}
		
		return $data->shares;
	}
}