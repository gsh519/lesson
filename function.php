<?php

// データベース接続
// function dbConnect()
// {
//     try {
//         $option = array(
//             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//             PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
//         );
//         $pdo = new PDO('mysql:charset=UTF8;dbname=employee;host=mysql', 'root', 'password', $option);
//     } catch (PDOException $e) {
//         $errors[] = $e->getMessage();
//     }

//     return $pdo;
// }

// 社員バリデーション
// function checkEmployeeData($name, $name_kana, $email, $commute, $blood_type)
// {
//     // トークン
//     if (
//         empty($_POST['token'])
//         || empty($_SESSION['token'])
//         || $_POST['token'] !== $_SESSION['token']
//     ) {
//         $error_token = 'トークンが一致しません';
//     } else {
//         $error_token = null;
//     }
//     // 氏名
//     if ($name === null) {
//         $error_name = '氏名は必須です';
//     } else {
//         $error_name = null;
//     }
//     // かな
//     if ($name_kana === null) {  
//         $error_name_kana = 'かなは必須です';
//     } else {
//         $error_name_kana = null;
//     }
//     // メールアドレス
//     if ($email === null) {
//         $error_email = 'メールアドレスは必須です';
//     } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         $error_email = 'メールアドレスの形式が間違っています';
//     } else {
//         $error_email = null;
//     }
//     // 通勤
//     if ($commute <= 0) {
//         $error_commute = '通勤時間は1以上にしてください';
//     } else {
//         $error_commute = null;
//     }
//     // 血液型
//     if ($blood_type === null) {
//         $error_blood_type = '血液型は必須です';
//     } else {
//         $error_blood_type = null;
//     }

//     return [$error_token, $error_name, $error_name_kana, $error_email, $error_commute, $error_blood_type];
// }

// 支店バリデーション
// function checkBranchData($branch_name, $ken_name, $city_name, $street_address, $phone_number, $sort_order)
// {
//     // トークン
//     if (
//         empty($_POST['token'])
//         || empty($_SESSION['token'])
//         || $_POST['token'] !== $_SESSION['token']
//     ) {
//         $error_token = 'トークンが一致しません';
//     } else {
//         $error_token = null;
//     }
//     // 支店名
//     if ($branch_name === null) {
//         $error_branch_name = '支店名は必須です';
//     } else {
//         $error_branch_name = null;
//     }
//     // 都道府県名
//     if ($ken_name === null) {
//         $error_ken_name = '都道府県名は必須です';
//     } else {
//         $error_ken_name = null;
//     }
//     // 市区町村
//     if ($city_name === null) {
//         $error_city_name = '市区町村は必須です';
//     } else {
//         $error_city_name = null;
//     }
//     // 字番地
//     if ($street_address === null) {
//         $error_street_address = '字番地は必須です';
//     } else {
//         $error_street_address = null;
//     }
//     // 電話番号
//     if ($phone_number === null) {
//         $error_phone_number = '電話番号は必須です';
//     } elseif (!preg_match('/\A\d{2,4}+-\d{2,4}+-\d{4}\z/', $phone_number)) {
//         $error_phone_number = '電話番号の形式が違います';
//     } else {
//         $error_phone_number = null;
//     }
//     // 並び順
//     if ($sort_order === null) {
//         $error_sort_order = '並び順は必須です';
//     } elseif ($sort_order <= 0) {
//         $error_sort_order = '並び順は0以上でお願いします';
//     } else {
//         $error_sort_order = null;
//     }

//     return [$error_token, $error_branch_name, $error_ken_name, $error_city_name, $error_street_address, $error_phone_number, $error_sort_order];
// }
