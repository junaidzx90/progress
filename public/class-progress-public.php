<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Progress
 * @subpackage Progress/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Progress
 * @subpackage Progress/public
 * @author     Md Junayed <admin@easeare.com>
 */
class Progress_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( 'progress', [$this,'progress_front_module'] );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/progress-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/progress-public.js', array( 'jquery' ), $this->version, false );

	}

	function progress_front_module($atts){
		ob_start();
		$entry_id = 0;
		if(!empty($atts['entry'])){
			$entry_id = intval($atts['entry']);
		}
		
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}progress_entries_v2 WHERE ID = $entry_id");

		if($results){
			require plugin_dir_path( __FILE__ )."partials/progress-public-display.php";
		}
		
		$output = ob_get_contents();
		ob_get_clean();
		return $output;
	}
}
