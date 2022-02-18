<?php
require('./varidators/base-validator.php');

class BranchValidator extends BaseValidator
{
    public function validate($branch)
    {
        // 支店名
        if ($branch->branch_name === null) {
            $this->errors[] = '支店名は必須です';
            $this->valid = false;
        }
        // 都道府県名
        if ($branch->ken_name === null) {
            $this->errors[] = '都道府県名は必須です';
            $this->valid = false;
        }
        // 市区町村
        if ($branch->city_name === null) {
            $this->errors[] = '市区町村は必須です';
            $this->valid = false;
        }
        // 字番地
        if ($branch->street_address === null) {
            $this->errors[] = '字番地は必須です';
            $this->valid = false;
        }
        // 電話番号
        if ($branch->phone_number === null) {
            $this->errors[] = '電話番号は必須です';
            $this->valid = false;
        } elseif (!preg_match('/\A\d{2,4}+-\d{2,4}+-\d{4}\z/', $branch->phone_number)) {
            $this->errors[] = '電話番号の形式が違います';
            $this->valid = false;
        }
        // 並び順
        if ($branch->sort_order === null) {
            $this->errors[] = '並び順は必須です';
            $this->valid = false;
        } elseif ($branch->sort_order <= 0) {
            $this->errors[] = '並び順は0以上でお願いします';
            $this->valid = false;
        }
    }
}