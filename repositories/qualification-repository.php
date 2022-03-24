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

    public function add(array $new)
    {
        // 現在の資格一覧と送られてきた配列をみて差分をみる
        // 現在

        $old = $this->getAll();
        var_dump($old);die;

    }
}