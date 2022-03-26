<?php

namespace Packages\Encryptor;

class Encryptor
{
    const ENCRYPTION_KEY = 'axx6d1hyizalupaidinahyi3';

    public function encrypt(string $data): string
    {
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($data, $cipher, self::ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, self::ENCRYPTION_KEY, true);
        return base64_encode( $iv.$hmac.$ciphertext_raw );
    }

    public function decrypt(string $encoded): string
    {
        $c = base64_decode($encoded);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $ciphertext_raw = substr($c, $ivlen+32);
        return openssl_decrypt($ciphertext_raw, $cipher, self::ENCRYPTION_KEY, $options=OPENSSL_RAW_DATA, $iv);
    }
}