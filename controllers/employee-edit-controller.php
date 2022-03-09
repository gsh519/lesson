<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/employee.php');
require(__DIR__ . '/../varidators/employee-validator.php');
require(__DIR__ . '/../repositories/employee-repository.php');
require(__DIR__ . '/../repositories/branch-repository.php');

class EmployeeEditController extends BaseController
{
    public $employee;
    public $branches = [];
    public $active_menu = 'employee-list';

    public function main()
    {
        if (isset($_GET['id']) && $_GET['id'] !== '') {
            $id = $_GET['id'];
        }

        // 更新処理
        if (!empty($_POST['edit'])) {

            var_dump($_POST);die;
            $employee = new Employee($_POST);

            // 社員情報バリデーション
            $validator = new EmployeeValidator();
            $validator->validate($employee);
            if ($validator->valid) {
                // 社員情報取得
                $employee->id = $id;
                $employee_repository = new EmployeeRepository($this->db);
                $success = $employee_repository->edit($employee);

                if ($success) {
                    $_SESSION['msg'] = '更新しました';
                    header("Location: ./edit.php?id={$id}");
                    exit;
                } else {
                    $_SESSION['msg'] = '更新できませんでした';
                    $this->employee = $employee;
                }
            } else {
                $errors = $validator->errors;
                $this->employee = $employee;
            }

        } else {

            if (isset($_GET['id']) && $_GET['id'] !== '') {
                //id一致のデータ取得
                $employee_repository = new EmployeeRepository($this->db);
                $this->employee = $employee_repository->find($id);
            }
        }

        // 支店カテゴリ
        $select_sql = "SELECT id, branch_name FROM branches ORDER BY sort_order ASC";
        $select_stmt = $this->db->prepare($select_sql);
        $select_stmt->execute();
        $this->branches = $select_stmt->fetchAll();

        require("./views/edit.view.php");
    }
}