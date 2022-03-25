<?php
class QualificationRepository
{
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * 資格一覧取得
     *
     * @return array
     */
    public function getAll() : array
    {
        $select_sql = "select * from qualifications";
        $select_stmt = $this->db->prepare($select_sql);
        $select_stmt->execute();
        $qualifications = $select_stmt->fetchAll(PDO::FETCH_ASSOC);
        return $qualifications;
    }

    /**
     * 新規追加・更新
     *
     * @param array $qualifications
     * @param string $new_qualification
     * @return boolean
     */
    public function add(array $qualifications, string $new_qualification) : bool
    {
        $update_params = [];
        $add_params = [];
        $delete_params = [];
        $params = [];

        $this->db->beginTransaction();

        try {
            // 更新処理
            foreach ($qualifications as $index => $qualification) {
                if ($qualification) {
                    // 既存の資格更新
                    $update_params[':id'] = $index;
                    $update_params[':qualification_name'] = $qualification;
                    $update_sql = "UPDATE qualifications SET qualification_name = :qualification_name WHERE id = :id";
                    $update_stmt = $this->db->prepare($update_sql);
                    $update_stmt->execute($update_params);
                } else {
                    // 空で入ってきたらその資格を削除
                    $delete_params[':id'] = $index;
                    $delete_sql = "DELETE FROM qualifications WHERE id = :id";
                    $delete_stmt = $this->db->prepare($delete_sql);
                    $delete_stmt->execute($delete_params);

                    // 削除した資格のidをもつemployees_qualificationsのデータを削除
                    $params[':qualification_id'] = $index;
                    $sql = "DELETE FROM employees_qualifications WHERE qualification_id = :qualification_id";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute($params);
                }
            }

            // 追加処理
            if ($new_qualification) {
                $add_params[':qualification_name'] = $new_qualification;
                $add_sql = "INSERT INTO qualifications (qualification_name) VALUES (:qualification_name)";
                $add_stmt = $this->db->prepare($add_sql);
                $add_stmt->execute($add_params);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
}