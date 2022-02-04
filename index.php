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


$sql = "SELECT * FROM employees WHERE 1 = 1 ";

if ($name !== null) {
    $sql = $sql . "and ((name like :name) or (name_kana like :name)) ";
}
if ($sex !== null) {
    $sql = $sql . "and sex = :sex ";
}

$start_no = (5 * $page) - 5;
$sql = $sql . "limit 5 offset {$start_no}";


$stmt = $pdo->prepare($sql);

var_dump($sql);

if ($name) {
    $value = '%' . $name . '%';
    $stmt->bindParam(":name", $value, PDO::PARAM_STR);
}
if ($sex !== null) {
    $stmt->bindParam(":sex", $sex, PDO::PARAM_STR);
}

$res = $stmt->execute();

if ($res) {
    $employees = $stmt->fetchAll();
}

if (empty($employees)) {
    $errors[] = '該当する社員がいません';
}

$sql = "SELECT * FROM employees WHERE 1 = 1 ";

if ($name !== null) {
    $sql = $sql . "and ((name like :name) or (name_kana like :name)) ";
}
if ($sex !== null) {
    $sql = $sql . "and sex = :sex ";
}

$stmt = $pdo->prepare($sql);

var_dump($sql);

if ($name) {
    $value = '%' . $name . '%';
    $stmt->bindParam(":name", $value, PDO::PARAM_STR);
}
if ($sex !== null) {
    $stmt->bindParam(":sex", $sex, PDO::PARAM_STR);
}

$res = $stmt->execute();

if ($res) {
    $employeesAll = $stmt->fetchAll();
}

// トータルデータ件数
$employeesAll_num = count($employeesAll);
var_dump($employeesAll_num);

/*
//トータルページ数
$max_page = ceil($employees_num / MAX);

// 現在のページ数
if (!isset($_GET['page_id'])) {
    $now = 1;
} else {
    $now = $_GET['page_id'];
}

//何番目から取得
$start_no = ($now - 1) * MAX;

//何番目から何番目までかを切り取る
$disp_data = array_slice($employees, $start_no, MAX, true);
*/
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
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php echo $employeesAll_num; ?>件中 1〜5 件目を表示
                <a href="">前へ</a>
                <a href="?page=1">1</a>
                <a href="?page=2">2</a>
                <a href="?page=3">3</a>
                <a href="?page=4">4</a>
                <a href="">次へ</a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>