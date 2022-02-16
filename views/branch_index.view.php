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
                <input type="text" name="branch_name" id="branch_name" value="<?php if (isset($search['branch_name'])) { echo htmlspecialchars($search['branch_name'], ENT_QUOTES); } ?>">
                <button type="submit">検索</button>
            </form>
        </div>

        <div class="content">
            <!-- エラー文 -->
            <?php if (!empty($errors)) : ?>
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES); ?></li>
                    <?php endforeach ?>
                </ul>
            <?php elseif (empty($errors)) : ?>
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
                        <?php foreach ($branches as $branch) : ?>
                            <tr>
                                <!-- 支店名 -->
                                <td><?php echo htmlspecialchars($branch->branch_name, ENT_QUOTES); ?></td>
                                <!-- 電話番号 -->
                                <td><?php echo htmlspecialchars($branch->phone_number, ENT_QUOTES); ?></td>
                                <!-- 住所 -->
                                <td><?php echo htmlspecialchars($branch->connectAddress(), ENT_QUOTES) ?></td>
                                
                                <td><a class="edit-button" href="./branch_edit.php?id=<?php echo htmlspecialchars($branch->id, ENT_QUOTES); ?>">編集</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- ページネーション -->
                <?php echo htmlspecialchars($branchesAll_num, ENT_QUOTES); ?>件中 <?php echo htmlspecialchars($from, ENT_QUOTES); ?>-<?php echo htmlspecialchars($to, ENT_QUOTES); ?>件目を表示
                <?php if ($pagenum >= 2) : ?>
                    <?php if ($page >= 2) : ?>
                        <a href="?page=<?php echo htmlspecialchars(($page - 1), ENT_QUOTES); ?>">前へ</a>
                    <?php else : ?>
                        <a class="not-click">前へ</a>
                    <?php endif; ?>
                    <?php for ($i = $page - 2; $i < ($page + 3); $i++) : ?>
                        <?php if ($i >= 1 && $i <= $pagenum) : ?>
                            <?php if ($i == $page) : ?>
                                <a class="not-click"><?php echo htmlspecialchars($i, ENT_QUOTES); ?></a>
                            <?php else : ?>
                                <a href="?page=<?php echo htmlspecialchars($i, ENT_QUOTES); ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($page < $pagenum) : ?>
                        <a href="?page=<?php echo htmlspecialchars(($page + 1), ENT_QUOTES); ?>">次へ</a>
                    <?php else : ?>
                        <a class="not-click">次へ</a>
                    <?php endif; ?>
                <?php endif; ?>

            <?php endif; ?>
            <div>
                <a href="./branch_add.php">追加</a>
            </div>
        </div>
    </main>
</body>
</html>