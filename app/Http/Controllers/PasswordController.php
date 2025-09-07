<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PasswordController extends Controller
{
    /**
     * Show the change password form.
     */
    public function edit()
    {
        return Inertia::render('Profile/ChangePassword', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'different:current_password'
            ],
        ], [
            'current_password.required' => 'Current password is required.',
            'password.required' => 'New password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.different' => 'New password must be different from current password.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.mixed_case' => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one symbol.',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
        ]);

        return redirect()
            ->route('profile.password')
            ->with('success', 'Password updated successfully! Your account is now more secure.');
    }

    /**
     * Check password strength (AJAX endpoint).
     */
    public function checkStrength(Request $request)
    {
        $password = $request->input('password');

        if (!$password) {
            return response()->json(['strength' => 0, 'feedback' => []]);
        }

        $feedback = [];
        $score = 0;

        // Length checks
        if (strlen($password) >= 8) {
            $score += 1;
        } else {
            $feedback[] = 'Use at least 8 characters';
        }

        if (strlen($password) >= 12) {
            $score += 1;
        }

        // Character variety checks
        if (preg_match('/[a-z]/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Add lowercase letters';
        }

        if (preg_match('/[A-Z]/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Add uppercase letters';
        }

        if (preg_match('/[0-9]/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Add numbers';
        }

        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Add special characters';
        }

        // Avoid common patterns
        if (preg_match('/(.)\1{2,}/', $password)) {
            $feedback[] = 'Avoid repeated characters';
            $score = max(0, $score - 1);
        }

        if (preg_match('/123|abc|qwe|password/i', $password)) {
            $feedback[] = 'Avoid common patterns';
            $score = max(0, $score - 1);
        }

        // Determine strength level
        $strengthLevel = 'weak';
        if ($score >= 5) {
            $strengthLevel = 'strong';
        } elseif ($score >= 3) {
            $strengthLevel = 'medium';
        }

        return response()->json([
            'strength' => min($score, 6),
            'level' => $strengthLevel,
            'feedback' => $feedback,
        ]);
    }

    /**
     * Generate a secure password suggestion.
     */
    public function generatePassword(Request $request)
    {
        $length = $request->input('length', 12);
        $length = max(8, min(64, $length)); // Ensure reasonable length

        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';

        $allChars = $lowercase . $uppercase . $numbers . $symbols;

        $password = '';

        // Ensure at least one character from each category
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];

        // Fill the rest randomly
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Shuffle the password
        $password = str_shuffle($password);

        return response()->json([
            'password' => $password,
            'strength' => $this->calculatePasswordStrength($password),
        ]);
    }

    /**
     * Calculate password strength for generated passwords.
     */
    private function calculatePasswordStrength($password)
    {
        $score = 0;

        if (strlen($password) >= 8) $score += 1;
        if (strlen($password) >= 12) $score += 1;
        if (preg_match('/[a-z]/', $password)) $score += 1;
        if (preg_match('/[A-Z]/', $password)) $score += 1;
        if (preg_match('/[0-9]/', $password)) $score += 1;
        if (preg_match('/[^A-Za-z0-9]/', $password)) $score += 1;

        return min($score, 6);
    }
}
