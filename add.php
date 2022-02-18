<?php
require('./entities/employee.php');
require('./varidators/employee-validator.php');
require('./entities/sql.php');

session_start();

$sql = new Sql();
$params = [];

// 登録ボタン処理
if (!empty($_POST['add'])) {

    $employee = new Employee($_POST);

    // 社員情報バリデーション
    $validator = new EmployeeValidator();
    $validator->validate($employee);
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
    } else {
        // エラーあり
        $errors = $validator->errors;
    }
    
} else {
    $employee = new Employee();
}

//トークンの生成
$token = bin2hex(openssl_random_pseudo_bytes(16));
$_SESSION['token'] = $token;

require("./views/add.view.php");
?>