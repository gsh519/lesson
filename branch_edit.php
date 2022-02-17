<?php
require('./entities/branch.php');
require('./entities/sql.php');

session_start();

$sql = new Sql();
$errors = [];
$params = [];

if (isset($_GET['id']) && $_GET['id'] !== '') {
    $id = $_GET['id'];
}

$pdo = $sql->dbConnect();

// 更新処理
if (!empty($_POST['edit'])) {
    
    $branch = new Branch($_POST);

    // 支店情報バリデーション
    $errors_array = $branch->checkBranchData($branch->branch_name, $branch->ken_name, $branch->city_name, $branch->street_address, $branch->phone_number, $branch->sort_order);
    foreach ($errors_array as $error) {
        if (isset($error)) {
            $errors[] = $error;
        }
    }

    if (empty($errors)) {

        $params['id'] = $id;
        $params['branch_name'] = $branch->branch_name;
        $params['phone_number'] = $branch->phone_number;
        $params['ken_name'] = $branch->ken_name;
        $params['city_name'] = $branch->city_name;
        $params['street_address'] = $branch->street_address;
        $params['building_name'] = $branch->building_name;
        $params['sort_order'] = $branch->sort_order;

        $pdo->beginTransaction();

        try {
            $note = "UPDATE branches SET branch_name = :branch_name, phone_number = :phone_number, ken_name = :ken_name, city_name = :city_name, street_address = :street_address, building_name = :building_name, sort_order = :sort_order WHERE id = :id";
            $sql->plural($note, $params);
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

    $params[':id'] = $id;
    if (isset($_GET['id']) && $_GET['id'] !== '') {
        //id一致のデータ取得
        $note = "SELECT * FROM branches WHERE id = :id";
        $branch_array = $sql->select($note, $params);
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