<?php


abstract class YOASTACFGlue_Field
{
    var $name;

    public function __construct()
    {
        add_action("acf/render_field_settings/type={$this->name}",	array($this, 'render_field_settings'), 11, 1);
    }

    abstract public function render_field_settings( $field );
}
