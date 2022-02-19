<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/employee.php');
require(__DIR__ . '/../varidators/employee-validator.php');
require(__DIR__ . '/../entities/sql.php');

class EmployeeIndexController extends BaseController
{

    public $search = [];
    public $page = 1;

    public function __construct($data = [])
    {
        // 初期値に値をセット
        if (isset($data['name']) && $data['name'] !== '') {
            $this->search['name'] = $data['name'];
        } else {
            $this->search['name'] = null;
        }
        if (isset($data['sex']) && $data['sex'] !== '') {
            $this->search['sex'] = $data['sex'];
        } else {
            $this->search['sex'] = null;
        }
        if (isset($data['page']) && $data['page'] !== '') {
            $this->page = $data['page'];
        }

    }

    public function main()
    {

        // 初期値
        $sql = new Sql();
        $errors = [];



        // 社員のデータ取得処理
        $pdo = $sql->dbConnect();
        //WHERE文の作成
        $sql_where = "WHERE 1 = 1 ";
        $param = [];

        //検索条件
        if ($this->search['name'] !== null) {
            $sql_where = $sql_where . "and ((name like :name) or (name_kana like :name)) ";

            $value = '%' . $this->search['name'] . '%';
            $param[":name"] = $value;
        }

        if ($this->search['sex'] !== null) {
            $sql_where = $sql_where . "and sex = :sex ";

            $param[":sex"] = $this->search['sex'];
        }

        //メインデータ取得
        $sql = "SELECT * FROM employees " . $sql_where;
        $start_no = (5 * $this->page) - 5;
        $sql = $sql . "limit 5 offset {$start_no}";

        $stmt = $pdo->prepare($sql);
        $res = $stmt->execute($param);

        if ($res) {
            $employees_arrays = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if (empty($employees_arrays)) {
            $errors[] = '該当する社員がいません';
        }

        $employees = [];
        foreach ($employees_arrays as $index => $employee_array) {
            $employee = new Employee($employee_array);
            $employees[] = $employee;
        }




        //全件のデータ数を値を取得
        $count_sql = "SELECT count(*) FROM employees " . $sql_where;
        $count_stmt = $pdo->prepare($count_sql);
        $res = $count_stmt->execute($param);

        if ($res) {
            $employees_count = $count_stmt->fetch();
        }
        $employeesAll_num = $employees_count[0];
        //総ページ数
        $pagenum = ceil($employeesAll_num / 5);

        //○〜○件目
        $from = ($this->page - 1) * 5 + 1;
        if ($this->page == $pagenum) {
            $to = $employeesAll_num;
        } else {
            $to = $this->page * 5;
        }

        $stmt = null;
        $pdo = null;





        require("./views/index.view.php");
    }
}