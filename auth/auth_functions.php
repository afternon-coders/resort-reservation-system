<?php
// Session utilities for authentication

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isStaff() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'staff';
}

function isGuest() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'guest';
}

function getCurrentUser() {
    return [
        'user_id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'role' => $_SESSION['role'] ?? null,
    ];
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . dirname(__DIR__) . '/auth/login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('HTTP/1.1 403 Forbidden');
        exit('Access denied: Admin only');
    }
}

function requireStaff() {
    if (!isStaff() && !isAdmin()) {
        header('HTTP/1.1 403 Forbidden');
        exit('Access denied: Staff only');
    }
}
