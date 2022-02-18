<?php
require('./entities/branch.php');
require('./varidators/branch-validator.php');
require('./entities/sql.php');
session_start();

$sql = new Sql();
$success_msg = [];
$errors = [];
$params = [];


// 登録ボタン処理
if (!empty($_POST['add'])) {

    $branch = new Branch($_POST);

    // トークン
    if (
        empty($_POST['token'])
        || empty($_SESSION['token'])
        || $_POST['token'] !== $_SESSION['token']
    ) {
        $errors[] = 'トークンが一致しません';
    }

    $validator = new BranchValidator();
    $validator->validate($branch);
    // トークンチェック
    if (
        empty($_POST['token'])
        || empty($_SESSION['token'])
        || $_POST['token'] !== $_SESSION['token']
    ) {
        $errors[] = 'トークンが一致しません';
        $validator->valid = false;
    }
    if ($validator->valid) {
        // エラーなし
        //保存処理
        $params['branch_name'] = $branch->branch_name;
        $params['phone_number'] = $branch->phone_number;
        $params['ken_name'] = $branch->ken_name;
        $params['city_name'] = $branch->city_name;
        $params['street_address'] = $branch->street_address;
        $params['building_name'] = $branch->building_name;
        $params['sort_order'] = $branch->sort_order;

        $note = "INSERT INTO branches (branch_name, phone_number, ken_name, city_name, street_address, building_name, sort_order) VALUES (:branch_name, :phone_number, :ken_name, :city_name, :street_address, :building_name, :sort_order)";

        $res = $sql->plural($note, $params);

        if ($res) {
            $_SESSION['success_msg'] = '登録しました';
        }

        $stmt = null;
        $pdo = null;

        header("Location: ./branch_add.php");
        exit;
    } else {
        // エラーあり
        $errors = $validator->errors;
    }


    // if (empty($errors)) {

    //     $params['branch_name'] = $branch->branch_name;
    //     $params['phone_number'] = $branch->phone_number;
    //     $params['ken_name'] = $branch->ken_name;
    //     $params['city_name'] = $branch->city_name;
    //     $params['street_address'] = $branch->street_address;
    //     $params['building_name'] = $branch->building_name;
    //     $params['sort_order'] = $branch->sort_order;

    //     $note = "INSERT INTO branches (branch_name, phone_number, ken_name, city_name, street_address, building_name, sort_order) VALUES (:branch_name, :phone_number, :ken_name, :city_name, :street_address, :building_name, :sort_order)";

    //     $res = $sql->plural($note, $params);

    //     if ($res) {
    //         $_SESSION['success_msg'] = '登録しました';
    //     }

    //     $stmt = null;
    //     $pdo = null;

    //     header("Location: ./branch_add.php");
    //     exit;
    // }

} else {
    $branch = new Branch();
}

//トークンの生成
$token = bin2hex(openssl_random_pseudo_bytes(16));
$_SESSION['token'] = $token;

require("./views/branch_add.view.php");
?>