<?php
$userId = $argv[1];
$salt = "Random_KUGBJVY";
$sign = md5($userId.$salt);

$key = pack('H*',"bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

$url = 'http://liguizhi.com/ammountInterface.php?userId='.$userId.'&sign='.$sign;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
$amount = AESDecrypt($result, $key);
file_put_contents('interface.log', 'amount:'.$amount,FILE_APPEND);
echo $amount;
/**
 * AESEncrypt 加密函数
 *
 * @author liguizhi <liguizhi@ucfgroup.com>
 * @param mixed $string
 * @param mixed $key
 */
function AESEncrypt($string, $key) {
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_MODE_ECB);
    $ciphertext_base64 = base64_encode($ciphertext);
    return $ciphertext_base64;
}

/**
 * AESDecrypt 解密函数
 *
 * @author liguizhi <liguizhi@ucfgroup.com>
 * @param mixed $string
 * @param mixed $key
 */
function AESDecrypt($string, $key) {
    $ciphertext_dec = base64_decode($string);
    $rawText = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_ECB);
    return $rawText;
}
