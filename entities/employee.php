<?php

// 社員
class Employee
{
    public $id = null;
    public $name = null;
    public $name_kana = null;
    public $sex = null;
    public $birthday = null;
    public $email = null;
    public $commute = null;
    public $blood_type = null;
    public $married = null;
    // public $params = [];

    public function __construct($data = [])
    {
        if (isset($data['id']) && $data['id'] !== '') {
            $this->id = $data['id'];
        }
        if (isset($data['name']) && $data['name'] !== '') {
            $this->name = $data['name'];
        }
        if (isset($data['name_kana']) && $data['name_kana'] !== '') {
            $this->name_kana = $data['name_kana'];
        }
        if (isset($data['sex']) && $data['sex'] !== '') {
            $this->sex = $data['sex'];
            // $this->params[':sex'] = $data['sex'];
        }
        if (isset($data['birthday']) && $data['birthday'] !== '') {
            $this->birthday = $data['birthday'];
            // $this->params[':birthday'] = $data['birthday'];
        }
        if (isset($data['email']) && $data['email'] !== '') {
            $this->email = $data['email'];
            // $this->params[':email'] = $data['email'];
        }
        if (isset($data['commute']) && $data['commute'] !== '') {
            $this->commute = $data['commute'];
            // $this->params[':commute'] = $data['commute'];
        }
        if (isset($data['blood_type']) && $data['blood_type'] !== '') {
            $this->blood_type = $data['blood_type'];
            // $this->params[':blood_type'] = $data['blood_type'];
        }
        if (isset($data['married']) && $data['married'] !== '') {
            $this->married = $data['married'];
            // $this->params[':married'] = $data['married'];
        }
    }

    // 社員バリデーション
    // public function checkEmployeeData($name, $name_kana, $email, $commute, $blood_type)
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

    // 性別判定
    public function getSexLabel()
    {
        if ($this->sex === '0') {
            return '男';
        } elseif ($this->sex === '1') {
            return '女';
        } elseif ($this->sex === '2') {
            return '不明';
        }
    }

    //年齢
    public function getAge() 
    {
        $now = date('Ymd');
        $employee['birthday'] = str_replace("-", "", $this->birthday);
        $age = floor(($now - $employee['birthday']) / 10000);
        return $age;
    }

    // 通勤時間
    public function getCommute()
    {
        if (isset($this->commute) && $this->commute !== '') {
            $commute = $this->commute . '分';
            return $commute;
        }
    }

    // 血液型判定
    public function getBlood_type()
    {
        if ($this->blood_type === '0') {
            return'不明';
        } elseif ($this->blood_type === '1') {
            return 'A型';
        } elseif ($this->blood_type === '2') {
            return 'B型';
        } elseif ($this->blood_type === '3') {
            return 'O型';
        } elseif ($this->blood_type === '4') {
            return 'AB型';
        }
    }

    // 既婚判定
    public function getMarried()
    {
        if ($this->married === null || $this->married === '0') {
            return '未婚';
        } elseif ($this->married === '1') {
            return '既婚';
        }
    }
}
