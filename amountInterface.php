<?php

$userId = $_GET['userId'];
$sign = $_GET['sign'];
$salt = 'Random_KUGBJVY';
//签名验证
if(!$sign || (md5($userId.$salt) != $sign)) {
    file_put_contents('interface.log', 'sign error'.$userId. '|'. $sign, FILE_APPEND);
    die('sign error');
}
//查询数据
$pdo = new PDO('mysql:host=127.0.0.1;dbname=playground','root','');
$sql = 'select amount from user_account where uid='.$userId;
$res = $pdo->query($sql);
$userAmount = $res->fetchColumn();

//生成加密参数
$key = pack('H*',"bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
$key_size = strlen($key);
$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

$amountEncrypt = AESEncrypt($userAmount, $key);

echo $amountEncrypt;







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
