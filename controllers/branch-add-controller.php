<?php
require(__DIR__ . '/../controllers/base-controller.php');
require(__DIR__ . '/../entities/branch.php');
require(__DIR__ . '/../varidators/branch-validator.php');
require(__DIR__ . '/../entities/sql.php');

class BranchAddController extends BaseController
{

    public $branch;

    public function main()
    {

        $sql = new Sql();
        $params = [];

        // 登録ボタン処理
        if (!empty($_POST['add'])) {

            $branch = new Branch($_POST);

            $validator = new BranchValidator();
            $validator->validate($branch);
            if ($validator->valid) {
                // エラーなし
                //保存処理
                $params['branch_name'] = $branch->branch_name;
                $params['phone_number'] = $branch->phone_number;
                $params['ken_name'] = $branch->ken_name;
                $params['city_name'] = $branch->city_name;
                $params['street_address'] = $branch->street_address;
                $params['building_name'] = $branch->building_name;
                $params['sort_order'] = $branch->sort_order;

                $note = "INSERT INTO branches (branch_name, phone_number, ken_name, city_name, street_address, building_name, sort_order) VALUES (:branch_name, :phone_number, :ken_name, :city_name, :street_address, :building_name, :sort_order)";

                $res = $sql->plural($note, $params);

                if ($res) {
                    $_SESSION['success_msg'] = '登録しました';
                }

                $stmt = null;
                $pdo = null;

                header("Location: ./branch_add.php");
                exit;
            } else {
                // エラーあり
                $errors = $validator->errors;
                $this->branch = $branch;
            }

        } else {
            $this->branch = new Branch();
        }

        require("./views/branch_add.view.php");
    }
}