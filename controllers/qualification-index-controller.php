<?php
require(__DIR__ . '/base-controller.php');
require(__DIR__ . '/../entities/employee.php');
require(__DIR__ . '/../varidators/employee-validator.php');
require(__DIR__ . '/../repositories/employee-repository.php');
require(__DIR__ . '/../repositories/branch-repository.php');
require(__DIR__ . '/../repositories/qualification-repository.php');

class QualificationIndexController extends BaseController
{
    public $errors = [];
    public $qualifications = [];
    public $active_menu = 'qualification-list';

    public function __construct()
    {
        parent::__construct();
    }

    public function main()
    {
        $qualification_repository = new QualificationRepository($this->db);
        if (!empty($_POST['add'])) {
            // 更新・新規追加できるようにする
            $success = $qualification_repository->add($_POST['qualifications'], $_POST['new_qualification']);

            if ($success) {
                header("Location: ./qualification.php");
                exit;
            }
        } else {
            // 今ある資格一覧を表示
            $this->qualifications = $qualification_repository->getAll();
        }

        require('./views/qualification.view.php');
    }
}