<?php
// +----------------------------------------------------------------------
// | NetOS
// +----------------------------------------------------------------------
// | Copyright (c) 2003-2019 http://netos.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: sunmens <sunmens@gmail.com>
// +----------------------------------------------------------------------
namespace sunmens\helper;

class Encrypt
{
    
    public static function encrypt($string, $key='')
    {
        return self::authcode($string, 'ENCODE', $key);
    }

    public static function decrypt($string, $key='')
    {
        return self::authcode($string, 'DECODE', $key);
    }

    /**
     * 字符串加密以及解密函数
     *
     * @param string $string    原文或者密文
     * @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE
     * @param string $key       密钥
     * @param int $expiry       密文有效期, 加密时候有效， 单位 秒，0 为永久有效
     * @return string       处理后的 原文或者 经过 base64_encode 处理后的密文
     *
     * @example
     *
     *  $a = authcode('abc', 'ENCODE', 'key');
     *  $b = authcode($a, 'DECODE', 'key');  // $b(abc)
     *
     *  $a = authcode('abc', 'ENCODE', 'key', 3600);
     *  $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
     */
    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        // $string = rawurldecode($args['string']);
        // $operation = $args['operation'];
        // $key = $args['key'];
        // $expiry = $args['expiry'];

        $string = rawurldecode($string);
        
        $ckey_length = 4;   //note 随机密钥长度 取值 0-32;
                    //note 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
                    //note 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
                    //note 当此值为 0 时，则不产生随机密钥

        $key = md5($key);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return rawurlencode($keyc.str_replace('=', '', base64_encode($result)));
        }
    }
}
