<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 8/24/2022
 * Time: 22:25
 */

namespace App\Utils;


use App\Common\SingletonPattern;

class CryptUtil extends SingletonPattern
{
    protected $cipher;
    protected $iv;
    protected $encryptionKey;
    protected $redisConnection;
    protected $redisKey;

    /**
     * @return CryptUtil|mixed
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    protected function __construct()
    {
        $this->cipher = "aes-256-cbc";
        $ivSize = openssl_cipher_iv_length($this->cipher);
        $this->iv = str_repeat('i', $ivSize);
        $this->encryptionKey = str_repeat('k', 32);
        $this->redisConnection = 'id_system';
        $this->redisKey = 'system_encrypt_keys';
        $encryptKey = $this->getCachedKey();
        if (!empty($encryptKey)) {
            $this->cipher = $encryptKey['name'];
            $this->iv = $encryptKey['iv'];
            $this->encryptionKey = substr($encryptKey['encryptionKey'], 0, 32);
        }
    }

    private function getCachedKey()
    {
        return json_decode(RedisUtil::get($this->redisKey, $this->redisConnection), true);
    }

    public function encrypt($data)
    {
        $encrypted_data = openssl_encrypt($data, $this->cipher, $this->encryptionKey, 0, $this->iv);
        return base64_encode($encrypted_data);
    }

    public function decrypt(string $encrypted_data)
    {
        $decrypted_data = openssl_decrypt(base64_decode($encrypted_data), $this->cipher, $this->encryptionKey, 0, $this->iv);
        return $decrypted_data;
    }

    public function encryptData(array $data)
    {
        $encrypted_data = openssl_encrypt(json_encode($data), $this->cipher, $this->encryptionKey, 0, $this->iv);
        return base64_encode($encrypted_data);
    }

    public function decryptData(string $encrypted_data)
    {
        $decrypted_data = openssl_decrypt(base64_decode($encrypted_data), $this->cipher, $this->encryptionKey, 0, $this->iv);
        return json_decode($decrypted_data, true);
    }

}