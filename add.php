<?php
require('./entities/employee.php');
require('./entities/sql.php');

session_start();

$sql = new Sql();
$success_msg = [];
$errors = [];
$params = [];



// 登録ボタン処理
if (!empty($_POST['add'])) {

    $employee = new Employee($_POST);

    // 社員情報バリデーション
    $errors_array = $employee->checkEmployeeData($employee->name, $employee->name_kana, $employee->email, $employee->commute, $employee->blood_type);
    foreach ($errors_array as $error) {
        if (isset($error)) {
            $errors[] = $error;
        }
    }

    if (empty($errors)) {

        $params[':name'] = $employee->name;
        $params[':name_kana'] = $employee->name_kana;
        $params[':sex'] = $employee->sex;
        $params[':birthday'] = $employee->birthday;
        $params[':email'] = $employee->email;
        $params[':commute'] = $employee->commute;
        $params[':blood_type'] = $employee->blood_type;
        $params[':married'] = $employee->married;

        $note = "INSERT INTO employees (name, name_kana, sex, birthday, email, commute, blood_type, married) VALUES (:name, :name_kana, :sex, :birthday, :email, :commute, :blood_type, :married)";

        $res = $sql->plural($note, $params);

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