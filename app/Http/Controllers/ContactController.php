<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contact = Contact::where('is_active', true)
            ->first();
            
        return view('kontak', compact('contact'));
    }
}