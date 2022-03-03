<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/employee.php');
require(__DIR__ . '/../varidators/employee-validator.php');
require(__DIR__ . '/../modules/paginator.php');
require(__DIR__ . '/../repositories/employee-repository.php');
require(__DIR__ . '/../repositories/branch-repository.php');

class EmployeeIndexController extends BaseController
{
    public $search = [];
    public $errors = [];
    public $employees = [];
    public $branches = [];
    public $page = 1;
    public $paginator;
    public $active_menu = 'employee-list';

    public function __construct($data = [])
    {
        parent::__construct();
        // 初期値に値をセット
        $this->search['name'] = $this->arrayGet($data, 'name');
        $this->search['sex'] = $this->arrayGet($data, 'sex');
        $this->search['branch_id'] = $this->arrayGet($data, 'branch_id');
        $this->page = $this->arrayGet($data, 'page', 1);
    }

    public function main()
    {
        // 社員一覧取得
        $employee_repository = new EmployeeRepository($this->db);
        $this->employees = $employee_repository->get($this->search, $this->page);
        $employees_count = $employee_repository->count($this->search);

        // データがない場合エラー表示
        if (empty($this->employees)) {
            $this->errors[] = '該当する社員がいません';
        }

        // ページネーション
        $this->paginator = new Paginator();
        $this->paginator->items_per_page = 5;
        $this->paginator->page = $this->page;
        $this->paginator->all_num = $employees_count;
        $this->paginator->search = $this->search;

        // 支店カテゴリ
        $branch_repository = new BranchRepository($this->db);
        $this->branches = $branch_repository->get();

        require("./views/index.view.php");
    }
}