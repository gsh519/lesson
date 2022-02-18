<?php
require('./varidators/base-validator.php');

class EmployeeValidator extends BaseValidator
{
    public function validate($employee)
    {
        // 氏名
        if ($employee->name === null) {
            $this->errors[] = '氏名は必須です';
            $this->valid = false;
        }
        // かな
        if ($employee->name_kana === null) {  
            $this->errors[] = 'かなは必須です';
            $this->valid = false;
        }
        // メールアドレス
        if ($employee->email === null) {
            $this->errors[] = 'メールアドレスは必須です';
            $this->valid = false;
        } elseif (!filter_var($employee->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'メールアドレスの形式が間違っています';
            $this->valid = false;
        }
        // 通勤
        if ($employee->commute <= 0) {
            $this->errors[] = '通勤時間は1以上にしてください';
            $this->valid = false;
        }
        // 血液型
        if ($employee->blood_type === null) {
            $this->errors[] = '血液型は必須です';
            $this->valid = false;
        }
    }
}
