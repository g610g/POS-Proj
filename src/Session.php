<?php
namespace App;

class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => 86400, // 1 day
                'cookie_httponly' => true,  // prevent JS access
                'cookie_secure' => isset($_SERVER['HTTPS']), // secure only if HTTPS
                'use_strict_mode' => true,
            ]);
        }
    }

    public static function set(string $key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key) {
        return $_SESSION[$key] ?? null;
    }
    public static function has (string $key){
        return isset($_SESSION[$key]);
    }
    public static function unset(string $key){
        unset($_SESSION[$key]);
    }
    public static function destroy() {

        if (session_status() !== PHP_SESSION_NONE) {
            session_unset();
            session_destroy();
        }
    }
}
