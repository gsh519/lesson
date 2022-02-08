<?php

$errors = [];

if (isset($_GET['name']) && $_GET['name'] !== '') {
    $name = $_GET['name'];
} else {
    $name = null;
}
if (isset($_GET['sex']) && $_GET['sex'] !== '') {
    $sex = $_GET['sex'];
} else {
    $sex = null;
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
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

//WHERE文の作成
$sql_where = "WHERE 1 = 1 ";
$param = array();

if ($name !== null) {
    $sql_where = $sql_where . "and ((name like :name) or (name_kana like :name)) ";

    $value = '%' . $name . '%';
    $param[":name"] = $value;
}

if ($sex !== null) {
    $sql_where = $sql_where . "and sex = :sex ";

    $param[":sex"] = $sex;
}


//メインデータ取得
$sql = "SELECT * FROM employees " . $sql_where;
$start_no = (5 * $page) - 5;
$sql = $sql . "limit 5 offset {$start_no}";

$stmt = $pdo->prepare($sql);
$res = $stmt->execute($param);

if ($res) {
    $employees = $stmt->fetchAll();
}

if (empty($employees)) {
    $errors[] = '該当する社員がいません';
}

//全件数取得
$count_sql = "SELECT count(*) FROM employees " . $sql_where;
$count_stmt = $pdo->prepare($count_sql);
$res = $count_stmt->execute($param);

if ($res) {
    $employees_count = $count_stmt->fetch();
}

$employeesAll_num = $employees_count[0];

//総ページ数
$pagenum = ceil($employeesAll_num / 5);

//○〜○件目
$from = ($page - 1) * 5 + 1;
if ($page == $pagenum) {
    $to = $employeesAll_num;
} else {
    $to = $page * 5;
}

$stmt = null;
$pdo = null;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員一覧</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <main>
        <h1 class="title">社員一覧</h1>

        <!-- 検索フォーム -->
        <div class="serch-form">
            <form action="" method="get">
                <label for="name">氏名</label>
                <input type="text" name="name" id="name" value="<?php if (isset($name)) { echo $name; } ?>">
                <label for="sex">性別</label>
                <select name="sex" id="sex">
                    <option value="">全て</option>
                    <option <?php if (isset($sex)) { if ($sex === '0') { echo 'selected'; }} ?> value="0">男</option>
                    <option <?php if (isset($sex)) { if ($sex === '1') { echo 'selected'; }} ?> value="1">女</option>
                    <option <?php if (isset($sex)) { if ($sex === '2') { echo 'selected'; }} ?> value="2">不明</option>
                </select>
                <button type="submit">検索</button>
            </form>
        </div>

        <div class="content">
            <?php if (!empty($errors)) : ?>
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach ?>
                </ul>
            <?php elseif (empty($errors)) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>氏名</th>
                            <th>かな</th>
                            <th>性別</th>
                            <th>年齢</th>
                            <th>生年月日</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $val) : ?>
                            <tr>
                                <td><?php echo $val['name']; ?></td>
                                <td><?php echo $val['name_kana']; ?></td>
                                <td>
                                    <?php if ($val['sex'] === '0') : ?>
                                        男
                                    <?php elseif ($val['sex'] === '1') : ?>
                                        女
                                    <?php elseif ($val['sex'] === '2') : ?>
                                        不明
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                        $now = date('Ymd');
                                        $birthday = str_replace("-", "", $val['birthday']);
                                        $age = floor(($now - $birthday) / 10000);
                                    ?>
                                    <?php echo $age; ?>
                                </td>
                                <td><?php echo $val['birthday']; ?></td>
                                <td><a class="edit-button" href="./edit.php">編集</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php echo $employeesAll_num; ?>件中 <?php echo $from; ?>-<?php echo $to; ?>件目を表示
                <?php if ($pagenum >= 2) : ?>
                    <?php if ($page >= 2) : ?>
                        <a href="?page=<?php echo ($page - 1); ?>&name=<?php echo $name; ?>&sex=<?php echo $sex; ?>">前へ</a>
                    <?php else : ?>
                        <a class="not-click">前へ</a>
                    <?php endif; ?>
                    <?php for ($i = $page - 2; $i < ($page + 3); $i++) : ?>
                        <?php if ($i >= 1 && $i <= $pagenum) : ?>
                            <?php if ($i == $page) : ?>
                                <a class="not-click"><?php echo $i; ?></a>
                            <?php else : ?>
                                <a href="?page=<?php echo $i; ?>&name=<?php echo $name; ?>&sex=<?php echo $sex; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($page < $pagenum) : ?>
                        <a href="?page=<?php echo ($page + 1); ?>&name=<?php echo $name; ?>&sex=<?php echo $sex; ?>">次へ</a>
                    <?php else : ?>
                        <a class="not-click">次へ</a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>