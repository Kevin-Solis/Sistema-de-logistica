<?php
require_once __DIR__ . '/auth.php';

// Close the session and return to the login screen.
logout_user();
header('Location: login.php');
exit;
