<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/employee.php');
require(__DIR__ . '/../varidators/employee-validator.php');
require(__DIR__ . '/../entities/sql.php');

class EmployeeAddController extends BaseController
{
    public $employee;

    public function main()
    {
        $sql = new Sql();
        $params = [];

        // 登録ボタン処理
        if (!empty($_POST['add'])) {

            $employee = new Employee($_POST);

            // 社員情報バリデーション
            $validator = new EmployeeValidator();
            $validator->validate($employee);
            if ($validator->valid) {
                // エラーなし
                //保存処理
                $params[':name'] = $employee->name;
                $params[':name_kana'] = $employee->name_kana;
                $params[':sex'] = $employee->sex;
                $params[':birthday'] = $employee->birthday;
                $params[':email'] = $employee->email;
                $params[':commute'] = $employee->commute;
                $params[':blood_type'] = $employee->blood_type;
                $params[':married'] = $employee->married;

                $this->db->beginTransaction();

                try {
                    $note = "INSERT INTO employees (name, name_kana, sex, birthday, email, commute, blood_type, married) VALUES (:name, :name_kana, :sex, :birthday, :email, :commute, :blood_type, :married)";
                    $sql->plural($note, $params);
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