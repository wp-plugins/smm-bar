<?php


/* Sicherheit */
if ( !class_exists('SMM_Bar') ) {
	die();
}


/**
* SMM_Bar_Gplus
*/

final class SMM_Bar_Gplus {
	
	
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
		$data['gplus'] = array(
			'name'  => 'Google+',
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
		$response = wp_remote_post(
			'https://clients6.google.com/rpc',
			array(
				'method' => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json'
				),
				'body' => json_encode(
					array(
						'method' => 'pos.plusones.get',
						'id' => 'p',
						'method' => 'pos.plusones.get',
						'jsonrpc' => '2.0',
						'key' => 'p',
						'apiVersion' => 'v1',
						'params' => array(
							'nolog' => true,
							'id' => $permalink,
							'source' =>'widget',
							'userId' =>'@viewer',
							'groupId' =>'@self'
						)
					)
				),
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
		$data = json_decode($json, true);
		
		/* Leer? */
		if ( empty($data['result']['metadata']['globalCounts']['count']) ) {
			return null;
		}
		
		return $data['result']['metadata']['globalCounts']['count'];
	}
}