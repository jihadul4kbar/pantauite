<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

/**
 * Simple Math CAPTCHA Service
 * Generates simple math questions (addition/subtraction) to verify human users
 */
class CaptchaService
{
    /**
     * Generate a new CAPTCHA question and store answer in session
     */
    public static function generate(): array
    {
        $num1 = random_int(1, 10);
        $num2 = random_int(1, 10);
        $operator = random_int(0, 1) === 0 ? '+' : '-';
        
        // Ensure result is always positive
        if ($operator === '-' && $num1 < $num2) {
            [$num1, $num2] = [$num2, $num1];
        }
        
        $answer = $operator === '+' ? $num1 + $num2 : $num1 - $num2;
        $question = "$num1 $operator $num2 = ?";
        
        // Store answer in session with expiry (5 minutes)
        Session::put('captcha_answer', [
            'answer' => $answer,
            'expires' => now()->addMinutes(5)->timestamp,
        ]);
        
        return [
            'question' => $question,
        ];
    }
    
    /**
     * Validate CAPTCHA answer
     */
    public static function validate(?string $userAnswer): bool
    {
        if ($userAnswer === null || $userAnswer === '') {
            return false;
        }
        
        $captcha = Session::get('captcha_answer');
        
        if (!$captcha) {
            return false;
        }
        
        // Check if expired
        if (now()->timestamp > $captcha['expires']) {
            Session::forget('captcha_answer');
            return false;
        }
        
        // Clear after validation attempt
        Session::forget('captcha_answer');
        
        return (int) $userAnswer === $captcha['answer'];
    }
    
    /**
     * Get validation error messages
     */
    public static function getErrorMessage(): string
    {
        return 'Jawaban CAPTCHA tidak benar. Silakan coba lagi.';
    }
}
