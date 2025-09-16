<?php

/*
 * Copyright (c) 2025.
 */

namespace App\Views\components;

class Button extends Component
{
    private $label;

    public function __construct($label, $attributes = [])
    {
        parent::__construct($attributes);
        $this->label = $label;
    }

    public function render()
    {
        $attr = $this->buildAttributes();
        return "<button{$attr}>{$this->label}</button>";
    }
}
