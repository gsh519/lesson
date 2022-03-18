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
    public $qualifications = [];
    public $active_menu = 'employee-list';

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
                // 社員情報取得
                $employee->id = $id;
                $employee_repository = new EmployeeRepository($this->db);
                $edit_success = $employee_repository->edit($employee);

                if ($edit_success) {
                    $_SESSION['msg'] = '更新しました';
                    header("Location: ./edit.php?id={$id}");
                    exit;
                } else {
                    $_SESSION['msg'] = '更新できませんでした';
                    $this->employee = new Employee($_POST);
                }
            } else {
                $errors = $validator->errors;
                $this->employee = new Employee($_POST);
            }

        // 削除処理
        } elseif (!empty($_POST['delete'])) {
            $employee_repository = new EmployeeRepository($this->db);
            $delete_success = $employee_repository->delete($id);

            if ($delete_success) {
                $_SESSION['msg'] = '削除しました';
                header("Location: ./index.php");
                exit;
            } else {
                $_SESSION['msg'] = '削除できませんでした';
                $this->employee = $employee_repository->find($id);
            }
        } else {

            if (isset($_GET['id']) && $_GET['id'] !== '') {
                //id一致のデータ取得
                $employee_repository = new EmployeeRepository($this->db);
                $this->employee = $employee_repository->find($id);
            }
        }

        $qualification_sql = "SELECT * FROM qualifications";
        $qualification_stmt = $this->db->prepare($qualification_sql);
        $qualification_stmt->execute();
        $this->qualifications = $qualification_stmt->fetchAll(PDO::FETCH_ASSOC);

        // 支店カテゴリ
        $select_sql = "SELECT id, branch_name FROM branches ORDER BY sort_order ASC";
        $select_stmt = $this->db->prepare($select_sql);
        $select_stmt->execute();
        $this->branches = $select_stmt->fetchAll();



        require("./views/edit.view.php");
    }
}