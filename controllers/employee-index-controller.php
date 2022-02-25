<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/employee.php');
require(__DIR__ . '/../varidators/employee-validator.php');
require(__DIR__ . '/../modules/paginator.php');

class EmployeeIndexController extends BaseController
{
    public $search = [];
    public $errors = [];
    public $employees = [];
    public $branches = [];
    public $page = 1;
    public $paginator;

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
        // 社員のデータ取得処理
        $sql_where = "WHERE 1 = 1 ";

        //検索条件
        if ($this->search['name'] !== null) {
            $sql_where = $sql_where . "and ((name like :name) or (name_kana like :name)) ";
            $value = '%' . $this->search['name'] . '%';
            $this->params[":name"] = $value;
        }
        if ($this->search['sex'] !== null) {
            $sql_where = $sql_where . "and sex = :sex ";
            $this->params[":sex"] = $this->search['sex'];
        }
        if ($this->search['branch_id'] !== null) {
            $sql_where = $sql_where . "and branch_id = :branch_id ";
            $this->params[":branch_id"] = $this->search['branch_id'];
        }

        //メインデータ取得
        $select_sql = "SELECT * FROM employees " . $sql_where;
        $start_no = (5 * $this->page) - 5;
        $select_sql = $select_sql . "limit 5 offset {$start_no}";
        $select_stmt = $this->db->prepare($select_sql);
        $select_stmt->execute($this->params);
        $employees_arrays = $select_stmt->fetchAll();

        // データがない場合エラー表示
        if (empty($employees_arrays)) {
            $this->errors[] = '該当する社員がいません';
        }

        // データ格納
        foreach ($employees_arrays as $employee_array) {
            $employee = new Employee($employee_array);
            $this->employees[] = $employee;
        }

        //全件のデータ数を取得
        $count_sql = "SELECT count(*) FROM employees " . $sql_where;
        $count_stmt = $this->db->prepare($count_sql);
        $count_stmt->execute($this->params);
        $employees_count = $count_stmt->fetch();

        // ページネーション
        $this->paginator = new Paginator();
        $this->paginator->items_per_page = 5;
        $this->paginator->page = $this->page;
        $this->paginator->all_num = $employees_count[0];
        $this->paginator->search = $this->search;

        //支店データ取得
        $select_branch_sql = "SELECT id, branch_name FROM branches ORDER BY sort_order ASC";
        $select_branch_stmt = $this->db->prepare($select_branch_sql);
        $select_branch_stmt->execute();
        $branches = $select_branch_stmt->fetchAll();
        foreach ($branches as $branch) {
            $this->branches[$branch['id']] = $branch['branch_name'];
        }

        require("./views/index.view.php");
    }
}