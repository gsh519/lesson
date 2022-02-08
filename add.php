<?php
$success_msg = [];
$errors = [];
session_start();

$name = null;
$name_kana = null;
$sex = null;
$birthday = null;

if (!empty($_POST['add'])) {

    if (isset($_POST['name']) && $_POST['name'] !== '') {
        $name = $_POST['name'];
    }
    if (isset($_POST['name_kana']) && $_POST['name_kana'] !== '') {
        $name_kana = $_POST['name_kana'];
    }
    if (isset($_POST['sex']) && $_POST['sex'] !== '') {
        $sex = $_POST['sex'];
    }
    if (isset($_POST['birthday']) && $_POST['birthday'] !== '') {
        $birthday = $_POST['birthday'];
    }

    if ($name === null) {
        $errors[] = '氏名は必須です';
    }
    if ($name_kana === null) {
        $errors[] = 'かなは必須です';
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

        $sql = "INSERT INTO employees (name, name_kana, sex, birthday) VALUES (:name, :name_kana, :sex, :birthday)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':name_kana', $name_kana, PDO::PARAM_STR);
        $stmt->bindParam(':sex', $sex, PDO::PARAM_STR);
        $stmt->bindParam(':birthday', $birthday, PDO::PARAM_STR);

        $res = $stmt->execute();

        if ($res) {
            $_SESSION['success_msg'] = '登録しました';
        }

        $stmt = null;
        $pdo = null;

        header("Location: ./add.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員登録</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <main>
        <!-- 成功メッセージ -->
        <?php if (empty($_POST['add']) && !empty($_SESSION['success_msg'])) : ?>
            <p class="success-message"><?php echo $_SESSION['success_msg']; ?></p>
            <?php unset($_SESSION['success_msg']); ?>
        <?php endif; ?>

        <h1 class="title">社員登録</h1>
        <div class="content">
            <!-- エラーメッセージ表示 -->
            <?php if (!empty($errors)) : ?>
                <ul class="error-message">
                    <?php foreach ($errors as $error) : ?>
                        <li>・<?php echo $error; ?></li>
                    <?php endforeach ?>
                </ul>
            <?php endif; ?>
            <div class="add-form">
                <form action="" method="post">

                    <!-- 氏名 -->
                    <div class="form-area">
                        <label class="label" for="name">氏名<span>必須</span></label>
                        <input required type="text" id="name" name="name" class="form-input" value="<?php echo $name; ?>">
                    </div>

                    <!-- かな -->
                    <div class="form-area">
                        <label class="label" for="name_kana">かな<span>必須</span></label>
                        <input required type="text" id="name_kana" name="name_kana" class="form-input" value="<?php echo $name_kana; ?>">
                    </div>

                    <!-- 性別 -->
                    <div class="form-area">
                        <label class="label" for="sex">性別</label>
                        <select name="sex" id="sex" class="form-select">
                            <option value="">選択</option>
                            <option <?php if ($sex === '0') { echo 'selected'; } ?> value="0">男</option>
                            <option <?php if ($sex === '1') { echo 'selected'; } ?> value="1">女</option>
                            <option <?php if ($sex === '2') { echo 'selected'; } ?> value="2">不明</option>
                        </select>
                    </div>

                    <!-- 生年月日 -->
                    <div class="form-area">
                        <label class="label" for="birthday">生年月日</label>
                        <input type="date" id="birthday" name="birthday" class="form-input" value="<?php echo $birthday; ?>">
                    </div>

                    <!-- 登録ボタン -->
                    <div class="form-area">
                        <input type="submit" name="add" class="form-submit" value="登録">
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>