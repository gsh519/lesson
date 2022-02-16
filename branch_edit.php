<?php
require('./entities/branch.php');
session_start();
$errors = [];

if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id = $_GET['id'];
}

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


// 更新処理
if (!empty($_POST['edit'])) {

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

        $pdo->beginTransaction();

        try {
            $sql = "UPDATE branches SET branch_name = :branch_name, phone_number = :phone_number, ken_name = :ken_name, city_name = :city_name, street_address = :street_address, building_name = :building_name, sort_order = :sort_order WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':branch_name', $branch->branch_name, PDO::PARAM_STR);
            $stmt->bindParam(':phone_number', $branch->phone_number, PDO::PARAM_STR);
            $stmt->bindParam(':ken_name', $branch->ken_name, PDO::PARAM_STR);
            $stmt->bindParam(':city_name', $branch->city_name, PDO::PARAM_STR);
            $stmt->bindParam(':street_address', $branch->street_address, PDO::PARAM_STR);
            $stmt->bindParam(':building_name', $branch->building_name, PDO::PARAM_STR);
            $stmt->bindParam(':sort_order', $branch->sort_order, PDO::PARAM_STR);
            $stmt->execute();
            $res = $pdo->commit();
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
            $pdo->rollBack();
            $res = false;   
        }

        $stmt = null;
        $pdo = null;

        if ($res) {
            $_SESSION['success_msg'] = '更新しました';
            header("Location: ./branch_edit.php?id={$id}");
            exit;
        }
    }
} else {

    if (isset($_GET['id']) && $_GET['id'] !== '') {
        //id一致のデータ取得
        $sql = "SELECT * FROM branches WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $branch_array = $stmt->fetch();
        if (!empty($branch_array)) {
            $branch = new Branch($branch_array);
        } 
    }
}

//トークンの生成
$token = bin2hex(openssl_random_pseudo_bytes(16));
$_SESSION['token'] = $token;

require("./views/branch_edit.view.php");
?>