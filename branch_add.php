<?php
require('./entities/branch.php');

session_start();

$success_msg = [];
$errors = [];


// 登録ボタン処理
if (!empty($_POST['add'])) {

    // トークンチェック
    if (
        empty($_POST['token'])
        || empty($_SESSION['token'])
        || $_POST['token'] !== $_SESSION['token']
    ) {
        $errors[] = 'トークンが一致しません';
    }

    $branch = new Branch($_POST);

    if ($branch->branch_name === null) {
        $errors[] = '支店名は必須です';
    }
    if ($branch->ken_name === null) {
        $errors[] = '都道府県名は必須です';
    }
    if ($branch->city_name === null) {
        $errors[] = '市区町村は必須です';
    }
    if ($branch->street_address === null) {
        $errors[] = '字番地は必須です';
    }
    if ($branch->phone_number === null) {
        $errors[] = '電話番号は必須です';
    } elseif (!preg_match('/\A\d{2,4}+-\d{2,4}+-\d{4}\z/', $branch->phone_number)) {
        $errors[] = '電話番号の形式が違います';
    }
    if ($branch->sort_order === null) {
        $errors[] = '並び順は必須です';
    } elseif ($branch->sort_order <= 0) {
        $errors[] = '並び順は0以上でお願いします';
    }

    if (empty($errors)) {
        //データベース接続
        try {
            $option = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
            );
            $pdo = new PDO('mysql:charset=UTF8;dbname=employee;host=mysql', 'root', 'password', $option);
        } catch (PDOException $e) {
            $errors[] = $e->getMessage();
        }

        // データベースに登録
        $sql = "INSERT INTO branches (branch_name, phone_number, ken_name, city_name, street_address, building_name, sort_order) VALUES (:branch_name, :phone_number, :ken_name, :city_name, :street_address, :building_name, :sort_order)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':branch_name', $branch->branch_name, PDO::PARAM_STR);
        $stmt->bindParam(':phone_number', $branch->phone_number, PDO::PARAM_STR);
        $stmt->bindParam(':ken_name', $branch->ken_name, PDO::PARAM_STR);
        $stmt->bindParam(':city_name', $branch->city_name, PDO::PARAM_STR);
        $stmt->bindParam(':street_address', $branch->street_address, PDO::PARAM_STR);
        $stmt->bindParam(':building_name', $branch->building_name, PDO::PARAM_STR);
        $stmt->bindParam(':sort_order', $branch->sort_order, PDO::PARAM_STR);

        $res = $stmt->execute();

        if ($res) {
            $_SESSION['success_msg'] = '登録しました';
        }

        $stmt = null;
        $pdo = null;

        header("Location: ./branch_add.php");
        exit;
    }
} else {
    $branch = new Branch();
}

//トークンの生成
$token = bin2hex(openssl_random_pseudo_bytes(16));
$_SESSION['token'] = $token;

require("./views/branch_add.view.php");
?>