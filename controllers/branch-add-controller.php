<?php
require(__DIR__ . '/../controllers/base-controller.php');
require(__DIR__ . '/../entities/branch.php');
require(__DIR__ . '/../varidators/branch-validator.php');
require(__DIR__ . '/../repositories/branch-repository.php');

class BranchAddController extends BaseController
{
    public $branch;
    public $active_menu = 'branch-add';

    public function main()
    {
        // 登録ボタン処理
        if (!empty($_POST['add'])) {

            $branch = new Branch($_POST);

            $validator = new BranchValidator();
            $validator->validate($branch);
            if ($validator->valid) {
                $branch_repository = new BranchRepository($this->db);
                $success = $branch_repository->add($branch);

                if ($success) {
                    $_SESSION['msg'] = '登録しました';
                    header("Location: ./branch_add.php");
                    exit;
                } else {
                    $_SESSION['msg'] = '登録できませんでした';
                    $this->branch = $branch;
                }
            } else {
                $errors = $validator->errors;
                $this->branch = $branch;
            }

        } else {
            $this->branch = new Branch();
        }

        require("./views/branch_add.view.php");
    }
}