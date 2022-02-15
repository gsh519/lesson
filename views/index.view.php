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
        <h1 class="title">社員一覧</h1>

        <!-- 検索フォーム -->
        <div class="serch-form">
            <form action="" method="get">
                <label for="name">氏名</label>
                <input type="text" name="name" id="name" value="<?php if (isset($search['name'])) { echo htmlspecialchars($search['name'], ENT_QUOTES); } ?>">
                <label for="sex">性別</label>
                <select name="sex" id="sex">
                    <option value="">全て</option>
                    <option <?php if ($search['sex'] === '0') { echo 'selected'; } ?> value="0">男</option>
                    <option <?php if ($search['sex'] === '1') { echo 'selected'; } ?> value="1">女</option>
                    <option <?php if ($search['sex'] === '2') { echo 'selected'; } ?> value="2">不明</option>
                </select>
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
                            <th>氏名</th>
                            <th>かな</th>
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
                        <?php foreach ($employees as $employee) : ?>
                            <tr>
                                <!-- 氏名 -->
                                <td><?php echo htmlspecialchars($employee->name, ENT_QUOTES); ?></td>
                                <!-- かな -->
                                <td><?php echo htmlspecialchars($employee->name_kana, ENT_QUOTES); ?></td>
                                <!-- 性別 -->
                                <td><?php echo htmlspecialchars($employee->getSexLabel(), ENT_QUOTES); ?></td>
                                <!-- 年齢 -->
                                <td><?php echo htmlspecialchars($employee->getAge(), ENT_QUOTES); ?></td>
                                <!-- 生年月日 -->
                                <td><?php echo htmlspecialchars($employee->birthday, ENT_QUOTES); ?></td>
                                <!-- メールアドレス -->
                                <td><?php echo htmlspecialchars($employee->email, ENT_QUOTES); ?></td> 
                                <!-- 通勤時間 -->
                                <td><?php echo htmlspecialchars($employee->getCommute(), ENT_QUOTES); ?></td> 
                                <!-- 血液型 -->
                                <td><?php echo htmlspecialchars($employee->getBlood_type(), ENT_QUOTES); ?></td> 
                                <!-- 既婚 -->
                                <td><?php echo htmlspecialchars($employee->getMarried(), ENT_QUOTES); ?></td> 
                                
                                <td><a class="edit-button" href="./edit.php?id=<?php echo htmlspecialchars($employee->id, ENT_QUOTES); ?>">編集</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- ページネーション -->
                <?php echo htmlspecialchars($employeesAll_num, ENT_QUOTES); ?>件中 <?php echo htmlspecialchars($from, ENT_QUOTES); ?>-<?php echo htmlspecialchars($to, ENT_QUOTES); ?>件目を表示
                <?php if ($pagenum >= 2) : ?>
                    <?php if ($page >= 2) : ?>
                        <a href="?page=<?php echo htmlspecialchars(($page - 1), ENT_QUOTES); ?>&name=<?php echo htmlspecialchars($search['name'], ENT_QUOTES); ?>&sex=<?php echo htmlspecialchars($search['sex'], ENT_QUOTES); ?>">前へ</a>
                    <?php else : ?>
                        <a class="not-click">前へ</a>
                    <?php endif; ?>
                    <?php for ($i = $page - 2; $i < ($page + 3); $i++) : ?>
                        <?php if ($i >= 1 && $i <= $pagenum) : ?>
                            <?php if ($i == $page) : ?>
                                <a class="not-click"><?php echo htmlspecialchars($i, ENT_QUOTES); ?></a>
                            <?php else : ?>
                                <a href="?page=<?php echo htmlspecialchars($i, ENT_QUOTES); ?>&name=<?php echo htmlspecialchars($search['name'], ENT_QUOTES); ?>&sex=<?php echo htmlspecialchars($search['sex'], ENT_QUOTES); ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($page < $pagenum) : ?>
                        <a href="?page=<?php echo htmlspecialchars(($page + 1), ENT_QUOTES); ?>&name=<?php echo htmlspecialchars($search['name'], ENT_QUOTES); ?>&sex=<?php echo htmlspecialchars($search['sex'], ENT_QUOTES); ?>">次へ</a>
                    <?php else : ?>
                        <a class="not-click">次へ</a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <div>
                <a href="./add.php">追加</a>
            </div>
        </div>
    </main>
</body>
</html>