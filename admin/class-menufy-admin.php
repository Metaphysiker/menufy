<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.philosophische-insel.ch/
 * @since      1.0.0
 *
 * @package    Menufy
 * @subpackage Menufy/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Menufy
 * @subpackage Menufy/admin
 * @author     Sandro Räss <s.raess@me.com>
 */
class Menufy_Admin {

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

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Menufy_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Menufy_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/menufy-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Menufy_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Menufy_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/menufy-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function dashboard_page(){

		add_menu_page(
			'Menufy',
			'Menufy',
			'manage_options',
			'menufy-dashboard',
			array( $this, 'dashboard_page_html' ),
			'dashicons-book-alt'
	);

}

public function dashboard_page_html(){
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
			return;
	}

	$menus = get_terms( 'nav_menu' );

	?>

	<?php
	$meta_tags = get_meta_tags('https://rrrevolve.ch/302_vegane-gesellschaft-schweiz');
	print_r($meta_tags);
	print($meta_tags["description"]);
	?>

	<h1>Menufy</h1>
	<p>Wenn man ein Untermenü anzeigen will, dann muss man die ID angeben</p>

	<?php
	foreach ($menus as &$menu) {
		echo "<h2> {$menu->name} - {$menu->term_id} </h2>";
		echo "<p>";
		echo "[menufy menu_id={$menu->term_id}]";
		echo "</p>";
		$array_menu = wp_get_nav_menu_items($menu->term_id);

		foreach ($array_menu as $m) {
				if (empty($m->menu_item_parent)) {
						echo "<h3>{$m->title}: {$m->ID}</h3>";
						echo "<p>";
						echo "[menufy menu_id={$menu->term_id} sub_menu_id={$m->ID}]";
						echo "</p>";
						echo "<p>";
						foreach ($array_menu as &$sm) {
							if ($sm->menu_item_parent == $m->ID) {
								echo " - {$sm->title}: {$sm->ID}";
								echo "<br>";
							}
						}
						echo "</p>";
						echo "<hr>";
				}
		}
	}
	?>

	<?php
}

}
