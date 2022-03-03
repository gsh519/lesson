<?php
require(__DIR__ . '/../controllers/base-controller.php');
require(__DIR__ . '/../entities/branch.php');
require(__DIR__ . '/../varidators/branch-validator.php');
require(__DIR__ . '/../modules/paginator.php');
require(__DIR__ . '/../repositories/branch-repository.php');

class BranchIndexController extends BaseController
{
    public $search = [];
    public $errors = [];
    public $branches = [];
    public $page = 1;
    public $paginator;
    public $active_menu = 'branch-list';

    public function __construct($data = [])
    {
        parent::__construct();
        $this->search['branch_name'] = $this->arrayGet($data, 'branch_name');
        $this->page = $this->arrayGet($data, 'page', 1);
    }

    public function main()
    {
        // 支店一覧取得
        $branch_repository = new BranchRepository($this->db);
        $this->branches = $branch_repository->getAll($this->search, $this->page);
        $branches_count = $branch_repository->count($this->search);

        if (empty($this->branches)) {
            $this->errors[] = '該当する支店がありません';
        }

        // ページネーション
        $this->paginator = new Paginator();
        $this->paginator->items_per_page = 5;
        $this->paginator->page = $this->page;
        $this->paginator->all_num = $branches_count;
        $this->paginator->search = $this->search;

        require("./views/branch_index.view.php");
    }
}