<?php


class YOASTACFGlue_TextField extends YOASTACFGlue_Field
{

    public function __construct()
    {
        $this->name = 'text';
        parent::__construct();
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

}

new YOASTACFGlue_TextField();