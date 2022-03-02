<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支店一覧</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <main>
        <!-- 共通メニュー -->
        <?php require('./views/menu.view.php'); ?>

        <h1 class="title">支店一覧</h1>

        <!-- 検索フォーム -->
        <div class="search-form">
            <form action="" method="get">
                <label for="branch_name">支店名</label>
                <input type="text" name="branch_name" id="branch_name" value="<?php if (isset($this->search['branch_name'])) { $this->escape($this->search['branch_name']); } ?>">
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
                            <th>支店名</th>
                            <th>電話番号</th>
                            <th>住所</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->branches as $branch) : ?>
                            <tr>
                                <!-- 支店名 -->
                                <td><?php $this->escape($branch->branch_name); ?></td>
                                <!-- 電話番号 -->
                                <td><?php $this->escape($branch->phone_number); ?></td>
                                <!-- 住所 -->
                                <td><?php $this->escape($branch->connectAddress()) ?></td>

                                <td><a class="edit-button" href="./branch_edit.php?id=<?php $this->escape($branch->id); ?>">編集</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- ページネーション -->
                <?php $this->paginator->show(); ?>
            <?php endif; ?>
            <div>
                <a href="./branch_add.php">追加</a>
            </div>
        </div>
    </main>
</body>
</html>