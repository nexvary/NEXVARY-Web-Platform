<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

final class ContactServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware('web')->group(function (): void {
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
                    'website' => ['nullable', 'size:0'],
                ]);

                DB::table('contact_requests')->insert([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'company' => $validated['company'] ?? null,
                    'country' => $validated['country'] ?? null,
                    'reason' => $validated['reason'],
                    'preferred_contact' => $validated['preferred_contact'],
                    'message' => $validated['message'],
                    'locale' => app()->getLocale(),
                    'status' => 'new',
                    'ip_hash' => $request->ip() ? hash_hmac('sha256', $request->ip(), (string) config('app.key')) : null,
                    'user_agent_hash' => $request->userAgent() ? hash_hmac('sha256', $request->userAgent(), (string) config('app.key')) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return back()->with('contact_success', true);
            })->middleware('throttle:5,1')->name('contact.submit');
        });

        Route::prefix(config('nexvary.admin_prefix'))
            ->middleware(['web', 'auth', 'verified', 'admin', 'throttle:admin', 'audit.admin'])
            ->group(function (): void {
                Route::get('/contacts', function () {
                    $requests = DB::table('contact_requests')
                        ->latest('created_at')
                        ->limit(200)
                        ->get(['id', 'name', 'email', 'phone', 'company', 'country', 'reason', 'preferred_contact', 'message', 'status', 'created_at']);

                    return Inertia::render('admin/contacts', ['requests' => $requests]);
                })->name('admin.contacts');

                Route::post('/contacts/{contact}/read', function (int $contact): RedirectResponse {
                    DB::table('contact_requests')->where('id', $contact)->update([
                        'status' => 'read',
                        'read_at' => now(),
                        'updated_at' => now(),
                    ]);

                    return back();
                })->name('admin.contacts.read');
            });
    }
}
