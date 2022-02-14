<?php

$errors = [];
$employee['name'] = null;
$employee['name_kana'] = null;
$employee['sex'] = null;
$employee['birthday'] = null;
$employee['email'] = null;
$employee['commute'] = null;
$employee['blood_type'] = null;
$employee['married'] = null;

if (isset($_GET['name']) && $_GET['name'] !== '') {
    $employee['name'] = $_GET['name'];
}
if (isset($_GET['sex']) && $_GET['sex'] !== '') {
    $employee['sex'] = $_GET['sex'];
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

//検索条件
if ($employee['name'] !== null) {
    $sql_where = $sql_where . "and ((name like :name) or (name_kana like :name)) ";

    $value = '%' . $employee['name'] . '%';
    $param[":name"] = $value;
}

if ($employee['sex'] !== null) {
    $sql_where = $sql_where . "and sex = :sex ";

    $param[":sex"] = $employee['sex'];
}


//メインデータ取得
$sql = "SELECT * FROM employees " . $sql_where;
$start_no = (5 * $page) - 5;
$sql = $sql . "limit 5 offset {$start_no}";

$stmt = $pdo->prepare($sql);
$res = $stmt->execute($param);

if ($res) {
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (empty($employees)) {
    $errors[] = '該当する社員がいません';
}

foreach ($employees as $index => $val) {
    // 年齢
    $now = date('Ymd');
    $employee['birthday'] = str_replace("-", "", $val['birthday']);
    $age = floor(($now - $employee['birthday']) / 10000);
    $employees[$index]['age'] = $age;

    // 性別
    if ($val['sex'] === '0') {
        $employees[$index]['gender'] = '男';
    } elseif ($val['sex'] === '1') {
        $employees[$index]['gender'] = '女';
    } elseif ($val['sex'] == '2') {
        $employees[$index]['gender'] = '不明';
    }

    // 通勤時間
    if (isset($val['commute']) && $val['commute'] !== '') {
        $employees[$index]['commute'] = $val['commute'] . '分';
    }

    // 血液型
    if ($val['blood_type'] === '0') {
        $employees[$index]['blood_type'] = '不明';
    } elseif ($val['blood_type'] === '1') {
        $employees[$index]['blood_type'] = 'A型';
    } elseif ($val['blood_type'] === '2') {
        $employees[$index]['blood_type'] = 'B型';
    } elseif ($val['blood_type'] === '3') {
        $employees[$index]['blood_type'] = 'O型';
    } elseif ($val['blood_type'] === '4') {
        $employees[$index]['blood_type'] = 'AB型';
    }

    //既婚
    if ($val['married'] === null || $val['married'] === '0') {
        $employees[$index]['married'] = '未婚';
    } elseif ($val['married'] === '1') {
        $employees[$index]['married'] = '既婚';
    }
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

require("./views/index.view.php");
?>
