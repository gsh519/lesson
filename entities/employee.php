<?php

// 社員
class Employee
{
    public $id = null;
    public $name = null;
    public $name_kana = null;
    public $branch_id = null;
    public $branch_name = null;
    public $sex = null;
    public $birthday = null;
    public $email = null;
    public $commute = null;
    public $blood_type = null;
    public $married = null;
    public $qualification = ''; //カンマ区切り
    public $qualification_array = []; //配列

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
        if (isset($data['branch_id']) && $data['branch_id'] !== '') {
            $this->branch_id = $data['branch_id'];
        }
        if (isset($data['branch_name']) && $data['branch_name'] !== '') {
            $this->branch_name = $data['branch_name'];
        }
        if (isset($data['sex']) && $data['sex'] !== '') {
            $this->sex = $data['sex'];
        }
        if (isset($data['birthday']) && $data['birthday'] !== '') {
            $this->birthday = $data['birthday'];
        }
        if (isset($data['email']) && $data['email'] !== '') {
            $this->email = $data['email'];
        }
        if (isset($data['commute']) && $data['commute'] !== '') {
            $this->commute = $data['commute'];
        }
        if (isset($data['blood_type']) && $data['blood_type'] !== '') {
            $this->blood_type = $data['blood_type'];
        }
        if (isset($data['married']) && $data['married'] !== '') {
            $this->married = $data['married'];
        }
        if (isset($data['qualification']) && $data['qualification'] !== []) {
            $this->qualification = $data['qualification'];
            $this->qualification_array = explode(',', $data['qualification']);
        } elseif (isset($data['qualification_array']) && $data['qualification_array'] !== []) {
            $this->qualification = implode(',', $data['qualification_array']);
            $this->qualification_array = $data['qualification_array'];
        }
    }

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

    // 年齢
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

    // 配列で渡ってきた保有資格を文字列に変換
    /*
    public function ChangeStringQualification()
    {
        $this->qualification = join(",", $this->qualification);
        return $this->qualification;
    }
    */
}
