<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Progress
 * @subpackage Progress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Progress
 * @subpackage Progress/admin
 * @author     Md Junayed <admin@easeare.com>
 */
class Progress_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if(isset($_GET['page']) && $_GET['page'] == 'progress'){
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/progress-admin.css', array(), microtime(), 'all' );
			wp_enqueue_style( 'dataTable', plugin_dir_url( __FILE__ ) . 'css/dataTable.css', array(), microtime(), 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if(isset($_GET['page']) && $_GET['page'] == 'progress'){
			wp_enqueue_script( 'vue', 'https://cdn.jsdelivr.net/npm/vue@2.6.14', '', '', false );
			wp_enqueue_script( 'dataTable', plugin_dir_url( __FILE__ ) . 'js/dataTable.js', array( 'jquery' ), microtime(), false );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/progress-admin.js', array( 'jquery' ), microtime(), true );
			wp_localize_script($this->plugin_name, "progress_entries", array(
				'ajaxurl' => admin_url('admin-ajax.php')
			));
		}
	}

	function progress_menupage(){
		add_menu_page( 'Progress', 'Progress', 'manage_options', 'progress', [$this,'progress_menupage_view'], 'dashicons-smiley', 45 );

		// options
		add_settings_section( 'progress_settings_section', '', '', 'progress_settings_page' );

		add_settings_field( 'progress_font_size', 'Font size', [$this,'progress_font_size_cb'], 'progress_settings_page', 'progress_settings_section');
		register_setting( 'progress_settings_section', 'progress_font_size');

		add_settings_field( 'progress_text_color', 'Text color', [$this,'progress_text_color_cb'], 'progress_settings_page', 'progress_settings_section');
		register_setting( 'progress_settings_section', 'progress_text_color');

		add_settings_field( 'progress_number', 'Number color', [$this,'progress_number_cb'], 'progress_settings_page', 'progress_settings_section');
		register_setting( 'progress_settings_section', 'progress_number');

		add_settings_field( 'progress_border_color', 'Border color', [$this,'progress_border_color_cb'], 'progress_settings_page', 'progress_settings_section');
		register_setting( 'progress_settings_section', 'progress_border_color');
	}

	function progress_menupage_view(){
		require_once plugin_dir_path( __FILE__ )."partials/progress-admin-display.php";
	}

	function progress_entries_save(){
		if(isset($_POST['data'])){
			global $wpdb;
			$data 		= $_POST['data'];
			$entryName 	= sanitize_text_field( $data['entryName'] );
			$leftslot 	= sanitize_text_field($data['leftSlot']);
			$number 	= intval($data['number']);
			$min 		= intval($data['min']);
			$max 		= intval($data['max']);
			$seconds 	= intval($data['seconds']);
			$textcolor 	= $data['textcolor'];
			$numbercolor 	= $data['numbercolor'];
			$borderswitch 	= $data['borderswitch'];
			$bordercolor 	= $data['bordercolor'];
			$fontsize 	= intval($data['fontsize']);
			$rightSlot 	= sanitize_text_field($data['rightSlot']);

			if($borderswitch == 'false'){
				$borderswitch = 0;
			}else{
				$borderswitch = 1;
			}

			if($id = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}progress_entries_v2 WHERE entryname = '$entryName'")){
				echo json_encode(array('error' => 'This entry is already exist.'));
				die;
			}

			if(!empty($entryName) && !empty($leftslot) && !empty($rightSlot) && $number !== 0 || ($min !== 0 && $max !== 0)){
				$insert = $wpdb->insert($wpdb->prefix.'progress_entries_v2',array(
					'entryname' 	=> $entryName,
					'leftslot' 		=> $leftslot,
					'rightslot' 	=> $rightSlot,
					'number'	 	=> $number,
					'min'	 		=> $min,
					'max'	 		=> $max,
					'seconds'	 	=> $seconds,
					'textcolor' 	=> $textcolor,
					'numbercolor' 	=> $numbercolor,
					'bordercolor' 	=> $bordercolor,
					'border_switch' => $borderswitch,
					'fontsize' 		=> $fontsize,
					'create_date' 	=> date('d-m-y'),
				),array('%s','%s','%s','%d','%d','%d','%d','%s','%s','%s','%d','%d','%s'));
	
				if($insert){
					echo json_encode(array('success' => 'success'));
					die;
				}
				die;
			}else{
				echo json_encode(array('error' => 'Trying without required values!'));
				die;
			}
			die;
		}
		die;
	}

	function progress_update_entry(){
		if(isset($_POST['data'])){
			global $wpdb;
			$data = $_POST['data'];
			$entry_id = intval($data['entry_id']);
			$entryName = sanitize_text_field($data['entryName']);
			$textcolor = $data['textcolor'];
			$numbercolor = $data['numbercolor'];
			$borderswitch = $data['borderswitch'];
			$bordercolor = $data['bordercolor'];
			$fontsize = $data['fontsize'];
			$edit_left = sanitize_text_field($data['edit_left']);
			$edit_number = intval($data['edit_number']);
			$edit_min = intval($data['edit_min']);
			$edit_max = intval($data['edit_max']);
			$seconds = intval($data['seconds']);
			$edit_right = sanitize_text_field($data['edit_right']);

			if(!$borderswitch || $borderswitch == 'false'){
				$borderswitch = 0;
			}else{
				$borderswitch = 1;
			}

			if(!empty($entryName) && !empty($edit_left) && !empty($edit_right) && $edit_number !== 0 || ($edit_min !== 0 && $edit_max !== 0)){
				$wpdb->update($wpdb->prefix.'progress_entries_v2',array(
					'entryname' 	=> $entryName,
					'leftslot' 		=> $edit_left,
					'rightslot' 	=> $edit_right,
					'number'	 	=> $edit_number,
					'min'	 		=> $edit_min,
					'max'	 		=> $edit_max,
					'seconds'	 	=> $seconds,
					'textcolor' 	=> $textcolor,
					'numbercolor' 	=> $numbercolor,
					'bordercolor' 	=> $bordercolor,
					'border_switch' => $borderswitch,
					'fontsize' 		=> $fontsize
				),array('ID' => $entry_id),array(
					'%s','%s','%s','%d','%d','%d','%d','%s','%s','%s','%d','%d'
				),array('%d'));

				echo json_encode(array('success' => 'Success'));
				die;
			}else{
				echo json_encode(array('error' => 'Trying without required values!'));
				die;
			}
			die;
		}
		die;
	}

	function delete_entry(){
		if(isset($_POST['entry_id'])){
			$entry_id = intval($_POST['entry_id']);
			global $wpdb;
			if($wpdb->query("DELETE FROM {$wpdb->prefix}progress_entries_v2 WHERE ID = $entry_id")){
				echo 'Deleted';
				die;
			}
			die;
		}
		die;
	}
}
