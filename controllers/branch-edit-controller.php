<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/branch.php');
require(__DIR__ . '/../varidators/branch-validator.php');
// require(__DIR__ . '/../entities/sql.php');

class BranchEditController extends BaseController
{

    public $branch;

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
                $this->params[':id'] = $id;
                $this->params[':branch_name'] = $branch->branch_name;
                $this->params[':phone_number'] = $branch->phone_number;
                $this->params[':ken_name'] = $branch->ken_name;
                $this->params[':city_name'] = $branch->city_name;
                $this->params[':street_address'] = $branch->street_address;
                $this->params[':building_name'] = $branch->building_name;
                $this->params[':sort_order'] = $branch->sort_order;

                $this->db->beginTransaction();

                try {
                    $update_sql = "UPDATE branches SET branch_name = :branch_name, phone_number = :phone_number, ken_name = :ken_name, city_name = :city_name, street_address = :street_address, building_name = :building_name, sort_order = :sort_order WHERE id = :id";
                    $update_stmt = $this->db->prepare($update_sql);
                    $update_stmt->execute($this->params);
                    // $this->sql->plural($update_sql, $this->params);
                    $this->db->commit();
                    $_SESSION['msg'] = '更新しました';
                    header("Location: ./branch_edit.php?id={$id}");
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

            $this->params[':id'] = $id;
            if (isset($_GET['id']) && $_GET['id'] !== '') {
                //id一致のデータ取得
                $select_sql = "SELECT * FROM branches WHERE id = :id";
                $select_stmt = $this->db->prepare($select_sql);
                $select_stmt->execute($this->params);
                $branch_array = $select_stmt->fetch();
                // $branch_array = $this->sql->select($select_sql, $this->params);
                if (isset($branch_array)) {
                    $this->branch = new Branch($branch_array);
                }
            }
        }

        require("./views/branch_edit.view.php");
    }
}