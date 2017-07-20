<?php
/**
 * Plugin Name: WP-Rest API Starter
 * Description: simple Wp-Rest API starter in plugin form for dev/messing with
 * Version: 0.1
 * Author: Chris Cline
 * Author URI: http://www.christiancline.com
 */

// Exit if this wasn't accessed via WordPress (aka via direct access)
if (!defined('ABSPATH')) exit;

class wpRestApiLoadPosts {
    public function __construct() {
        //add scripts
        add_action('wp_enqueue_scripts', array($this,'enqueue'));

        //add the shortcode
        add_shortcode('wp_rest_api_posts', array($this,'shortcode'));

        // add featured image to JSON via registered field
        add_action('rest_api_init', array($this,'featured'));

    }

    // shortcode function
    public function shortcode($atts) {
        //add atts as needed
        // $myClass = isset($atts['class'])  ? $atts['class']  : 'red';

         return '<button id="show-btn">Show Posts</button>
         <div id="posts-wrap" class="row vis"></div>';

    }

    public function featured(){
        //Add featured image to post
        register_rest_field( 'post',
            'my_featured_image_src', //NAME OF THE NEW FIELD TO BE ADDED - you can call this anything
            array(
                'get_callback'    => array( $this, 'get_image_src' ),
                'update_callback' => null,
                'schema'          => null,
                 )
            );

    }
    //callback for featured function above
    public function get_image_src( $object, $field_name, $request ){
        $feat_img_array = wp_get_attachment_image_src($object['featured_media'], 'thumbnail', true);
        return $feat_img_array[0];
    }

    // enqueue function
    public function enqueue() {
        //plugin's stylesheet - if needed
        wp_enqueue_style('test-numbers', plugins_url('css/wp-rest-api-posts.css', __FILE__), null, '1.0');

        // main rest api script
        wp_enqueue_script('wp-rest-api-main', plugins_url('js/main.js', __FILE__), null, '1.0', true);

    }

}
// Let's do this thing!
$restApiloadPosts = new wpRestApiLoadPosts();
