<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/employee.php');
require(__DIR__ . '/../varidators/employee-validator.php');

class EmployeeEditController extends BaseController
{
    public $employee;

    public function main()
    {
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
                $this->params[':id'] = $id;
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
                    $update_sql = "UPDATE employees SET name = :name, name_kana = :name_kana, sex = :sex, birthday = :birthday, email = :email, commute = :commute, blood_type = :blood_type, married = :married WHERE id = :id";
                    $update_stmt = $this->db->prepare($update_sql);
                    $update_stmt->execute($this->params);
                    // $this->sql->plural($update_sql, $this->params);
                    $this->db->commit();
                    $_SESSION['msg'] = '更新しました';
                    header("Location: ./edit.php?id={$id}");
                    exit;
                } catch (Exception $e) {
                    echo $e->getMessage();
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
            $this->params[':id'] = $id;
            if (isset($_GET['id']) && $_GET['id'] !== '') {
                //id一致のデータ取得
                $select_sql = "SELECT * FROM employees WHERE id = :id";
                $select_stmt = $this->db->prepare($select_sql);
                $select_stmt->execute($this->params);
                $employee_array = $select_stmt->fetch();
                // $employee_array = $this->sql->select($select_sql, $this->params);
                if (isset($employee_array)) {
                    $this->employee = new Employee($employee_array);
                }
            }
        }

        require("./views/edit.view.php");
    }
}