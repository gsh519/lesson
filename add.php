<?php
require('./entities/employee.php');

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

    $employee = new Employee($_POST);

    if ($employee->name === null) {
        $errors[] = '氏名は必須です';
    }
    if ($employee->name_kana === null) {  
        $errors[] = 'かなは必須です';
    }
    if ($employee->email === null) {
        $errors[] = 'メールアドレスは必須です';
    } elseif (!filter_var($employee->email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'メールアドレスの形式が間違っています';
    }
    if ($employee->commute <= 0) {
        $errors[] = '通勤時間は1以上にしてください';
    }
    if ($employee->blood_type === null) {
        $errors[] = '血液型は必須です';
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
        $sql = "INSERT INTO employees (name, name_kana, sex, birthday, email, commute, blood_type, married) VALUES (:name, :name_kana, :sex, :birthday, :email, :commute, :blood_type, :married)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $employee->name, PDO::PARAM_STR);
        $stmt->bindParam(':name_kana', $employee->name_kana, PDO::PARAM_STR);
        $stmt->bindParam(':sex', $employee->sex, PDO::PARAM_STR);
        $stmt->bindParam(':birthday', $employee->birthday, PDO::PARAM_STR);
        $stmt->bindParam(':email', $employee->email, PDO::PARAM_STR);
        $stmt->bindParam(':commute', $employee->commute, PDO::PARAM_STR);
        $stmt->bindParam(':blood_type', $employee->blood_type, PDO::PARAM_STR);
        $stmt->bindParam(':married', $employee->married, PDO::PARAM_STR);

        $res = $stmt->execute();

        if ($res) {
            $_SESSION['success_msg'] = '登録しました';
        }

        $stmt = null;
        $pdo = null;

        header("Location: ./add.php");
        exit;
    }
} else {
    $employee = new Employee();
}

//トークンの生成
$token = bin2hex(openssl_random_pseudo_bytes(16));
$_SESSION['token'] = $token;

require("./views/add.view.php");
?>