<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/employee.php');
require(__DIR__ . '/../varidators/employee-validator.php');
require(__DIR__ . '/../entities/sql.php');

class EmployeeEditController extends BaseController
{
    public $employee;

    public function main()
    {

        $sql = new Sql();
        $params = [];

        if (isset($_GET['id']) && $_GET['id'] !== '') {
            $id = $_GET['id'];
        }

        // 更新処理
        if (!empty($_POST['edit'])) {

            $employee = new Employee($_POST);

            // 社員情報バリデーション
            $validator = new EmployeeValidator();
            $validator->validate($employee);
            if ($validator->valid) {
                // エラーなし
                $params[':id'] = $id;
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
                    $note = "UPDATE employees SET name_kana = :name_kana, sex = :sex, birthday = :birthday, email = :email, commute = :commute, blood_type = :blood_type, married = :married WHERE id = :id";
                    $sql->plural($note, $params);
                    $this->db->commit();

                    $_SESSION['msg'] = '更新しました';
                    header("Location: ./edit.php?id={$id}");
                    exit;
                } catch (Exception $e) {
                    $_SESSION['msg'] = '更新できませんでした';
                    $this->employee = $employee;
                    $this->db->rollBack();
                }

            } else {
                // エラーあり
                $errors = $validator->errors;
                $this->employee = $employee;
            }

        } else {
            $params[':id'] = $id;
            if (isset($_GET['id']) && $_GET['id'] !== '') {
                //id一致のデータ取得
                $note = "SELECT * FROM employees WHERE id = :id";
                $employee_array = $sql->select($note, $params);
                if (isset($employee_array)) {
                    $this->employee = new Employee($employee_array);
                }
            }
        }

        require("./views/edit.view.php");
    }
}