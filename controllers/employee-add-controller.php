<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/employee.php');
require(__DIR__ . '/../varidators/employee-validator.php');

class EmployeeAddController extends BaseController
{
    public $employee;

    public function main()
    {
        // 登録ボタン処理
        if (!empty($_POST['add'])) {
            $employee = new Employee($_POST);

            // 社員情報バリデーション
            $validator = new EmployeeValidator();
            $validator->validate($employee);
            if ($validator->valid) {
                // エラーなし
                //保存処理
                $this->params[':name'] = $employee->name;
                $this->params[':name_kana'] = $employee->name_kana;
                $this->params[':sex'] = $employee->sex;
                $this->params[':birthday'] = $employee->birthday;
                $this->params[':email'] = $employee->email;
                $this->params[':commute'] = $employee->commute;
                $this->params[':blood_type'] = $employee->blood_type;
                $this->params[':married'] = $employee->married;

                $this->db->beginTransaction();

                try {
                    $insert_sql = "INSERT INTO employees (name, name_kana, sex, birthday, email, commute, blood_type, married) VALUES (:name, :name_kana, :sex, :birthday, :email, :commute, :blood_type, :married)";
                    $insert_stmt = $this->db->prepare($insert_sql);
                    $insert_stmt->execute($this->params);
                    // $this->sql->plural($insert_sql, $this->params);
                    $this->db->commit();
                    $_SESSION['msg'] = '登録しました';
                    header("Location: ./add.php");
                    exit;
                } catch(Exception $e) {
                    $_SESSION['msg'] = '登録できませんでした';
                    $this->employee = $employee;
                    $this->db->rollBack();
                }

            } else {
                // エラーあり
                $errors = $validator->errors;
                $this->employee = $employee;
            }

        } else {
            $this->employee = new Employee();
        }

        require("./views/add.view.php");
    }
}