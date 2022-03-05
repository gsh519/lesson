<div class="menu">
    <ul class="menu-list">
        <li><a class="<?php if ($this->active_menu === 'employee-list') { echo 'active'; } ?>" href="./">社員一覧</a></li>
        <li><a class="<?php if ($this->active_menu === 'employee-add') { echo 'active'; } ?>" href="../add.php">社員登録</a></li>
        <li><a class="<?php if ($this->active_menu === 'employee-total') { echo 'active'; } ?>" href="../total.php">社員集計</a></li>
        <li><a class="<?php if ($this->active_menu === 'branch-list') { echo 'active'; } ?>" href="../branch_index.php">支店一覧</a></li>
        <li><a class="<?php if ($this->active_menu === 'branch-add') { echo 'active'; } ?>" href="../branch_add.php">支店登録</a></li>
    </ul>
</div>
