<?php


/**
* SMM_Bar
*
* @since 0.0.1
*/

class SMM_Bar
{
	
	
	/**
	* Init des Plugins
	*
	* @since   0.0.1
	* @change  0.0.1
	*/
	
	public static function init() {
		/* Aktionen */
		add_action(
			'init',
			array(
				__CLASS__,
				'register_data'
			)
		);
		add_action(
			( is_admin() ? 'admin_init' : 'template_redirect' ),
			array(
				__CLASS__,
				'touch_rendering'
			)
		);
		add_action(
			self::_app_hash('data', 'count'),
			array(
				__CLASS__,
				'show_metric'
			)
		);
	}
	
	
	/**
	* Start der Prozesse
	*
	* @since   0.0.1
	* @change  0.0.1
	*/
	
	public static function touch_rendering() {
		/* Raus? */
		if ( self::_skip_rendering() ) {
			return;
		}
		
		/* Aktionen */
		add_action(
			'admin_enqueue_scripts',
			array(
				__CLASS__,
				'add_css'
			)
		);
		add_action(
			'wp_enqueue_scripts',
			array(
				__CLASS__,
				'add_css'
			)
		);
		add_action(
			'admin_bar_menu',
			array(
				__CLASS__,
				'add_menu'
			),
			99
		);
	}
	
	
	/**
	* Start der Prozesse
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @return  boolean  true/false  TRUE fürs Aussteigen
	*/
	
	private static function _skip_rendering() {
		/* Global */
		global $pagenow;	
		
		/* Admin Bar an? */
		if ( !is_admin_bar_showing() ) {
			return true;
		}
		
		/* Filter */
		if ( is_admin() ) {
			if ( empty($pagenow) or $pagenow != 'post.php' ) {
				return true;
			}
		} else {
			if ( !is_singular() ) {
				return true;
			}
		}
		
		return false;
	}
	
	
	/**
	* Registriert verfügbare Datentypen als Filter
	*
	* @since   0.0.1
	* @change  0.0.2
	*/
	
	public static function register_data() {
		foreach( array('SMM_Bar_Twitter', 'SMM_Bar_Gplus', 'SMM_Bar_FB', 'SMM_Bar_Pinterest') as $class ) {
			add_filter(
				self::_app_hash('data', 'item'),
				array(
					$class,
					'init'
				),
				10,
				2
			);
		}
	}
	
	
	/**
	* Gibt ein Array mit Kennzahlen für eine Post-ID zurück
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   integer  $post_id  ID eines Artikels
	* @return  array    $data     Array mit Kennzahlen
	*/
	
	private static function _get_data($post_id)
	{
		/* Konvertieren */
		$post_id = (int) $post_id;
		
		/* Leer? */
		if ( empty($post_id) ) {
			wp_die('Get data: Empty post ID');
		}
		
		/* Auslesen */
		$data = get_transient(
			self::_app_hash('data', $post_id)
		);
		
		/* Generieren */
		if ( empty($data) ) {
			return self::_build_data($post_id);
		}
		
		return $data;
	}
	
	
	/**
	* Initialisiert und speichert die Daten für eine Post-ID
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   integer  $post_id  ID eines Artikels
	* @return  array    $data     Array mit Kennzahlen
	*/
	
	private static function _build_data($post_id)
	{
		/* Konvertieren */
		$post_id = (int) $post_id;
		
		/* Leer? */
		if ( empty($post_id) ) {
			wp_die('Build data: Empty post ID');
		}
		
		/* Einsammeln */
		$data = apply_filters(
			self::_app_hash('data', 'item'),
			array(),
			get_permalink($post_id)
		);
		
		/* Leer? */
		if ( empty($data) ) {
			wp_die('Build data: Empty data array');
		}
		
		/* Speichern */
		set_transient(
			self::_app_hash('data', $post_id),
			$data,
			60 * 60 * 1 /* 1 Stunde */
		);
		
		return $data;
	}
	
	
	/**
	* Generiert einen internen Hash-String
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   string  $prefix  Hash-Prefix
	* @param   string  $suffix  Hash-Suffix
	* @return  string  $diff    Zusammengestellter Hash
	*/
	
	private static function _app_hash($prefix, $suffix)
	{
		return sprintf(
			'smmbar-%s-%s',
			(string) $prefix,
			(string) $suffix
		);
	}
	
	
	/**
	* Einbindung von Styles
	*
	* @since   0.0.1
	* @change  0.0.1
	*/

	public static function add_css()
	{
		/* CSS registrieren */
		wp_register_style(
			'smm_bar_css',
			plugins_url('css/style.css', SMM_BAR_FILE),
			array(),
			'17042012'
		);

		/* CSS einbinden */
		wp_enqueue_style('smm_bar_css');
	}
	
	
	/**
	* Fügt ein Element der Adminbar hinzu
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   object  Objekt mit Menü-Eigenschaften
	*/
	
	public static function add_menu($wp_admin_bar) {
		/* Global */
		global $post;
		
		/* Filter 1 */
		if ( !is_object($post) or $post->post_status != 'publish' ) {
			return;
		}
		
		/* Anlegen */
		$wp_admin_bar->add_menu(
			array(
				'parent' => 'top-secondary',
				'id'     => 'smm_bar',
				'title'  => self::_render_menu($post->ID)
			)
		);
	}
	
	
	/**
	* Rendert den Eintrag für die Adminbar
	*
	* @since   0.0.1
	* @change  0.0.1
	*/
	
	private static function _render_menu($post_id) {
		/* Konvertieren */
		$post_id = (int) $post_id;
		
		/* Leer? */
		if ( empty($post_id) ) {
			wp_die('Render menu: Empty post ID');
		}
		
		/* Auslesen */
		$data = self::_get_data($post_id);
		
		/* Leer? */
		if ( empty($data) or !is_array($data) ) {
			return null;
		}
		
		/* Starten */
		$output = '<ul class="metrics-items">';
		
		/* Loopen */
		foreach ( $data as $v ) {
			$output .= '<li class="metrics-item">';
			$output .= '<label class="metrics-item-name">' .esc_html($v['name']). '</label>';
			$output .= '<span class="metrics-item-count">' .esc_html($v['count']). '</span>';
			$output .= '</li>';
		}
		
		/* Enden */
		$output .= '</ul>';
		
		return $output;
	}
	
	
	/**
	* Gibt eine Kennzahl aus
	*
	* @since   0.0.1
	* @change  0.0.1
	*
	* @param   string  $type  Typ der Kennzahl
	*/
	
	public static function show_metric($type) {
		/* Global */
		global $post;
		
		/* Leer? */
		if ( empty($type) or !is_object($post) or empty($post->ID) ) {
			return null;
		}
		
		/* Auslesen */
		$data = self::_get_data($post->ID);
		
		/* Leer? */
		if ( empty($data) or !is_array($data) ) {
			return null;
		}
		
		/* Ausgabe */
		echo ( empty($data[$type]['count']) ? 0 : esc_html($data[$type]['count']) );
	}
}