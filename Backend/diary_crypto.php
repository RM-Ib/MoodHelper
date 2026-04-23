<?php

function getDiaryEncryptionKey(): string
{
    $key = getenv('APP_SECRET_KEY');

   
    if (!$key) {
        $key = 'CHANGE_THIS_TO_A_LONG_RANDOM_SECRET_KEY_32+_CHARS';
    }

    return hash('sha256', $key, true); // 32 bytes for AES-256
}

function encryptDiaryText(?string $plainText): string
{
    $plainText = (string)($plainText ?? '');

    $key = getDiaryEncryptionKey();
    $cipher = 'AES-256-CBC';
    $ivLength = openssl_cipher_iv_length($cipher);
    $iv = random_bytes($ivLength);

    $ciphertext = openssl_encrypt(
        $plainText,
        $cipher,
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    if ($ciphertext === false) {
        throw new Exception('Encryption failed.');
    }

    return base64_encode($iv . $ciphertext);
}

function decryptDiaryText(?string $encodedCipherText): string
{
    if ($encodedCipherText === null || $encodedCipherText === '') {
        return '';
    }

    $key = getDiaryEncryptionKey();
    $cipher = 'AES-256-CBC';
    $ivLength = openssl_cipher_iv_length($cipher);
    $raw = base64_decode($encodedCipherText, true);

    if ($raw === false || strlen($raw) < $ivLength) {
        return '';
    }

    $iv = substr($raw, 0, $ivLength);
    $ciphertext = substr($raw, $ivLength);

    $plainText = openssl_decrypt(
        $ciphertext,
        $cipher,
        $key,
        OPENSSL_RAW_DATA,
        $iv
    );

    return $plainText === false ? '' : $plainText;
}
?>