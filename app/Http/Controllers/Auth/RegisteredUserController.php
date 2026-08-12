<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the account creation view (admin only).
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming account creation request.
     *
     * The authenticated admin stays logged in as themselves — this creates
     * a teammate's account, it is not a self-registration flow.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'admin',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect(route('dashboard', absolute: false))
            ->with('status', 'Compte créé avec succès.');
    }
}
