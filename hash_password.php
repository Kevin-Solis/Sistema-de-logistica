<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Password hash generator
|--------------------------------------------------------------------------
|
| This file converts a plain password into a secure hash before saving it
| in SQLite. It is useful when a password needs to be created manually from
| the terminal, for example for an initial admin user.
|
| Important notes:
| - The real password should never be stored in the database.
| - SQLite should only store the value returned by password_hash().
| - Login validation must use password_verify(), because every generated
|   hash includes its own salt and will look different each time.
|
| Terminal example:
|
|   php hash_password.php "admin123"
|
| The generated value can be saved in usuarios.password_hash.
*/

// Creates the secure hash using PHP's recommended password algorithm.
function generate_password_hash(string $plainPassword): string
{
    return password_hash($plainPassword, PASSWORD_DEFAULT);
}

// Reads the password passed as the first terminal argument.
function password_from_cli(array $argv): ?string
{
    if (isset($argv[1]) && trim($argv[1]) !== '') {
        return $argv[1];
    }

    return null;
}

// This script is meant to be used from the terminal, not from the browser.
if (PHP_SAPI === 'cli') {
    $plainPassword = password_from_cli($argv);

    if ($plainPassword === null) {
        echo "Usage:\n";
        echo "  php hash_password.php \"your_password\"\n\n";
        echo "Example:\n";
        echo "  php hash_password.php \"admin123\"\n";
        exit(1);
    }

    echo "Original password: {$plainPassword}\n";
    echo "Secure hash:\n";
    echo generate_password_hash($plainPassword) . "\n";
}
