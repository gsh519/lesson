<?php
require('./entities/employee.php');
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
    
    $employee = new Employee($_POST);

    // 社員情報バリデーション
    $errors_array = $employee->checkEmployeeData($employee->name, $employee->name_kana, $employee->email, $employee->commute, $employee->blood_type);
    foreach ($errors_array as $error) {
        if (isset($error)) {
            $errors[] = $error;
        }
    }

    if (empty($errors)) {

        $params[':id'] = $id;
        $params[':name'] = $employee->name;
        $params[':name_kana'] = $employee->name_kana;
        $params[':sex'] = $employee->sex;
        $params[':birthday'] = $employee->birthday;
        $params[':email'] = $employee->email;
        $params[':commute'] = $employee->commute;
        $params[':blood_type'] = $employee->blood_type;
        $params[':married'] = $employee->married;

        $pdo->beginTransaction();

        try {
            $note = "UPDATE employees SET name = :name, name_kana = :name_kana, sex = :sex, birthday = :birthday, email = :email, commute = :commute, blood_type = :blood_type, married = :married WHERE id = :id";
            $sql->plural($note, $params);
            $res = $pdo->commit();
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
            $pdo->rollBack();
        }

        $stmt = null;
        $pdo = null;

        if ($res) {
            $_SESSION['success_msg'] = '更新しました';
            header("Location: ./edit.php?id={$id}");
            exit;
        } else {
            $errors[] = '更新できませんでした';
        }
    }
} else {
    $params[':id'] = $id;
    if (isset($_GET['id']) && $_GET['id'] !== '') {
        //id一致のデータ取得
        $note = "SELECT * FROM employees WHERE id = :id";
        $employee_array = $sql->select($note, $params);
        if (!empty($employee_array)) {
            $employee = new Employee($employee_array);
        } 
    } 
}

//トークンの生成
$token = bin2hex(openssl_random_pseudo_bytes(16));
$_SESSION['token'] = $token;

require("./views/edit.view.php");
?>