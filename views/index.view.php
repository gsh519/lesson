<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員一覧</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <main>
        <?php require('./views/menu.view.php'); ?>
        <h1 class="title"><a href="/">社員一覧</a></h1>
        <!-- 検索フォーム -->
        <div class="search-form">
            <form action="" method="get">
                <label for="name">氏名</label>
                <input type="text" name="name" id="name" value="<?php if (isset($this->search['name'])) { $this->escape($this->search['name']); } ?>">
                <label for="sex">性別</label>
                <select name="sex" id="sex">
                    <option value="">全て</option>
                    <option <?php if ($this->search['sex'] === '0') { echo 'selected'; } ?> value="0">男</option>
                    <option <?php if ($this->search['sex'] === '1') { echo 'selected'; } ?> value="1">女</option>
                    <option <?php if ($this->search['sex'] === '2') { echo 'selected'; } ?> value="2">不明</option>
                </select>
                <label for="branch_id">支店</label>
                <select name="branch_id" id="branch_id">
                    <option value="">全て</option>
                    <?php foreach ($this->branches as $id => $name) : ?>
                        <option <?php if ($this->search['branch_id'] == $id) { echo 'selected'; } ?> value="<?php $this->escape($id); ?>"><?php $this->escape($name); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">検索</button>
            </form>
        </div>

        <div class="content">
            <!-- エラー文 -->
            <?php if (!empty($this->errors)) : ?>
                <ul class="error-message">
                    <?php foreach ($this->errors as $error) : ?>
                        <li><?php $this->escape($error); ?></li>
                    <?php endforeach ?>
                </ul>
            <?php elseif (empty($this->errors)) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>氏名</th>
                            <th>かな</th>
                            <th>支店</th>
                            <th>性別</th>
                            <th>年齢</th>
                            <th>生年月日</th>
                            <th>メールアドレス</th>
                            <th>通勤時間</th>
                            <th>血液型</th>
                            <th>既婚</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->employees as $employee) : ?>
                            <tr>
                                <!-- 氏名 -->
                                <td><?php $this->escape($employee->name); ?></td>
                                <!-- かな -->
                                <td><?php $this->escape($employee->name_kana); ?></td>
                                <!-- 支店 -->
                                <td><?php $this->escape($employee->branch_name); ?></td>
                                <!-- 性別 -->
                                <td><?php $this->escape($employee->getSexLabel()); ?></td>
                                <!-- 年齢 -->
                                <td><?php $this->escape($employee->getAge()); ?></td>
                                <!-- 生年月日 -->
                                <td><?php $this->escape($employee->birthday); ?></td>
                                <!-- メールアドレス -->
                                <td><?php $this->escape($employee->email); ?></td>
                                <!-- 通勤時間 -->
                                <td><?php $this->escape($employee->getCommute()); ?></td>
                                <!-- 血液型 -->
                                <td><?php $this->escape($employee->getBlood_type()); ?></td>
                                <!-- 既婚 -->
                                <td><?php $this->escape($employee->getMarried()); ?></td>

                                <td><a class="edit-button" href="./edit.php?id=<?php $this->escape($employee->id); ?>">編集</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- ページネーション -->
                <?php $this->paginator->show(); ?>
            <?php endif; ?>
            <div>
                <a href="./add.php">追加</a>
            </div>
        </div>
    </main>
</body>
</html>