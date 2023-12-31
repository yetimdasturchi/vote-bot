<?php

class Encryptor {


    public function encode($str, $key = null) {
        if ($key === null) {
            $key = sha1('NTNv7j0TuYARvm');
        }

        $key_hash = sha1($key);
        $str_len  = strlen($str);
        $seq      = $key;
        $gamma    = '';
        $salt     = sha1($key_hash);

        while (strlen($gamma) < $str_len) {
            $seq   = sha1($gamma . $seq . $salt);
            $gamma .= substr($seq, 0, 8);
        }

        return $str ^ $gamma;
    }

    public function xorEncryptArray($arr, $key = null) {
        $encode_str = $this->encode(json_encode( $arr ), $key);
        return $this->base64EncodeUrl($encode_str);
    }


    public function xorDecryptArray($str, $key = null) {
        $str = $this->base64DecodeUrl($str);
        return json_decode( $this->encode($str, $key), TRUE );
    }

    public function xorEncrypt($str, $key = null) {
        $encode_str = $this->encode($str, $key);
        return $this->base64EncodeUrl($encode_str);
    }


    public function xorDecrypt($str, $key = null) {
        $str = $this->base64DecodeUrl($str);
        return $this->encode($str, $key);
    }


    public function base64EncodeUrl($str) {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }


    public function base64DecodeUrl($str) {
        return base64_decode(str_pad(strtr($str, '-_', '+/'), strlen($str) % 4, '=', STR_PAD_RIGHT));
    }

}