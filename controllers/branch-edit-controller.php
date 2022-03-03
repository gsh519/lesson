<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/branch.php');
require(__DIR__ . '/../varidators/branch-validator.php');
require(__DIR__ . '/../repositories/branch-repository.php');

class BranchEditController extends BaseController
{

    public $branch;
    public $active_menu = 'branch-list';

    public function main()
    {
        if (isset($_GET['id']) && $_GET['id'] !== '') {
            $id = $_GET['id'];
        }

        // 更新処理
        if (!empty($_POST['edit'])) {
            $branch = new Branch($_POST);

            $validator = new BranchValidator();
            $validator->validate($branch);

            if ($validator->valid) {

                $branch->id = $id;
                $branch_repository = new BranchRepository($this->db);
                $success = $branch_repository->edit($branch);

                if ($success) {
                    $_SESSION['msg'] = '更新しました';
                    header("Location: ./branch_edit.php?id={$id}");
                    exit;
                } else {
                    $_SESSION['msg'] = '更新できませんでした';
                    $this->branch = $branch;
                }

            } else {
                $errors = $validator->errors;
                $this->branch = $branch;
            }
        } else {
            //id一致のデータ取得
            $branch_repository = new BranchRepository($this->db);
            $this->branch = $branch_repository->find($id);
        }

        require("./views/branch_edit.view.php");
    }
}