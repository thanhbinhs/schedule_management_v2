<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SessionTime extends Component
{
    public $sessionNumber;

    /**
     * Create a new component instance.
     *
     * @param int $sessionNumber
     * @return void
     */
    public function __construct($sessionNumber)
    {
        $this->sessionNumber = $sessionNumber;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.session-time');
    }
}
