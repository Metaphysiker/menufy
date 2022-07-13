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
		wp_register_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js' );
	 	wp_enqueue_script('bootstrap-js');
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
		$accordion_items = "";
		$attributes = shortcode_atts( array(
			'menu_id' => $menus[0],
			'sub_menu_id' => 0,
			'per-page' => '50',
		), $atts );
		//$menu_items = wp_get_nav_menu_items($attributes['menu_id']);
		$menu_items = $this->wp_get_menu_array($attributes['menu_id']);

		if( !empty($attributes['sub_menu_id']) ) {
			$menu_items = $menu_items[$attributes['sub_menu_id']]["children"];
		}
		//$top_menu_items_r = print_r($menu_items);
		if (!function_exists('is_plugin_active')) {
				include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}

		foreach ($menu_items as $key=>&$menu_item) {

			$menu_item_description = "";
			$item_featured_image = get_the_post_thumbnail_url(url_to_postid( $menu_item["url"] ),'full');

			if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) || is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ) ) {
				$menu_item_description = YoastSEO()->meta->for_url($menu_item["url"])->description;
			}

			if (empty($menu_item_description)) {

				$meta_tags_of_external_page = get_meta_tags($menu_item["url"]);

				if (!empty($meta_tags_of_external_page["description"])) {
					$menu_item_description = $meta_tags_of_external_page["description"];
				}

			}

		  if (get_post_meta(url_to_postid( $menu_item["url"] ), 'menufy_custom_description', true)) {
				$menu_item_description = get_post_meta(url_to_postid( $menu_item["url"] ), 'menufy_custom_description', true);
		  }

			$accordion_item = <<<HTML

			<div class="my-3">
			</div>

			<div class="accordion-item border-0 menufy-accordion-item shadow-lg">
				<h2 class="accordion-header menufy-accordion-header" id="heading-{$menu_item["ID"]}">
					<button class="accordion-button menufy-accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{$menu_item["ID"]}" aria-expanded="false" aria-controls="collapse-{$menu_item["ID"]}">
						{$menu_item["title"]}
					</button>
				</h2>
				<div id="collapse-{$menu_item["ID"]}" class="accordion-collapse collapse" aria-labelledby="heading-{$menu_item["ID"]}" data-bs-parent="#menufy-accordion">
					<div class="accordion-body">

						<div class="row">
							<div class="col-12 col-md-6">
								<img src="{$item_featured_image}" class="menufy-img">
							</div>
							<div class="col-12 col-md-6 d-flex align-items-start flex-column mt-5 mt-md-0 ">
								<div class="bd-highlight menufy-description">
									{$menu_item_description}<br>
								</div>
								<div class="mt-auto mr-auto">
									<a href="{$menu_item["url"]}" class="menufy-button align-self-end">MEHR ERFAHREN</a>
								</div>
							</div>
						</div>


					</div>
				</div>
			</div>
			HTML;

			$accordion_items = $accordion_items . $accordion_item;

		    $html_block = $html_block . "

				<div class=\"card shadow border-0 my-5\">
				  <div class=\"card-body\">
				    <h2 class=\"card-title menufy-card-title\">{$menu_item["title"]}</h2>
						<div class=\"row\">
							<div class=\"col-12 col-md-6 d-flex\">
							<img src=\"{$item_featured_image}\" style=\"width: 100%; height: 200px; object-fit: cover;\" class=\"img-fluid menufy-img\">
							</div>
							<div class=\"col-12 col-md-6 d-flex align-items-start flex-column mt-5 mt-md-0 \">

								<div class=\"bd-highlight menufy-description\">
									{$menu_item_description}<br>
								</div>

								<div class=\"mt-auto mr-auto\">

									<a href=\"{$menu_item["url"]}\" class=\"menufy-button align-self-end\">MEHR ERFAHREN</a>
								</div>


							</div>
						</div>
				  </div>
				</div>
				";
		}

return <<<HTML
		<div class="accordion" id="menufy-accordion" class="shadow">
			{$accordion_items}
		</div>

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
