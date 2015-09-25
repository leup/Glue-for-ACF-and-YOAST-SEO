<?php
/*
Plugin Name: YOAST/ACF Glue
Plugin URI:
Description:
Author: Paul BERNARD @ GLASSHOUSE
Version: 1.0
Author URI:
*/

include __DIR__ . '/fields/field.php';

add_action("init",	"yoast_acf_glue", 10, 1);
function yoast_acf_glue()
{
    
    include __DIR__ . '/fields/text.php';

    do_action('yoast-acf-glue/include');

    add_filter('wpseo_pre_analysis_post_content', 'yoast_acf_add_content_to_yoast', 10, 2);

}

// Inspired by WordPress SEO - ACF Content Analysis
function yoast_acf_add_content_to_yoast( $content )
{
    global $post, $typenow;
	$pid = isset($_GET['post']) ? $_GET['post'] : $post->ID;

//    var_dump(get_fields($pid));
    // get field groups
    $field_groups = acf_get_field_groups();

    $seo_fields = array();

    if( !empty($field_groups) ) {

        $args = array(
            'post_id'	=> $post->ID,
            'post_type'	=> $typenow
        );

        foreach( $field_groups as $i => $field_group ) {


            $seo_fields[$field_group['ID']] = array();
            // visibility
            $visibility = acf_get_field_group_visibility( $field_group, $args);

            if ($visibility) {
                $group_fields = acf_get_fields($field_group);
                if (!empty($group_fields && is_array($group_fields))) {
                    foreach ($group_fields as $gfield) {
//                        var_dump($gfield);
                        if (!empty($gfield['yoast_analysis']) && $gfield['yoast_analysis'][0] === 'yes') {
                            $seo_fields[$field_group['ID']][$gfield['name']] = $gfield['type'];
                        }
                    }
                }
            }

            if (empty($seo_fields[$field_group['ID']])) {
                unset($seo_fields[$field_group['ID']]);
            }
        }
    }

    if (!empty($seo_fields)) {
        var_dump($seo_fields);
        $fields = get_fields($pid);
    }

//    var_dump(get_fields($pid));
    
	return $content;
}