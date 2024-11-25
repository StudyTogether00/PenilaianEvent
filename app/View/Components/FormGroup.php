<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormGroup extends Component
{
    public $type;
    public $label;
    public $class;

    public function __construct($type = "text", $label = "", $class = "")
    {
        $this->type = $type;
        $this->label = $label;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.formgroup');
    }
}
