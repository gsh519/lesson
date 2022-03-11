<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/employee.php');
require(__DIR__ . '/../varidators/employee-validator.php');
require(__DIR__ . '/../repositories/employee-repository.php');
require(__DIR__ . '/../repositories/branch-repository.php');

class EmployeeTotalController extends BaseController
{
    public $errors = [];
    public $count_employees = [];
    public $count_branch_employees = [];
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
        $this->count_employees = $employee_repository->countEmployees();

        var_dump($this->count_employees);die;

        $all_count = 0;
        foreach ($this->count_employees as $count_employee) {
            $all_count += $count_employee['sex_count'];
        }

        //社員数合計
        $this->count_all = $employee_repository->count();
        // 部門別社員数
        $this->count_branch_employees = $employee_repository->countBranchEmployees();

        require('./views/total.view.php');
    }
}