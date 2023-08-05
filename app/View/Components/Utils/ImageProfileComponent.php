<?php

namespace App\View\Components\Utils;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImageProfileComponent extends Component
{
    public $item;

    /**
     * Create a new component instance.
     */
    public function __construct($item = null)
    {
        //
        $this->item = $item;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.utils.image-profile-component');
    }
}
