<?php
require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function decrypt_file($encryptedData) {
    $firstKey = base64_decode($_ENV['FIRST_KEY']);
    $secondKey = base64_decode($_ENV['SECOND_KEY']);
    $mix = base64_decode($encryptedData);
    $method = "aes-256-cbc";
    $iv_length = openssl_cipher_iv_length($method);

    $iv = substr($mix, 0, $iv_length);
    $secondEncrypted = substr($mix, $iv_length, 64);
    $firstEncrypted = substr($mix, $iv_length + 64);

    $data = openssl_decrypt($firstEncrypted, $method, $firstKey, OPENSSL_RAW_DATA, $iv);
    if (hash_equals($secondEncrypted, hash_hmac('sha3-512', $firstEncrypted, $secondKey, true))) {
        return $data;
    }
    return false;
}

$uploadDir = 'uploads/';
$files = glob($uploadDir . '*.enc');

foreach ($files as $file) {
    $encryptedData = file_get_contents($file);
    $decryptedContent = decrypt_file($encryptedData);

    if ($decryptedContent !== false) {
        file_put_contents(str_replace('.enc', '', $file), $decryptedContent);

        echo "<a href='" . str_replace('.enc', '', 'uploads/' . basename($file)) . "'>Preuzmi dokument</a><br>";
    } else {
        echo "Greška pri dekriptiranju datoteke: " . basename($file) . "<br>";
    }
}
?>
