<?php
session_start();
$errors = [];
$employee['name'] = null;
$employee['name_kana'] = null;
$employee['sex'] = null;
$employee['birthday'] = null;
$employee['email'] = null;
$employee['commute'] = null;
$employee['blood_type'] = null;
$employee['married'] = null;

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
    if (isset($_POST['name']) && $_POST['name'] !== '') {
        $employee['name'] = $_POST['name'];
    }
    if (isset($_POST['name_kana']) && $_POST['name_kana'] !== '') {
        $employee['name_kana'] = $_POST['name_kana'];
    }
    if (isset($_POST['sex']) && $_POST['sex'] !== '') {
        $employee['sex'] = $_POST['sex'];
    }
    if (isset($_POST['birthday']) && $_POST['birthday'] !== '') {
        $employee['birthday'] = $_POST['birthday'];
    }
    if (isset($_POST['email']) && $_POST['email'] !== '') {
        $employee['email'] = $_POST['email'];
    }
    if (isset($_POST['commute']) && $_POST['commute'] !== '') {
        $employee['commute'] = $_POST['commute'];
    }
    if (isset($_POST['blood_type']) && $_POST['blood_type'] !== '') {
        $employee['blood_type'] = $_POST['blood_type'];
    }
    if (isset($_POST['married']) && $_POST['married'] !== '') {
        $employee['married'] = $_POST['married'];
    }
    if ($employee['name'] === null) {
        $errors[] = '氏名は必須です';
    }
    if ($employee['name_kana'] === null) {
        $errors[] = 'かなは必須です';
    }
    if ($employee['email'] === null) {
        $errors[] = 'メールアドレスは必須です';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'メールアドレスの形式が間違っています';
    }
    if ($employee['commute'] <= 0) {
        $errors[] = '通勤時間は1以上にしてください';
    }
    if ($employee['blood_type'] === null) {
        $errors[] = '血液型は必須です';
    }

    if (empty($errors)) {

        $pdo->beginTransaction();

        try {
            $sql = "UPDATE employees SET name = :name, name_kana = :name_kana, sex = :sex, birthday = :birthday, email = :email, commute = :commute, blood_type = :blood_type, married = :married WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $employee['name'], PDO::PARAM_STR);
            $stmt->bindParam(':name_kana', $employee['name_kana'], PDO::PARAM_STR);
            $stmt->bindParam(':sex', $employee['sex'], PDO::PARAM_STR);
            $stmt->bindParam(':birthday', $employee['birthday'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $employee['email'], PDO::PARAM_STR);
            $stmt->bindParam(':commute', $employee['commute'], PDO::PARAM_STR);
            $stmt->bindParam(':blood_type', $employee['blood_type'], PDO::PARAM_STR);
            $stmt->bindParam(':married', $employee['married'], PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
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
        }
    }
} else {

    if (isset($_GET['id']) && $_GET['id'] !== '') {
        //id一致のデータ取得
        $sql = "SELECT * FROM employees WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $employee = $stmt->fetch();
    } else {
        $employee = null;
    }
}
require("./views/edit.view.php");
?>