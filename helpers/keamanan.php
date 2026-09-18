<?php

function aman($data)
{
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function enkripsiData($data)
{
    $key = hash('sha256', 'UJIAN_PHP_2026_SECRET_KEY');
    $iv = substr(hash('sha256', 'UJIAN_PHP_IV'), 0, 16);

    return openssl_encrypt(
        $data,
        'AES-256-CBC',
        $key,
        0,
        $iv
    );
}

function dekripsiData($data)
{
    $key = hash('sha256', 'UJIAN_PHP_2026_SECRET_KEY');
    $iv = substr(hash('sha256', 'UJIAN_PHP_IV'), 0, 16);

    return openssl_decrypt(
        $data,
        'AES-256-CBC',
        $key,
        0,
        $iv
    );
}