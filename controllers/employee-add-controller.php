<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/employee.php');
require(__DIR__ . '/../varidators/employee-validator.php');
require(__DIR__ . '/../repositories/employee-repository.php');

class EmployeeAddController extends BaseController
{
    public $employee;
    public $branches = [];
    public $qualifications = [];
    public $password = true;
    public $active_menu = 'employee-add';

    // 追加のとき バリデーションあり is_password true
    // 更新のとき 入力されていたら バリデーションあり is_password true
    // 更新のとき 空白なら バリデーションなし is_password false

    public function main()
    {
        // 登録ボタン処理
        if (!empty($_POST['add'])) {
            $employee = new Employee($_POST);

            // 社員情報バリデーション
            $validator = new EmployeeValidator();
            $validator->validate($employee);
            if ($validator->valid) {
                $employee_repository = new EmployeeRepository($this->db);
                $success = $employee_repository->add($employee);

                if ($success) {
                    $_SESSION['msg'] = '登録しました';
                    header("Location: ./add.php");
                    exit;
                } else {
                    $_SESSION['msg'] = '登録できませんでした';
                    $this->employee = new Employee($_POST);
                }
            } else {
                // エラーあり
                $errors = $validator->errors;
                $this->employee = $employee;
            }

        } else {
            $this->employee = new Employee();
        }

        $qualification_sql = "SELECT * FROM qualifications";
        $qualification_stmt = $this->db->prepare($qualification_sql);
        $qualification_stmt->execute();
        $this->qualifications = $qualification_stmt->fetchAll(PDO::FETCH_ASSOC);

        // セレクトボックス用選択肢取得
        $select_sql = "SELECT id, branch_name FROM branches ORDER BY sort_order ASC";
        $select_stmt = $this->db->prepare($select_sql);
        $select_stmt->execute();
        $this->branches = $select_stmt->fetchAll();

        require("./views/add.view.php");
    }
}