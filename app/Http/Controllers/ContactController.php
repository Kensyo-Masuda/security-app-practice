<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('contact.form');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $validated['message'] = Purifier::clean($validated['message']);

        return view('contact.thanks', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contactMessage' => $validated['message'],
        ]);
    }
}
