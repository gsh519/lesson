<?php
require(__DIR__ . '/../controllers/base-controller.php');
require(__DIR__ . '/../entities/branch.php');
require(__DIR__ . '/../varidators/branch-validator.php');
require(__DIR__ . '/../entities/sql.php');

class BranchIndexController extends BaseController
{

    public $search = [];
    public $page = 1;

    public function __construct($data = [])
    {
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

        $sql = new Sql();
        $errors = [];

        $pdo = $sql->dbConnect();

        //WHERE文の作成
        $sql_where = "WHERE 1 = 1 ";
        $param = [];

        //検索条件
        if ($this->search['branch_name'] !== null) {
            $sql_where = $sql_where . "and (branch_name like :branch_name) ";
            $value = '%' . $this->search['branch_name'] . '%';
            $param[":branch_name"] = $value;
        }


        //メインデータ取得
        $sql = "SELECT * FROM branches " . $sql_where . "ORDER BY sort_order ASC ";
        $start_no = (5 * $this->page) - 5;
        $sql = $sql . "limit 5 offset {$start_no}";

        $stmt = $pdo->prepare($sql);
        $res = $stmt->execute($param);

        if ($res) {
            $branches_arrays = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if (empty($branches_arrays)) {
            $errors[] = '該当する支店がありません';
        }

        $branches = [];
        foreach ($branches_arrays as $index => $branch_array) {
            $branch = new Branch($branch_array);
            $branches[] = $branch;
        }

        //全件数取得
        $count_sql = "SELECT count(*) FROM branches " . $sql_where;
        $count_stmt = $pdo->prepare($count_sql);
        $res = $count_stmt->execute($param);

        if ($res) {
            $branches_count = $count_stmt->fetch();
        }

        $branchesAll_num = $branches_count[0];

        //総ページ数
        $pagenum = ceil($branchesAll_num / 5);

        //○〜○件目
        $from = ($this->page - 1) * 5 + 1;
        if ($this->page == $pagenum) {
            $to = $branchesAll_num;
        } else {
            $to = $this->page * 5;
        }

        $stmt = null;
        $pdo = null;

        require("./views/branch_index.view.php");
    }
}