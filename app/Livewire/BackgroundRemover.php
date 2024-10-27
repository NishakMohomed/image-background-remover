<?php

namespace App\Livewire;

use Livewire\Component;

class BackgroundRemover extends Component
{
    public $image = "";

    public function updated($property)
    {
        if ($property === "image") {
            //TODO: validation
            $name = pathinfo($this->image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $this->image->getClientOriginalExtension();
            $uploadName = $name . '_' . time() . '.' . $extension;

            $uploadPath = $this->image->storeAs('images', $uploadName, 'public');

            $imageId = base64url_encode($uploadPath);
        }
    }

    public function render()
    {
        return view('livewire.background-remover');
    }
}
