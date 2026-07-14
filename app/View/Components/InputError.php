<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputError extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $name;
    public $messages;

    public function __construct($name = null, $messages = [])
    {
        $this->name = $name;
        $this->messages = $messages;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input-error');
    }
}
