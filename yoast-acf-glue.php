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
    if (is_admin()) {

        include __DIR__ . '/fields/text.php';

        do_action('yoast-acf-glue/include');

        add_filter('wpseo_pre_analysis_post_content', 'yoast_acf_add_content_to_yoast', 10, 2);

    }

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
            // visibility
            $visibility = acf_get_field_group_visibility( $field_group, $args);

            if ($visibility) {
                $group_fields = acf_get_fields($field_group);
                if (!empty($group_fields && is_array($group_fields))) {
                    scan_prepare_fields($field_group, $group_fields, $seo_fields);
                }
            }
        }
    }

    if (!empty($seo_fields)) {
//        var_dump($seo_fields);
        $fields = get_fields($pid);
//        var_dump($fields);

        foreach($fields as $key => $field) {
            scan_add_field($key, $field, $fields, $seo_fields, $content);
        }
    }

//    remove_filter('wpseo_pre_analysis_post_content', 'yoast_acf_add_content_to_yoast', 10);
    
	return $content;
}

function scan_prepare_fields($parent, $fields, &$seo_fields = array())
{
    $tmp = array();

    foreach ($fields as $gfield) {
//        var_dump($gfield);
        if (!empty($gfield['yoast_analysis']) && $gfield['yoast_analysis'][0] === 'yes') {
            $tmp[$gfield['name']] = $gfield['type'];
        }

        if (!empty($gfield['sub_fields'])) {
            scan_prepare_fields($gfield, $gfield['sub_fields'], $tmp);
        }
        else if (!empty($gfield['layouts'])) {
            foreach ($gfield['layouts'] as $layout) {
//                scan_prepare_fields($layout, $gfield['sub_fields'], $tmp);
                if (!empty($layout['sub_fields'])) {
                    scan_prepare_fields($gfield, $layout['sub_fields'], $tmp);
                }
            }
        }
    }

    if (!empty($tmp)) {
        if (isset($parent['name'])) {
            $seo_fields[$parent['name']] = $tmp;
        }
        else {
            $seo_fields = $tmp;
        }
    }
}

function scan_add_field($key, $field, $fields, $seo_fields, &$content)
{
    if (!is_array($field) && isset($seo_fields[$key]) && !is_array($seo_fields[$key])) {
        $content .= ' ' . $field;
    }

    if (!empty($field) && is_array($field) && !empty($seo_fields[$key])) {
        foreach ($field as $subfields) {
            foreach($subfields as $subkey => $subfield) {
                scan_add_field($subkey, $subfield, $subfields, $seo_fields[$key], $content);
            }
        }
    }

//    var_dump($key, $field);
}