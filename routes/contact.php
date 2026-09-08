<?php

declare(strict_types=1);

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

Route::get('/contact', fn () => Inertia::render('contact'))->name('contact');

Route::post('/contact', function (Request $request): RedirectResponse {
    $validated = $request->validate([
        'name' => ['required', 'string', 'min:2', 'max:120'],
        'email' => ['required', 'email:rfc', 'max:255'],
        'phone' => ['nullable', 'string', 'max:40'],
        'company' => ['nullable', 'string', 'max:160'],
        'country' => ['nullable', 'string', 'max:100'],
        'reason' => ['required', Rule::in(['general', 'cybersecurity', 'tscm', 'forensics', 'privacy', 'partnership', 'support', 'other'])],
        'preferred_contact' => ['required', Rule::in(['email', 'phone', 'whatsapp'])],
        'message' => ['required', 'string', 'min:10', 'max:5000'],
        'website' => ['nullable', 'string', 'max:255'],
    ]);

    // Honeypot: silently accept bot submissions without persisting them.
    if (filled($validated['website'] ?? null)) {
        return back()->with('contact_success', true);
    }

    DB::table('contact_requests')->insert([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'company' => $validated['company'] ?? null,
        'country' => $validated['country'] ?? null,
        'reason' => $validated['reason'],
        'preferred_contact' => $validated['preferred_contact'],
        'message' => $validated['message'],
        'locale' => (string) $request->session()->get('locale', app()->getLocale()),
        'status' => 'new',
        'ip_hash' => $request->ip() ? hash('sha256', (string) $request->ip()) : null,
        'user_agent_hash' => $request->userAgent() ? hash('sha256', (string) $request->userAgent()) : null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('contact_success', true);
})->middleware('throttle:10,1')->name('contact.submit');
