<?php
require(__DIR__ . '/../controllers/base-controller.php');
require(__DIR__ . '/../entities/branch.php');
require(__DIR__ . '/../varidators/branch-validator.php');

class BranchIndexController extends BaseController
{
    public $page = 1;
    public $search = [];
    public $errors = [];
    public $branches = [];

    public function __construct($data = [])
    {
        parent::__construct();
        if (isset($data['branch_name']) && $data['branch_name'] !== '') {
            $this->search['branch_name'] = $data['branch_name'];
        } else {
            $this->search['branch_name'] = null;
        }
        if (isset($data['page']) && $data['page'] !== '') {
            $this->page = $data['page'];
        }
    }

    public function main()
    {
        //WHERE文の作成
        $sql_where = "WHERE 1 = 1 ";

        //検索条件
        if ($this->search['branch_name'] !== null) {
            $sql_where = $sql_where . "and (branch_name like :branch_name) ";
            $value = '%' . $this->search['branch_name'] . '%';
            $this->params[":branch_name"] = $value;
        }

        //メインデータ取得
        $select_sql = "SELECT * FROM branches " . $sql_where . "ORDER BY sort_order ASC ";
        $start_no = (5 * $this->page) - 5;
        $select_sql = $select_sql . "limit 5 offset {$start_no}";
        $select_stmt = $this->db->prepare($select_sql);
        $select_stmt->execute($this->params);
        $branches_arrays = $select_stmt->fetchAll();
        // $branches_arrays = $this->sql->selectAll($select_sql, $this->params);

        if (empty($branches_arrays)) {
            $this->errors[] = '該当する支店がありません';
        }

        foreach ($branches_arrays as $branch_array) {
            $branch = new Branch($branch_array);
            $this->branches[] = $branch;
        }

        //全件の数取得
        $count_sql = "SELECT count(*) FROM branches " . $sql_where;
        $count_stmt = $this->db->prepare($count_sql);
        $count_stmt->execute($this->params);
        $branches_count = $count_stmt->fetch();
        $branchesAll_num = $branches_count[0];
        // $branches_count = $this->sql->select($count_sql, $this->params);

        //総ページ数
        $pagenum = ceil($branchesAll_num / 5);

        //○〜○件目
        $from = ($this->page - 1) * 5 + 1;
        if ($this->page == $pagenum) {
            $to = $branchesAll_num;
        } else {
            $to = $this->page * 5;
        }

        require("./views/branch_index.view.php");
    }
}