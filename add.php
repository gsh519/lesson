<?php
$errors = [];

$name = $_POST['name'];
$name_kana = $_POST['name_kana'];
$sex = $_POST['sex'];
$birthday = $_POST['birthday'];

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

$sql = "INSRT INTO (name, name_kana, sex, birthday) VALUES (:name, :name_kana, :sex, :birthday)";
$stmt = $pdo->prepare($sql);

$stmt->bindParam(':name', $name, PDO::PARAM_STR);
$stmt->bindParam(':name_kana', $name_kana, PDO::PARAM_STR);
$stmt->bindValue(':sex', $sex, PDO::PARAM_INT);
$stmt->bindParam(':birthday', $birthday, PDO::PARAM_STR);

$res = $stmt->execute();
var_dump($res);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員登録</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
<main>
        <h1 class="title">社員登録</h1>

        <div class="content">
            <!-- エラーメッセージ表示 -->
            <?php if (!empty($errors)) : ?>
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach ?>
                </ul>
            <!-- エラーメッセージなし -->
            <?php elseif (empty($errors)) : ?>
                <div class="add-form">
                    <form action="" method="post">

                        <!-- 氏名 -->
                        <div class="form-area">
                            <label class="label" for="name">氏名<span>必須</span></label>
                            <input type="text" id="name" name="name" class="form-input">
                        </div>

                        <!-- かな -->
                        <div class="form-area">
                            <label class="label" for="name_kana">かな<span>必須</span></label>
                            <input type="text" id="name_kana" name="name_kana" class="form-input">
                        </div>

                        <!-- 性別 -->
                        <div class="form-area">
                            <label class="label" for="sex">性別</label>
                            <select name="sex" id="sex" class="form-select">
                                <option value="">選択</option>
                                <option value="0">男</option>
                                <option value="1">女</option>
                                <option value="2">不明</option>
                            </select>
                        </div>

                        <!-- 生年月日 -->
                        <div class="form-area">
                            <label class="label" for="birthday">生年月日</label>
                            <input type="date" id="birthday" name="birthday" class="form-input">
                        </div>

                        <!-- 登録ボタン -->
                        <div class="form-area">
                            <button type="submit" class="form-submit">登録</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>