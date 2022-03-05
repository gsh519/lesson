<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/employee.php');
require(__DIR__ . '/../varidators/employee-validator.php');
require(__DIR__ . '/../repositories/employee-repository.php');
require(__DIR__ . '/../repositories/branch-repository.php');

class EmployeeTotalController extends BaseController
{
    public $errors = [];
    public $count_male = 0;
    public $count_female = 0;
    public $count_unregistered = 0;
    public $count_all = 0;
    public $active_menu = 'employee-total';

    public function __construct()
    {
        parent::__construct();
    }

    public function main()
    {
        $male = 0;
        $female = 1;
        $unregistered = 2;

        $employee_repository = new EmployeeRepository($this->db);
        // 男性の社員数
        $this->count_male = $employee_repository->countEmployee($male);
        // 女性の社員数
        $this->count_female = $employee_repository->countEmployee($female);
        // 未登録の社員数
        $this->count_unregistered = $employee_repository->countEmployee($unregistered);
        //社員数合計
        $this->count_all = $employee_repository->count();

        require('./views/total.view.php');
    }
}