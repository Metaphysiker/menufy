<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.philosophische-insel.ch/
 * @since      1.0.0
 *
 * @package    Menufy
 * @subpackage Menufy/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Menufy
 * @subpackage Menufy/public
 * @author     Sandro Räss <s.raess@me.com>
 */
class Menufy_Public {

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

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		wp_register_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css' );
 	 	wp_enqueue_style('bootstrap-css');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/menufy-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/menufy-public.js', array( 'jquery' ), $this->version, false );

	}

	//add_action( 'init', 'menufy_add_shortcode' );

	public function menufy_add_shortcode() {
	    add_shortcode(
				'menufy',
				array( $this, 'menufy_func' )
			);
	}

	public function menufy_func($atts){
		$menus = get_terms( 'nav_menu' );
		$html_block = "";
		$attributes = shortcode_atts( array(
			'menu_id' => $menus[0],
			'per-page' => '50',
		), $atts );
		//$menu_items = wp_get_nav_menu_items($attributes['menu_id']);
		$menu_items = $this->wp_get_menu_array($attributes['menu_id']);
		$top_menu_items = $menu_items;
		$top_menu_items_r = print_r($menu_items);
		if (!function_exists('is_plugin_active')) {
				include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}

		foreach ($top_menu_items as $key=>&$menu_item) {

			$menu_item_description = "";

			$item_featured_image = get_the_post_thumbnail_url($menu_item["ID"], "medium");


			if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) || is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ) ) {
				$menu_item_description = YoastSEO()->meta->for_url($menu_item["url"])->description;
			}

		    $html_block = $html_block . "

				<div class=\"card shadow border-0 my-5\">
				  <div class=\"card-body\">
				    <h2 class=\"card-title\">{$menu_item["title"]}</h2>
						<div class=\"row\">
							<div class=\"col-4\">
							<img src=\"{$item_featured_image}\" class=\"img-fluid\">
							</div>
							<div class=\"col-8\">
							<p>
								{$menu_item_description}
							</p>
							<p>
							{$item_featured_image}
							</p>
							<p>
								{$menu_item["ID"]}
							</p>
							<a href=\"{$menu_item["url"]}\" class=\"btn btn-primary\">MEHR ERFAHREN</a>
							</div>
						</div>
				  </div>
				</div>
				";
		}

return <<<HTML
		{$html_block}
		{$top_menu_items_r}
HTML;
	}



	function wp_get_menu_array($current_menu) {

	    $array_menu = wp_get_nav_menu_items($current_menu);
	    $menu = array();
	    foreach ($array_menu as $m) {
	        if (empty($m->menu_item_parent)) {
	            $menu[$m->ID] = array();
	            $menu[$m->ID]['ID']      =   $m->ID;
	            $menu[$m->ID]['title']       =   $m->title;
	            $menu[$m->ID]['url']         =   $m->url;
	            $menu[$m->ID]['children']    =   array();
	        }
	    }
	    $submenu = array();
	    foreach ($array_menu as $m) {
	        if ($m->menu_item_parent) {
	            $submenu[$m->ID] = array();
	            $submenu[$m->ID]['ID']       =   $m->ID;
	            $submenu[$m->ID]['title']    =   $m->title;
	            $submenu[$m->ID]['url']  =   $m->url;
	            $menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
	        }
	    }
	    return $menu;
	}

}
