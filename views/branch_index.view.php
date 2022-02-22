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
        <h1 class="title">支店一覧</h1>

        <!-- 検索フォーム -->
        <div class="serch-form">
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
                <?php /* $this->escape($branchesAll_num); ?>件中 <?php $this->escape($from); ?>-<?php $this->escape($to); ?>件目を表示
                <?php if ($pagenum >= 2) : ?>
                    <?php if ($this->page >= 2) : ?>
                        <a href="?page=<?php $this->escape(($this->page - 1)); ?>&branch_name=<?php $this->escape($this->search['branch_name']); ?>">前へ</a>
                    <?php else : ?>
                        <a class="not-click">前へ</a>
                    <?php endif; ?>
                    <?php for ($i = $this->page - 2; $i < ($this->page + 3); $i++) : ?>
                        <?php if ($i >= 1 && $i <= $pagenum) : ?>
                            <?php if ($i == $this->page) : ?>
                                <a class="not-click"><?php $this->escape($i); ?></a>
                            <?php else : ?>
                                <a href="?page=<?php $this->escape($i); ?>&branch_name=<?php $this->escape($this->search['branch_name']); ?>"><?php $this->escape($i); ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($this->page < $pagenum) : ?>
                        <a href="?page=<?php $this->escape(($this->page + 1)); ?>&branch_name=<?php $this->escape($this->search['branch_name']); ?>">次へ</a>
                    <?php else : ?>
                        <a class="not-click">次へ</a>
                    <?php endif; ?>
                <?php endif; */ ?>

            <?php endif; ?>
            <div>
                <a href="./branch_add.php">追加</a>
            </div>
        </div>
    </main>
</body>
</html>