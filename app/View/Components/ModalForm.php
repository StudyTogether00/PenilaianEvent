<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ModalForm extends Component
{
    public $id;
    public $title;
    public $zIndex;

    public function __construct($id, $title = "", $zIndex = 1050)
    {
        $this->id = $id;
        $this->title = $title;
        $this->zIndex = $zIndex;
    }

    public function render()
    {
        return view('components.modalform');
    }
}
