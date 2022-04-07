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
        // パスワード
        // 新規登録画面では入力必須
        // 編集画面では入力された場合のみ更新し、空白の場合は更新しない
        if ($employee->is_password) {
            if ($employee->password === null) {
                $this->errors[] = 'パスワードは必須です';
                $this->valid = false;
            } elseif (!preg_match('/^[a-z0-9]{8,}$/i', $employee->password)) {
                $this->errors[] = 'パスワードは半角英数字8文字以上です';
                $this->valid = false;
            }
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
