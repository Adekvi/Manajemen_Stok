<?php

namespace App\View\Components\view\layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class app extends Component
{
    public $title = 'Dashboard';

    /**
     * Create a new component instance.
     */
    public function __construct($title = 'Dashboard')
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.view.layout.app');
    }
}
