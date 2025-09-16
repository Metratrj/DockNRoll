<?php

/*
 * Copyright (c) 2025.
 */

namespace App\Views\components;

abstract class Component
{
    protected $attributes = [];
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes; } abstract public function render(); protected function buildAttributes() {
$html = ''; foreach ($this->attributes as $key => $value) { $html .= " $key=\"" . htmlspecialchars($value) . "\""; }
return $html; } }
