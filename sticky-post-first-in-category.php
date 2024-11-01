<?php
/**
 * Plugin Name: Sticky Post First in Category
 * Plugin URI: http://www.seosthemes.com
 * Contributors: seosbg
 * Author: seosbg
 * Description: Sticky Post First in Category WordPress plugin make the post first in the category.
 * Version: 1.1
 * License: GPL2
 */

add_action('admin_menu', 'sticky_post_first_in_category_menu');
function sticky_post_first_in_category_menu() {
    add_menu_page('Sticky Post First in Category', 'Sticky Post First in Category', 'administrator', 'sticky-post-first-in-category', 'sticky_post_first_in_category_settings_page', plugins_url('sticky-post-first-in-category/images/icon.png')
    );

    add_action('admin_init', 'sticky_post_first_in_category_register_settings');
}

function sticky_post_first_in_category_register_settings() {

}

/*********************************************************************************************************
*  	Sticky Post First in Category
**********************************************************************************************************/

function sticky_post_first_in_category_sticky_posts()
{
    if ( !is_category() )
        return false;
    $stickies = get_option( 'sticky_posts' );

    if ( !$stickies )
        return false;
    $current_object = get_queried_object_id();
    $args = array (
        'nopaging' => true,
        'post__in' => $stickies,
        'cat' => $current_object,
        'ignore_sticky_posts' => 1,
        'fields' => 'ids'
    );
    $q = get_posts( $args );

    return $q;
}

function sticky_post_first_in_category_load ($q)
{
    if ( !is_admin()
         && $q->is_main_query()
         && $q->is_category()
    ) {
        if ( function_exists( 'sticky_post_first_in_category_sticky_posts' ) ) {
            $stickies = sticky_post_first_in_category_sticky_posts();

            if ( $stickies ) {
                $q->set( 'post__not_in', $stickies );
                if ( !$q->is_paged() ) {
                    add_filter( 'the_posts', function ( $posts ) use ( $stickies )
                    {   
                        $term_stickies = get_posts( array('post__in' => $stickies, 'nopaging' => true) );

                        $posts = array_merge( $term_stickies, $posts );

                        return $posts;
                    }, 10, 1 );
                }
            }
        }
    }
}
add_action( 'pre_get_posts', 'sticky_post_first_in_category_load');

function sticky_post_first_in_category_settings_page() {
?>

    <div class="wrap sticky-post-first-in-category">
		<h1><?php _e('Sticky Post First in Category', 'sticky-post-first-in-category'); ?></h1>
        <form action="options.php" method="post" role="form" name="sticky-post-first-in-category-form">
		
			<?php settings_fields( 'sticky-post-first-in-category' ); ?>
			<?php do_settings_sections( 'sticky-post-first-in-category' ); ?>
		
			<div>
				<a target="_blank" href="http://seosthemes.com/">
					<div class="btn s-red">
						 <?php _e('SEOS' . ' <img class="ss-logo" src="' . plugins_url( 'images/logo.png' , __FILE__ ) . '" alt="logo" />' . ' THEMES', 'sticky-post-first-in-category'); ?>
					</div>
				</a>
			</div>
			
			
									
		<div class="cc-clr"></div>

			
		</form>	
	</div>
	
	<?php } 
	
	function sticky_post_first_in_category_language_load() {
	  load_plugin_textdomain('sticky_post_first_in_category_language_load', FALSE, basename(dirname(__FILE__)) . '/languages');
	}
	add_action('init', 'sticky_post_first_in_category_language_load');

	
	function sticky_post_first_in_category_admin_options_css() { ?>	
			<style type="text/css">
				.sticky-post-first-in-category {
					width: 100%;
					display: block;
					clear: both;
				}
				
				.sticky-post-first-in-category label {
					font-weight: bold;
				}
				
				.cc-clr {
					display: block;
					clear: both;
					content: "";
				}
				
				.sticky-post-first-in-category .form-group  {
					margin-top: 15px;
					float: left;
					width: 200px;
					height: 50px;
					display:block;
				}
				
				.sticky-post-first-in-category .form-group input {
					border-radius: 4px;
					padding: 10px;
				}
				
				.sticky-post-first-in-category  .s-red {
					background-color: #B70000 !important;
					border: 1px solid #6B0000 !important;
					display: block;
					clear: both;
					font-size: 40px;
					font-weight: 900;
					width: 100%;
					color: #fff;
					-webkit-transition: all 0.6s ease;
					-moz-transition: all 0.6s ease;
					-o-transition: all 0.6s ease;
					-ms-transition: all 0.6s ease;
					transition: all 0.6s ease;
					font-family: 'Montserrat', sans-serif;
					text-shadow: 2px 2px #333;
				}

				.sticky-post-first-in-category h2  {
					font-family: 'Montserrat', sans-serif;
					font-weight: 900;
					font-size: 20px;
				}

				.s-red:hover {
					background-color: red !important;
				}

				.sticky-post-first-in-category .btn {
					text-align: center;
					line-height: 80px;
				}

				.sticky-post-first-in-category  a {
					text-decoration: none;
				}

				.sticky-post-first-in-category h1 {
					font-size: 44px;
					padding: 30px 0 30px 0;
					text-align: center;
				}
				
				.sticky-post-first-in-category .ss-logo {
					position: relative;
					top: 12px;
					width: 60px;
					height: 60px;
				}
			</style>
	<?php } add_action('admin_head', 'sticky_post_first_in_category_admin_options_css'); 