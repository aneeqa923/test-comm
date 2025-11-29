<?php
/**
 * Security Helper Functions
 * 
 * This file contains reusable security functions for the e-commerce website.
 * These functions help prevent CSRF, XSS, and other common vulnerabilities.
 */

// ============================================
// CSRF TOKEN FUNCTIONS
// ============================================

/**
 * Generate a CSRF token and store it in the session
 * @return string The generated CSRF token
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify a CSRF token
 * @param string $token The token to verify
 * @return bool True if valid, false otherwise
 */
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// ============================================
// OUTPUT SANITIZATION (XSS PREVENTION)
// ============================================

/**
 * Escape output for safe HTML display
 * @param string $string The string to escape
 * @return string The escaped string
 */
function escape_output($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// ============================================
// INPUT VALIDATION
// ============================================

/**
 * Validate and sanitize an integer input
 * @param mixed $value The value to validate
 * @param int $min Minimum allowed value (optional)
 * @param int $max Maximum allowed value (optional)
 * @return int|false The validated integer or false if invalid
 */
function validate_integer($value, $min = null, $max = null) {
    // Check if value is numeric
    if (!is_numeric($value)) {
        return false;
    }
    
    $int_value = (int)$value;
    
    // Check minimum value
    if ($min !== null && $int_value < $min) {
        return false;
    }
    
    // Check maximum value
    if ($max !== null && $int_value > $max) {
        return false;
    }
    
    return $int_value;
}

/**
 * Validate email address
 * @param string $email The email to validate
 * @return string|false The validated email or false if invalid
 */
function validate_email($email) {
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return $email;
    }
    return false;
}

// ============================================
// RATE LIMITING HELPERS
// ============================================

/**
 * Get client IP address
 * @return string The client IP address
 */
function get_client_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

/**
 * Check if rate limit is exceeded
 * @param string $key Unique identifier (e.g., 'login_' . $email)
 * @param int $max_attempts Maximum number of attempts allowed
 * @param int $time_window Time window in seconds
 * @return array ['allowed' => bool, 'attempts' => int, 'reset_time' => int]
 */
function check_rate_limit($key, $max_attempts = 5, $time_window = 900) {
    $rate_key = 'rate_limit_' . $key;
    
    if (!isset($_SESSION[$rate_key])) {
        $_SESSION[$rate_key] = [
            'attempts' => 0,
            'first_attempt' => time()
        ];
    }
    
    $rate_data = $_SESSION[$rate_key];
    $current_time = time();
    $time_passed = $current_time - $rate_data['first_attempt'];
    
    // Reset if time window has passed
    if ($time_passed > $time_window) {
        $_SESSION[$rate_key] = [
            'attempts' => 0,
            'first_attempt' => $current_time
        ];
        return [
            'allowed' => true,
            'attempts' => 0,
            'reset_time' => $current_time + $time_window
        ];
    }
    
    // Check if limit exceeded
    if ($rate_data['attempts'] >= $max_attempts) {
        return [
            'allowed' => false,
            'attempts' => $rate_data['attempts'],
            'reset_time' => $rate_data['first_attempt'] + $time_window
        ];
    }
    
    return [
        'allowed' => true,
        'attempts' => $rate_data['attempts'],
        'reset_time' => $rate_data['first_attempt'] + $time_window
    ];
}

/**
 * Increment rate limit counter
 * @param string $key Unique identifier
 */
function increment_rate_limit($key) {
    $rate_key = 'rate_limit_' . $key;
    if (!isset($_SESSION[$rate_key])) {
        $_SESSION[$rate_key] = [
            'attempts' => 1,
            'first_attempt' => time()
        ];
    } else {
        $_SESSION[$rate_key]['attempts']++;
    }
}

/**
 * Reset rate limit counter
 * @param string $key Unique identifier
 */
function reset_rate_limit($key) {
    $rate_key = 'rate_limit_' . $key;
    unset($_SESSION[$rate_key]);
}

?>
