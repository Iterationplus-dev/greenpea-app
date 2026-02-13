<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function send(ContactRequest $request)
    {
        Mail::raw(
            "Name: {$request->name}\nEmail: {$request->email}\nSubject: {$request->subject}\n\n{$request->message}",
            function ($mail) use ($request) {
                $mail->to(config('mail.from.address'))
                    ->replyTo($request->email, $request->name)
                    ->subject('Contact Form: '.$request->subject);
            }
        );

        return back()->with('success', 'Thank you for reaching out! We\'ll get back to you within 24 hours.');
    }
}
