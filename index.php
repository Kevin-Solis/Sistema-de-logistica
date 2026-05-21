<?php
require_once __DIR__ . '/auth.php';

// Send authenticated users directly to the main panel.
if (current_user() !== null) {
    header('Location: panel.php');
    exit;
}

// Guests start at the login form.
header('Location: login.php');
exit;
