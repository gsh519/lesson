<?php
require('./entities/branch.php');
session_start();

$errors = [];
$search['branch_name'] = null;

if (isset($_GET['branch_name']) && $_GET['branch_name'] !== '') {
    $search['branch_name'] = $_GET['branch_name'];
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
if ($search['branch_name'] !== null) {
    $sql_where = $sql_where . "and (branch_name like :branch_name) ";
    $value = '%' . $search['branch_name'] . '%';
    $param[":branch_name"] = $value;
}


//メインデータ取得
$sql = "SELECT * FROM branches " . $sql_where . "ORDER BY sort_order ASC ";
$start_no = (5 * $page) - 5;
$sql = $sql . "limit 5 offset {$start_no}";

$stmt = $pdo->prepare($sql);
$res = $stmt->execute($param);

if ($res) {
    $branches_arrays = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (empty($branches_arrays)) {
    $errors[] = '該当する支店がありません';
}

$branches = [];
foreach ($branches_arrays as $index => $branch_array) {
    $branch = new Branch($branch_array);
    $branches[] = $branch;
}

//全件数取得
$count_sql = "SELECT count(*) FROM branches " . $sql_where;
$count_stmt = $pdo->prepare($count_sql);
$res = $count_stmt->execute($param);

if ($res) {
    $branches_count = $count_stmt->fetch();
}

$branchesAll_num = $branches_count[0];

//総ページ数
$pagenum = ceil($branchesAll_num / 5);

//○〜○件目
$from = ($page - 1) * 5 + 1;
if ($page == $pagenum) {
    $to = $branchesAll_num;
} else {
    $to = $page * 5;
}

$stmt = null;
$pdo = null;

require("./views/branch_index.view.php");
?>
