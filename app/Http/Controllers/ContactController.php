<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(){
        return view('user.contact');
    }
    
    public function sendContactForm(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);
    
        Mail::to('ralphdaher6@gmail.com')->send(new ContactFormMail($validatedData));
    
        return redirect()->back()->with('success', 'Message sent successfully!');
    }
}
