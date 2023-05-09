<?php

namespace App\View\Components\Modal;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CreatePatientComponent extends Component
{
    public $title;
    public $modalClass;

    /**
     * Create a new component instance.
     */
    public function __construct($title, $modalClass)
    {
        $this->title = $title;
        $this->modalClass = $modalClass;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal.create-patient-component');
    }
}
