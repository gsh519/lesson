<?php
require('./entities/employee.php');
require('./entities/sql.php');
session_start();

$sql = new Sql();
$errors = [];
$search['name'] = null;
$search['sex'] = null;

if (isset($_GET['name']) && $_GET['name'] !== '') {
    $search['name'] = $_GET['name'];
}
if (isset($_GET['sex']) && $_GET['sex'] !== '') {
    $search['sex'] = $_GET['sex'];
}
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$pdo = $sql->dbConnect();

//WHERE文の作成
$sql_where = "WHERE 1 = 1 ";
$param = array();

//検索条件
if ($search['name'] !== null) {
    $sql_where = $sql_where . "and ((name like :name) or (name_kana like :name)) ";

    $value = '%' . $search['name'] . '%';
    $param[":name"] = $value;
}

if ($search['sex'] !== null) {
    $sql_where = $sql_where . "and sex = :sex ";

    $param[":sex"] = $search['sex'];
}


//メインデータ取得
$sql = "SELECT * FROM employees " . $sql_where;
$start_no = (5 * $page) - 5;
$sql = $sql . "limit 5 offset {$start_no}";

$stmt = $pdo->prepare($sql);
$res = $stmt->execute($param);

if ($res) {
    $employees_arrays = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (empty($employees_arrays)) {
    $errors[] = '該当する社員がいません';
}

$employees = [];
foreach ($employees_arrays as $index => $employee_array) {
    $employee = new Employee($employee_array);
    $employees[] = $employee;
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
