<?php


class YOASTACFGlue_TextField extends YOASTACFGlue_Field
{

    public function __construct()
    {
        $this->name = 'text';
        parent::__construct();
    }

}

new YOASTACFGlue_TextField();