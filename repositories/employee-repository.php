<?php
class EmployeeRepository
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * 従業員一覧を取得
     *
     * @param array $search 検索条件
     * @param int $page ページ番号
     * @return array<Employee>
     */
    public function get(array $search = [], int $page = 1) : array
    {
        // 社員のデータ取得処理
        $sql_where = "WHERE 1 = 1 ";

        $params = [];

        //検索条件
        if ($search['name'] !== null) {
            $sql_where = $sql_where . "and ((name like :name) or (name_kana like :name)) ";
            $value = '%' . $search['name'] . '%';
            $params[":name"] = $value;
        }
        if ($search['sex'] !== null) {
            $sql_where = $sql_where . "and sex = :sex ";
            $params[":sex"] = $search['sex'];
        }
        if ($search['branch_id'] !== null) {
            $sql_where = $sql_where . "and branch_id = :branch_id ";
            $params[":branch_id"] = $search['branch_id'];
        }

        //メインデータ取得
        $select_sql = "SELECT employees.*, branches.branch_name FROM employees LEFT OUTER JOIN branches ON employees.branch_id = branches.id " . $sql_where;
        $start_no = (5 * $page) - 5;
        $select_sql = $select_sql . "limit 5 offset {$start_no}";
        $select_stmt = $this->db->prepare($select_sql);
        $select_stmt->execute($params);
        $employees_arrays = $select_stmt->fetchAll(PDO::FETCH_ASSOC);

        // データ格納
        $employees = [];
        foreach ($employees_arrays as $employee_array) {
            $employee = new Employee($employee_array);
            $employees[] = $employee;
        }

        return $employees;
    }

    /**
     * 社員の件数を取得
     *
     * @param array $search 検索条件
     * @return integer
     */
    public function count(array $search = []) : int
    {
        // 社員のデータ取得処理
        $sql_where = "WHERE 1 = 1 ";

        $params = [];

        //検索条件
        if (isset($search['name']) && $search['name'] !== '') {
            $sql_where = $sql_where . "and ((name like :name) or (name_kana like :name)) ";
            $value = '%' . $search['name'] . '%';
            $params[":name"] = $value;
        }
        if (isset($search['sex']) && $search['sex'] !== '') {
            $sql_where = $sql_where . "and sex = :sex ";
            $params[":sex"] = $search['sex'];
        }
        if (isset($search['branch_id']) && $search['branch_id'] !== '') {
            $sql_where = $sql_where . "and branch_id = :branch_id ";
            $params[":branch_id"] = $search['branch_id'];
        }

        //ページネーション用件数取得
        $count_sql = "SELECT count(*) FROM employees " . $sql_where;
        $count_stmt = $this->db->prepare($count_sql);
        $count_stmt->execute($params);
        $employees_count = $count_stmt->fetch();

        return $employees_count[0];
    }

    /**
     * 社員情報を追加
     *
     * @param Employee $employee
     * @return boolean
     */
    public function add (Employee $employee) : bool
    {
        $params = [];
        $params[':name'] = $employee->name;
        $params[':name_kana'] = $employee->name_kana;
        $params[':branch_id'] = $employee->branch_id;
        $params[':sex'] = $employee->sex;
        $params[':birthday'] = $employee->birthday;
        $params[':email'] = $employee->email;
        $params[':commute'] = $employee->commute;
        $params[':blood_type'] = $employee->blood_type;
        $params[':married'] = $employee->married;

        $this->db->beginTransaction();

        try {
            $insert_sql = "INSERT INTO employees (name, name_kana, branch_id, sex, birthday, email, commute, blood_type, married) VALUES (:name, :name_kana, :branch_id, :sex, :birthday, :email, :commute, :blood_type, :married)";
            $insert_stmt = $this->db->prepare($insert_sql);
            $insert_stmt->execute($params);
            $this->db->commit();
            return true;
        } catch(Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * 社員情報の更新
     *
     * @param Employee $employee
     * @return boolean
     */
    public function edit (Employee $employee) : bool
    {
        $params = [];
        $params[':id'] = $employee->id;
        $params[':name'] = $employee->name;
        $params[':name_kana'] = $employee->name_kana;
        $params[':branch_id'] = $employee->branch_id;
        $params[':sex'] = $employee->sex;
        $params[':birthday'] = $employee->birthday;
        $params[':email'] = $employee->email;
        $params[':commute'] = $employee->commute;
        $params[':blood_type'] = $employee->blood_type;
        $params[':married'] = $employee->married;

        $this->db->beginTransaction();

        try {
            $update_sql = "UPDATE employees SET name = :name, name_kana = :name_kana, branch_id = :branch_id, sex = :sex, birthday = :birthday, email = :email, commute = :commute, blood_type = :blood_type, married = :married WHERE id = :id";
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
     * id一致の社員情報を取得
     *
     * @param integer $id
     * @return Employee|null
     */
    public function find (int $id) : ?Employee
    {
        $params = [];
        $params[':id'] = $id;
        $select_sql = "SELECT * FROM employees WHERE id = :id";
        $select_stmt = $this->db->prepare($select_sql);
        $select_stmt->execute($params);
        $employee_array = $select_stmt->fetch();
        if ($employee_array) {
            $employee = new Employee($employee_array);
            return $employee;
        } else {
            return null;
        }
    }

    /**
     * 性別による社員数の取得
     *
     * @return array
     */
    public function countEmployees() : array
    {
        $count_sql =
        "SELECT
            case sex
                when 0 then '男性'
                when 1 then '女性'
                when 2 then '未登録'
                else '???'
            end as sex_label,
            sex,
            count(sex) as sex_count
        FROM
            employees
        GROUP BY
            sex";
        $count_stmt = $this->db->prepare($count_sql);
        $count_stmt->execute();
        $count_employees = $count_stmt->fetchAll(PDO::FETCH_ASSOC);
        return $count_employees;
    }

    /**
     * 部門別社員数の取得
     *
     * @return array
     */
    public function countBranchEmployees() : array
    {
        $count_sql =
        "SELECT
            t1.*,
            branches.branch_name
        FROM
            (select
                branch_id,
                count(branch_id) as employee_count
            from employees
            where branch_id is not null
            group by branch_id) as t1
        LEFT JOIN
            branches
        ON
            t1.branch_id = branches.id";
        $count_stmt = $this->db->prepare($count_sql);
        $count_stmt->execute();
        $count_employees = $count_stmt->fetchAll(PDO::FETCH_ASSOC);
        return $count_employees;
    }
}