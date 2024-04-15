<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Set a success flash message.
     *
     * @param string $message
     * @return void
     */
    public function setSuccessMessage($message)
    {
        // Flash a success message with the provided message
        session()->flash('message', $message);
        session()->flash('type','success');

    }

    /**
     * Set an error flash message.
     *
     * @param string $message
     * @return void
     */
    public function setErrorMessage($message)
    {
        // Flash an error message with the provided message
        session()->flash('message', $message);
        session()->flash('type','danger');

    }
}
