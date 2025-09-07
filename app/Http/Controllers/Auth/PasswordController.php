<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // Rate limiting untuk password changes
        $key = 'password-change.' . $request->user()->id;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'current_password' => ["Too many password change attempts. Please try again in {$seconds} seconds."],
            ]);
        }

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(), // Check against leaked passwords
                'different:current_password'
            ],
        ], [
            'current_password.required' => 'Current password is required.',
            'current_password.current_password' => 'The current password is incorrect.',
            'password.required' => 'New password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.different' => 'New password must be different from current password.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.mixed_case' => 'Password must contain both uppercase and lowercase letters.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one symbol.',
            'password.uncompromised' => 'This password has appeared in a data breach. Please choose a different password.',
        ]);

        $user = $request->user();

        // Additional security check: Prevent reusing recent passwords
        if ($this->isPasswordRecentlyUsed($user, $validated['password'])) {
            RateLimiter::hit($key, 300); // 5 minutes lockout
            throw ValidationException::withMessages([
                'password' => ['This password was recently used. Please choose a different password.'],
            ]);
        }

        // Update password with tracking
        $user->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now(),
        ]);

        // Store password history (optional - for preventing reuse)
        $this->storePasswordHistory($user, $validated['password']);

        // Clear rate limiting
        RateLimiter::clear($key);

        // Log password change for security audit
        $this->logPasswordChange($user, $request);

        return back()->with('status', 'Password updated successfully! Your account is now more secure.');
    }

    /**
     * Check if password was recently used (placeholder implementation).
     * You can implement password history table if needed.
     */
    private function isPasswordRecentlyUsed($user, $password): bool
    {
        // Simple check: don't allow same as current password
        return Hash::check($password, $user->password);

        // For full implementation, you'd check against a password_history table:
        // return PasswordHistory::where('user_id', $user->id)
        //     ->latest()
        //     ->take(5) // Check last 5 passwords
        //     ->get()
        //     ->contains(function ($history) use ($password) {
        //         return Hash::check($password, $history->password_hash);
        //     });
    }

    /**
     * Store password in history for future checks.
     */
    private function storePasswordHistory($user, $password): void
    {
        // Placeholder implementation
        // You can create a password_history table if you want to prevent password reuse

        // PasswordHistory::create([
        //     'user_id' => $user->id,
        //     'password_hash' => Hash::make($password),
        //     'created_at' => now(),
        // ]);

        // Keep only last 5 passwords
        // PasswordHistory::where('user_id', $user->id)
        //     ->oldest()
        //     ->skip(5)
        //     ->delete();
    }

    /**
     * Log password change for security audit.
     */
    private function logPasswordChange($user, $request): void
    {
        // Log to Laravel log or dedicated security log
        \Log::info('Password changed', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()->toISOString(),
        ]);

        // You could also create a security_logs table:
        // SecurityLog::create([
        //     'user_id' => $user->id,
        //     'action' => 'password_changed',
        //     'ip_address' => $request->ip(),
        //     'user_agent' => $request->userAgent(),
        //     'details' => json_encode([
        //         'previous_password_age' => $user->password_age,
        //     ]),
        //     'created_at' => now(),
        // ]);
    }

    /**
     * Get password strength score (AJAX endpoint).
     */
    public function checkStrength(Request $request)
    {
        $password = $request->input('password');

        if (!$password) {
            return response()->json([
                'strength' => 0,
                'level' => 'very_weak',
                'feedback' => ['Password is required']
            ]);
        }

        $feedback = [];
        $score = 0;

        // Length checks
        if (strlen($password) >= 8) {
            $score += 2;
        } else {
            $feedback[] = 'Use at least 8 characters';
        }

        if (strlen($password) >= 12) {
            $score += 1;
        } else if (strlen($password) >= 8) {
            $feedback[] = 'Consider using 12+ characters for better security';
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
            $score += 2;
        } else {
            $feedback[] = 'Add special characters (!@#$%^&*)';
        }

        // Pattern checks
        if (preg_match('/(.)\1{2,}/', $password)) {
            $feedback[] = 'Avoid repeated characters (aaa, 111)';
            $score = max(0, $score - 1);
        }

        if (preg_match('/123|abc|qwe|password|admin|user/i', $password)) {
            $feedback[] = 'Avoid common patterns and words';
            $score = max(0, $score - 2);
        }

        // Sequential characters check
        if (preg_match('/012|123|234|345|456|567|678|789|890|abc|bcd|cde|def/i', $password)) {
            $feedback[] = 'Avoid sequential characters';
            $score = max(0, $score - 1);
        }

        // Determine strength level
        $level = 'very_weak';
        if ($score >= 7) {
            $level = 'very_strong';
        } elseif ($score >= 6) {
            $level = 'strong';
        } elseif ($score >= 4) {
            $level = 'medium';
        } elseif ($score >= 2) {
            $level = 'weak';
        }

        // Add positive feedback for strong passwords
        if ($score >= 6) {
            $feedback = ['Excellent password strength!'];
        } elseif ($score >= 4) {
            $feedback[] = 'Good password! Consider adding more characters for extra security.';
        }

        return response()->json([
            'strength' => min($score, 8),
            'level' => $level,
            'feedback' => $feedback,
            'progress' => min(($score / 8) * 100, 100), // Percentage for progress bar
        ]);
    }

    /**
     * Generate a secure password suggestion (AJAX endpoint).
     */
    public function generatePassword(Request $request)
    {
        $length = $request->input('length', 16);
        $length = max(12, min(32, $length)); // Ensure reasonable length

        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';

        // Ensure at least one character from each type
        $password = '';
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];

        // Fill the rest randomly
        $allChars = $lowercase . $uppercase . $numbers . $symbols;
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Shuffle the password
        $password = str_shuffle($password);

        return response()->json([
            'password' => $password,
            'strength' => $this->checkStrength(new Request(['password' => $password]))->getData(),
        ]);
    }
}
