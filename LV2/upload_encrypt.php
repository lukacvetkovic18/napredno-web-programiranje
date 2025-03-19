<?php
require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function encrypt_file($filePath) {
    $firstKey = base64_decode($_ENV['FIRST_KEY']);
    $secondKey = base64_decode($_ENV['SECOND_KEY']);
    $method = "aes-256-cbc";
    $iv_length = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($iv_length);

    $fileContent = file_get_contents($filePath);
    $firstEncrypted = openssl_encrypt($fileContent, $method, $firstKey, OPENSSL_RAW_DATA, $iv);
    $secondEncrypted = hash_hmac('sha3-512', $firstEncrypted, $secondKey, true);

    return base64_encode($iv . $secondEncrypted . $firstEncrypted);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document'])) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($_FILES['document']['name']);
    $targetFilePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['document']['tmp_name'], $targetFilePath)) {
        $encryptedData = encrypt_file($targetFilePath);

        file_put_contents($targetFilePath . '.enc', $encryptedData);
        unlink($targetFilePath);

        echo "Dokument uspješno kriptiran i pohranjen.";
    } else {
        echo "Greška pri uploadu dokumenta.";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <label for="document">Odaberite dokument:</label>
    <input type="file" name="document" id="document">
    <button type="submit">Upload</button>
</form>