<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員集計</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <main>
        <?php require('./views/menu.view.php'); ?>
        <h1 class="title"><a href="/">社員集計</a></h1>

        <div class="content">
            <!-- エラー文 -->
            <?php if (!empty($this->errors)) : ?>
                <ul class="error-message">
                    <?php foreach ($this->errors as $error) : ?>
                        <li><?php $this->escape($error); ?></li>
                    <?php endforeach ?>
                </ul>
            <?php elseif (empty($this->errors)) : ?>
            <div>
                <h2>表１：男女別社員数</h2>
                <table>
                    <thead>
                        <tr>
                            <th><strong>性別</strong></th>
                            <th>社員数</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($this->count_employees as $employee) : ?>
                            <tr>
                                <td><strong><?php $this->escape($employee['sex_label']) ?></strong></td>
                                <td><?php $this->escape($employee['sex_count']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>合計</strong></td>
                            <td><?php $this->escape($this->count_all); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div>
                <h2>表２：部門別社員数</h2>
                <table>
                    <thead>
                        <tr>
                            <th><strong>部門</strong></th>
                            <th>社員数</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->count_branch_employees as $employee) : ?>
                            <tr>
                                <td><strong><?php $this->escape($employee['branch_name']); ?></strong></td>
                                <td><?php $this->escape($employee['employee_count']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>