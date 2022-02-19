<?php
abstract class BaseController
{
    public $db;
    public $token;

    public function __construct()
    {
        session_start();

        // トークンチェック
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                empty($_POST['token'])
                || empty($_SESSION['token'])
                || $_POST['token'] !== $_SESSION['token']
            ) {
                die('トークンが一致しません');
            }
        }

        //トークンの生成
        $this->token = bin2hex(openssl_random_pseudo_bytes(16));
        $_SESSION['token'] = $this->token;

        //DB接続
        try {
            $option = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
            );
            $this->db = new PDO('mysql:charset=UTF8;dbname=employee;host=mysql', 'root', 'password', $option);
        } catch (PDOException $e) {
            die('error:' . $e->getMessage());
        }
    }
}
