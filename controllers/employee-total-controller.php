<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/employee.php');
require(__DIR__ . '/../varidators/employee-validator.php');
require(__DIR__ . '/../repositories/employee-repository.php');
require(__DIR__ . '/../repositories/branch-repository.php');

class EmployeeTotalController extends BaseController
{
    public $errors = [];
    public $count_employees;
    public $count_branch_employees = [];
    public $array_sex = [];
    public $count_all= 0;
    public $active_menu = 'employee-total';

    public function __construct()
    {
        parent::__construct();
    }

    public function main()
    {
        $employee_repository = new EmployeeRepository($this->db);
        // 性別による社員数
        $this->array_sex = [
            0 => '男性',
            1 => '女性',
            2 => '未登録',
        ];

        foreach ($this->array_sex as $index => $sex) {
            $this->count_employees[] = $employee_repository->countEmployees($index);
        }

        //社員数合計
        $this->count_all = $employee_repository->count();
        // 部門別社員数
        $this->count_branch_employees = $employee_repository->countBranchEmployees();

        require('./views/total.view.php');
    }
}