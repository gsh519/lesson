<?php
require(__DIR__ . '/../config/config.php');

abstract class BaseController
{
    public $db;
    public $token;
    public $current_url;
    public $active_menu;
    public $params = [];

    public function __construct()
    {
        session_start();
        $this->current_url = $_SERVER['REQUEST_URI'];

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
            $this->db = new PDO('mysql:charset=UTF8;dbname=' . DB_NAME . ';host=' . DB_HOST, DB_USER, DB_PASS, $option);
        } catch (PDOException $e) {
            die('error:' . $e->getMessage());
        }
    }

    public function escape($str)
    {
        $res = htmlspecialchars($str, ENT_QUOTES);
        echo $res;
    }

    /**
     * 配列 $array の キー $key の値を返す。
     * 空文字の場合、null の場合、キー自体が存在しない場合、$default を返す
     *
     * @param [type] $array
     * @param [type] $key
     * @param [type] $default
     */
    public function arrayGet($array, $key, $default = null)
    {
        if (isset($array[$key]) && $array[$key] !== '') {
            return $array[$key];
        } else {
            return $default;
        }
    }
}