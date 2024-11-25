<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $label;
    public $icon;

    public function __construct($label = "", $icon = "")
    {
        $this->label = $label;
        $this->icon = !empty($icon) ? "<i class=\"{$icon}\"></i>" : "";
    }

    public function render()
    {
        return view('components.button');
    }
}
