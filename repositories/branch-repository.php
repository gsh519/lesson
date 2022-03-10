<?php
class BranchRepository
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * セレクトボックス用選択肢取得
     *
     * @param
     * @return array
     */
    public function get() : array
    {
        $select_branch_sql = "SELECT id, branch_name FROM branches ORDER BY sort_order ASC";
        $select_branch_stmt = $this->db->prepare($select_branch_sql);
        $select_branch_stmt->execute();
        $branches_arrays = $select_branch_stmt->fetchAll();
        $branches = [];
        foreach ($branches_arrays as $branch) {
            $branches[$branch['id']] = $branch['branch_name'];
        }

        return $branches;
    }

    /**
     * 支店一覧を取得
     *
     * @param array $search
     * @param integer $page
     * @return array<Branch>
     */
    public function getAll(array $search = [], int $page = 1) : array
    {
        //WHERE文の作成
        $sql_where = "WHERE 1 = 1 ";

        $params = [];

        //検索条件
        if ($search['branch_name'] !== null) {
            $sql_where = $sql_where . "and (branch_name like :branch_name) ";
            $value = '%' . $search['branch_name'] . '%';
            $params[":branch_name"] = $value;
        }

        //メインデータ取得
        $select_sql = "SELECT * FROM branches " . $sql_where . "ORDER BY sort_order ASC ";
        $start_no = (5 * $page) - 5;
        $select_sql = $select_sql . "limit 5 offset {$start_no}";
        $select_stmt = $this->db->prepare($select_sql);
        $select_stmt->execute($params);
        $branches_arrays = $select_stmt->fetchAll();

        $branches = [];
        foreach ($branches_arrays as $branch_array) {
            $branch = new Branch($branch_array);
            $branches[] = $branch;
        }

        return $branches;
    }

    /**
     * 支店の件数を取得
     *
     * @param array $search
     * @return integer
     */
    public function count(array $search = []) : int
    {
        //WHERE文の作成
        $sql_where = "WHERE 1 = 1 ";

        $params = [];

        //検索条件
        if ($search['branch_name'] !== null) {
            $sql_where = $sql_where . "and (branch_name like :branch_name) ";
            $value = '%' . $search['branch_name'] . '%';
            $params[":branch_name"] = $value;
        }

        $count_sql = "SELECT count(*) FROM branches " . $sql_where;
        $count_stmt = $this->db->prepare($count_sql);
        $count_stmt->execute($params);
        $branches_count = $count_stmt->fetch();
        return $branches_count[0];
    }

    /**
     * 支店情報を追加
     *
     * @param Branch $branch
     * @return boolean
     */
    public function add (Branch $branch) : bool
    {
        $params = [];
        $params[':branch_name'] = $branch->branch_name;
        $params[':phone_number'] = $branch->phone_number;
        $params[':ken_name'] = $branch->ken_name;
        $params[':city_name'] = $branch->city_name;
        $params[':street_address'] = $branch->street_address;
        $params[':building_name'] = $branch->building_name;
        $params[':sort_order'] = $branch->sort_order;

        $this->db->beginTransaction();

        try {
            $insert_sql = "INSERT INTO branches (branch_name, phone_number, ken_name, city_name, street_address, building_name, sort_order) VALUES (:branch_name, :phone_number, :ken_name, :city_name, :street_address, :building_name, :sort_order)";
            $insert_stmt = $this->db->prepare($insert_sql);
            $insert_stmt->execute($params);
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * 支店情報編集
     *
     * @param Branch $branch
     * @return boolean
     */
    public function edit(Branch $branch) : bool
    {
        $params = [];
        $params[':id'] = $branch->id;
        $params[':branch_name'] = $branch->branch_name;
        $params[':phone_number'] = $branch->phone_number;
        $params[':ken_name'] = $branch->ken_name;
        $params[':city_name'] = $branch->city_name;
        $params[':street_address'] = $branch->street_address;
        $params[':building_name'] = $branch->building_name;
        $params[':sort_order'] = $branch->sort_order;

        $this->db->beginTransaction();

        try {
            $update_sql = "UPDATE branches SET branch_name = :branch_name, phone_number = :phone_number, ken_name = :ken_name, city_name = :city_name, street_address = :street_address, building_name = :building_name, sort_order = :sort_order WHERE id = :id";
            $update_stmt = $this->db->prepare($update_sql);
            $update_stmt->execute($params);
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * id一致の支店取得
     *
     * @param integer $id
     * @return Branch|null
     */
    public function find(int $id) : ?Branch
    {
        $params = [];
        $params[':id'] = $id;
        $select_sql = "SELECT * FROM branches WHERE id = :id";
        $select_stmt = $this->db->prepare($select_sql);
        $select_stmt->execute($params);
        $branch_array = $select_stmt->fetch();
        if ($branch_array) {
            $branch = new Branch($branch_array);
            return $branch;
        } else {
            return null;
        }
    }

    /**
     * 支店の社員数取得
     *
     * @return array
     */
    // public function countBranchEmployees() : array
    // {
    //     $select_sql = "SELECT branch_name, count(branch_name) FROM branches GROUP BY branch_name";
    //     $select_stmt = $this->db->prepare($select_sql);
    //     $select_stmt->execute();
    //     $count_branches = $select_stmt->fetchAll();
    //     return $count_branches;
    // }
}