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
        $sql_where = "WHERE is_deleted = 0 ORDER BY id DESC ";

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
        $sql_where = "WHERE is_deleted = 0 ";

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
        $params[':password'] = $employee->password;
        $params[':commute'] = $employee->commute;
        $params[':blood_type'] = $employee->blood_type;
        $params[':married'] = $employee->married;

        $this->db->beginTransaction();

        try {
            // 保有資格以外のデータ登録
            $insert_sql = "INSERT INTO employees (name, name_kana, branch_id, sex, birthday, email, password, commute, blood_type, married) VALUES (:name, :name_kana, :branch_id, :sex, :birthday, :email, :password, :commute, :blood_type, :married)";
            $insert_stmt = $this->db->prepare($insert_sql);
            $insert_stmt->execute($params);

            // 登録した社員のidを取得
            $id_sql = "SELECT id FROM employees ORDER BY id DESC LIMIT 1";
            $id_stmt = $this->db->prepare($id_sql);
            $id_stmt->execute();
            $id = $id_stmt->fetch(PDO::FETCH_ASSOC);

            // 保有資格の登録処理
            $qualification_params = [];
            $qualification_params[':employee_id'] = $id['id'];

            foreach ($employee->qualification_array as $qualification) {
                $qualification_params[':qualification_id'] = $qualification;
                $add_sql = "INSERT INTO employees_qualifications (employee_id, qualification_id) VALUES (:employee_id, :qualification_id)";
                $add_stmt = $this->db->prepare($add_sql);
                $add_stmt->execute($qualification_params);
            }

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
        $params[':password'] = $employee->password;
        $params[':commute'] = $employee->commute;
        $params[':blood_type'] = $employee->blood_type;
        $params[':married'] = $employee->married;

        $this->db->beginTransaction();

        try {
            // 保有資格以外のデータを更新
            if ($employee->password === null) {
                $update_sql = "UPDATE employees SET name = :name, name_kana = :name_kana, branch_id = :branch_id, sex = :sex, birthday = :birthday, email = :email, commute = :commute, blood_type = :blood_type, married = :married WHERE id = :id";
            } else {
                $update_sql = "UPDATE employees SET name = :name, name_kana = :name_kana, branch_id = :branch_id, sex = :sex, birthday = :birthday, email = :email, password = :password, commute = :commute, blood_type = :blood_type, married = :married WHERE id = :id";
            }
            $update_stmt = $this->db->prepare($update_sql);
            $update_stmt->execute($params);

            // employee_idと一致するemployees_qualificationsのデータを全て削除
            $qualification_params = [];
            $qualification_params[':employee_id'] = $employee->id;

            $delete_sql = "DELETE FROM employees_qualifications WHERE employee_id = :employee_id";
            $delete_stmt = $this->db->prepare($delete_sql);
            $delete_stmt->execute($qualification_params);

            // 新しく入ってきた保有資格の情報を追加する
            foreach ($employee->qualification_array as $qualification) {
                $qualification_params[':qualification_id'] = $qualification;
                $add_sql = "INSERT INTO employees_qualifications (employee_id, qualification_id) VALUES (:employee_id, :qualification_id)";
                $add_stmt = $this->db->prepare($add_sql);
                $add_stmt->execute($qualification_params);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * 社員情報の削除
     *
     * @param int $id
     * @return boolean
     */
    public function delete(int $id) : bool
    {
        $params = [];
        $params[':id'] = $id;

        $this->db->beginTransaction();

        try {
            $delete_sql = "UPDATE employees SET is_deleted = 1 WHERE id = :id";
            $delete_stmt = $this->db->prepare($delete_sql);
            $delete_stmt->execute($params);
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
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
        $select_sql = "SELECT * FROM employees WHERE id = :id and is_deleted = 0";
        $select_stmt = $this->db->prepare($select_sql);
        $select_stmt->execute($params);
        $employee_array = $select_stmt->fetch(PDO::FETCH_ASSOC);

        $qualification_sql = "SELECT q.qualification_id FROM (SELECT * FROM employees WHERE id = :id and is_deleted = 0) as e left join employees_qualifications as q on e.id = q.employee_id";
        // $qualification_sql = "SELECT qualification_id FROM employees_qualifications WHERE employee_id = :id";
        $qualification_stmt = $this->db->prepare($qualification_sql);
        $qualification_stmt->execute($params);
        $qualifications = $qualification_stmt->fetchAll(PDO::FETCH_ASSOC);
        $qualification_array = [];
        foreach ($qualifications as $qualification) {
            $qualification_array[] = $qualification['qualification_id'];
        }

        if ($employee_array) {
            $employee = new Employee($employee_array);
            $employee->qualification_array = $qualification_array;
            return $employee;
        } else {
            return null;
        }
    }

    /**
     * 性別による社員数の取得
     *
     * @param integer $number
     * @return array $count_employees[0]
     */
    public function countEmployees(int $number) : array
    {
        $params = [];
        $params[':sex'] = $number;
        $count_sql = "
                    select
                        count(sex) as count_sex
                    from
                        employees
                    where
                        sex = :sex
                    and is_deleted = 0";
        $count_stmt = $this->db->prepare($count_sql);
        $count_stmt->execute($params);
        $count_employees = $count_stmt->fetchAll(PDO::FETCH_ASSOC);
        return $count_employees[0];
    }

    /**
     * 部門別社員数の取得
     *
     * @return array
     */
    public function countBranchEmployees() : array
    {
        $count_sql = "
            select
                b.id,
                b.branch_name,
                count(e.id) as employee_count
            from branches b
            left join employees e
                on e.branch_id = b.id and e.is_deleted = 0
            group by b.id, b.branch_name
        ";
        $count_stmt = $this->db->prepare($count_sql);
        $count_stmt->execute();
        $count_employees = $count_stmt->fetchAll(PDO::FETCH_ASSOC);

        return $count_employees;
    }
}