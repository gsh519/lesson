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

                $this->db->beginTransaction();

                try {
                    $note = "INSERT INTO branches (branch_name, phone_number, ken_name, city_name, street_address, building_name, sort_order) VALUES (:branch_name, :phone_number, :ken_name, :city_name, :street_address, :building_name, :sort_order)";
                    $sql->plural($note, $params);
                    $this->db->commit();

                    $_SESSION['msg'] = '登録しました';
                    header("Location: ./branch_add.php");
                    exit;
                } catch (Exception $e) {
                    $_SESSION['msg'] = '更新できませんでした';
                    $this->branch = $branch;
                    $this->db->rollBack();
                }

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