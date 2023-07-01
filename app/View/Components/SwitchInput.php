<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SwitchInput extends Component
{
    public $inputName;
    public $inputId;
    public $switchId;
    public $disabled;
    public $active;

    /**
     * Create a new component instance.
     */
    public function __construct(string $inputName, string $inputId, string $switchId, bool $disabled = false, bool $active = false)
    {
        $this->inputName = $inputName;
        $this->inputId = $inputId;
        $this->switchId = $switchId;
        $this->disabled = $disabled;
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.switch-input');
    }
}
