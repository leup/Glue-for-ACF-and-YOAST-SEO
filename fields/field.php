<?php


abstract class YOASTACFGlue_Field
{
    var $name;

    public function __construct()
    {
        add_action( "acf/render_field_settings/type={$this->name}",	array($this, 'render_field_settings'), 11, 1 );
        add_filter( "acf/prepare_field/type={$this->name}", array($this, 'prepare_field'), 11, 1 );
    }

    public function render_field_settings( $field )
    {
        // default_value
		acf_render_field_setting( $field, array(
			'label'			=> __('Import content to YOAST','yoast-acf-glue'),
			'instructions'	=> __('Check this box for Yoast SEO to parse this field for content analysis','yoast-acf-glue'),
			'type'			=> 'checkbox',
			'name'			=> 'yoast_analysis',
            'choices'		=> array(
				'yes'		=> __("Yes",'acf'),
			),
		));
    }

    public function prepare_field( $field )
    {
        if (!empty($field['yoast_analysis']) && $field['yoast_analysis'][0] === 'yes') {
            $field['class'] .= ' yoast-acf ';
        }

        return $field;
    }
}
