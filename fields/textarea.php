<?php


class YOASTACFGlue_TextareaField extends YOASTACFGlue_Field
{

    public function __construct()
    {
        $this->name = 'textarea';
        parent::__construct();
    }

}

new YOASTACFGlue_TextareaField();