<?php
/*
Plugin Name: Jeba cute Tab
Plugin URI: http://prowpexpert.com/jeba-cute-tab
Description: This is Jeba cute wordpress tab plugin really looking awesome. Everyone can use the cute tab plugin easily like other wordpress plugin. Here everyone can use tab from post, page or other custom post. This plugin also working toggle when show in small screen. By using [jeba_tab] shortcode use the tab every where post, page and template.
Author: Md Jahed
Version: 1.0
Author URI: http://prowpexpert.com/
*/
function jeba_tab_wp_latest_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'jeba_tab_wp_latest_jquery');

function plugin_function_jeba_tab() {
    wp_enqueue_script( 'jeba-tab-js', plugins_url( '/js/easyResponsiveTabs.js', __FILE__ ), true);
    wp_enqueue_style( 'jeba-tab-css', plugins_url( '/js/easy-responsive-tabs.css', __FILE__ ));
}

add_action('init','plugin_function_jeba_tab');
function jeba_tab_script_function () {?>
	    <style type="text/css">
        .demo {
            margin: 0px auto;
        }
        .demo h1 {
                margin:33px 0 25px;
            }
        .demo h3 {
                margin: 10px 0;
            }
        pre {
            background: #fff;
        }
        @media only screen and (max-width: 780px) {
        .demo {
                margin: 5%;
                width: 90%;
         }
        .how-use {
                float: left;
                width: 300px;
                display: none;
            }
        }
        #tabInfo {
            display: none;
        }
    </style>
	

<?php
}
add_action('wp_head','jeba_tab_script_function');


function tab_list_shortcode($atts){
	extract( shortcode_atts( array(
		'category' => '',
		'post_type' => '',
	), $atts, 'tablist' ) );
	 
		$q = new WP_Query(
			array('posts_per_page' => -1, 'post_type' => $post_type, 'tab_cat' => $category)
		);
	 
		$list = '<ul class="resp-tabs-list">';
	while($q->have_posts()) : $q->the_post();
	 
		$list .= '
		 <li>'.get_the_title().'</li>
		
		';
	endwhile;
		$list.= '</ul>';
		wp_reset_query();
	return $list;
}
add_shortcode('tablist', 'tab_list_shortcode');

function tab_content_shortcode($atts){
	extract( shortcode_atts( array(
		'category' => '',
		'post_type' => '',
	), $atts, 'tabcontent' ) );
	 
	$q = new WP_Query(
		array('posts_per_page' => -1, 'post_type' => $post_type, 'tab_cat' => $category)
	);
	 
	$list = '<div class="resp-tabs-container">';
		while($q->have_posts()) : $q->the_post();
	 
	$list .= '
		<div>'.get_the_content().'</div>';
		endwhile;
	$list.= '</div>';
		wp_reset_query();
	return $list;
}
add_shortcode('tabcontent', 'tab_content_shortcode');

function tab_main_shortcode($atts, $content = null) {
	extract( shortcode_atts( array(
		'category' => '',
		'post_type' => 'jeba-tab-items',
	), $atts ) );
	 
	return'
	<div class="demo">

        <!--Horizontal Tab-->
        <div id="horizontalTab">
            '.do_shortcode('[tablist post_type="'.$post_type.'" category="'.$category.'"] [tabcontent post_type="'.$post_type.'" category="'.$category.'"]').'
        </div>
        <br />

        <div id="tabInfo">
            Selected tab: <span class="tabName"></span>
        </div>
    </div>
	';
}
add_shortcode('jeba_tab', 'tab_main_shortcode');

add_action( 'init', 'jeba_tab_custom_post' );
function jeba_tab_custom_post() {

	register_post_type( 'jeba-tab-items',
		array(
			'labels' => array(
				'name' => __( 'JebaTabs' ),
				'singular_name' => __( 'JebaTab' )
			),
			'public' => true,
			'supports' => array('title', 'editor', 'thumbnail'),
			'has_archive' => true,
			'rewrite' => array('slug' => 'jeba-tab'),
			'taxonomies' => array('category', 'post_tag') 
		)
	);	
}
function jeba_tab_plugin_function () {?>
        <script type="text/javascript">
    jQuery(document).ready(function ($) {
        jQuery('#horizontalTab').easyResponsiveTabs({
            type: 'default',       
            width: 'auto',
            fit: true,  
            closed: 'accordion', 
            activate: function(event) { 
                var $tab = $(this);
                var $info = $('#tabInfo');
                var $name = $('span', $info);

                $name.text($tab.text());

                $info.show();
            }
        });

        jQuery('#verticalTab').easyResponsiveTabs({
            type: 'vertical',
            width: 'auto',
            fit: true
        });
    });
</script>
		
<?php
}
add_action('wp_footer','jeba_tab_plugin_function');

 
// Hooks your functions into the correct filters
function jeba_add_tab_mce_button() {
// check user permissions
if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
return;
}
// check if WYSIWYG is enabled
if ( 'true' == get_user_option( 'rich_editing' ) ) {
add_filter( 'mce_external_plugins', 'jeba_add_tab_tinymce_plugin' );
add_filter( 'mce_buttons', 'jeba_register_tab_mce_button' );
}
}
add_action('admin_head', 'jeba_add_tab_mce_button');
 
// Declare script for new button
function jeba_add_tab_tinymce_plugin( $plugin_array ) {
$plugin_array['jeba_tab_button'] = plugins_url('/js/tinymce-button.js', __FILE__ );
return $plugin_array;
}
 
// Register new button in the editor
function jeba_register_tab_mce_button( $buttons ) {
array_push( $buttons, 'jeba_tab_button' );
return $buttons;
}

?>