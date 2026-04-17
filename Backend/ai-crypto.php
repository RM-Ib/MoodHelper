<?php

$key = "user's secret key"; 

function encryptMessage($message) {
    global $key;
    return openssl_encrypt($message, "AES-256-CBC", $key, 0, substr($key, 0, 16));
}

function decryptMessage($encrypted) {
    global $key;
    return openssl_decrypt($encrypted, "AES-256-CBC", $key, 0, substr($key, 0, 16));
}