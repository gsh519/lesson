<?php
$errors = [];

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

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員編集</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <main>

        <h1 class="title">社員編集</h1>
        <div class="content">
            <div class="add-form">
                <form action="" method="post">

                    <!-- 氏名 -->
                    <div class="form-area">
                        <label class="label" for="name">氏名<span>必須</span></label>
                        <input required type="text" id="name" name="name" class="form-input" value="">
                    </div>

                    <!-- かな -->
                    <div class="form-area">
                        <label class="label" for="name_kana">かな<span>必須</span></label>
                        <input required type="text" id="name_kana" name="name_kana" class="form-input" value="">
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
                        <input type="date" id="birthday" name="birthday" class="form-input" value="">
                    </div>

                    <!-- 登録ボタン -->
                    <div class="form-area">
                        <input type="submit" name="edit" class="form-submit" value="登録">
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>