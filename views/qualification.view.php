<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>資格マスタ</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <main>
        <?php require('./views/menu.view.php'); ?>
        <h1 class="title"><a href="/">資格マスタ</a></h1>

        <!-- メッセージ -->
        <?php if (!empty($_SESSION['msg'])) : ?>
            <p class="message"><?php echo $this->escape($_SESSION['msg']); ?></p>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>
        <div class="content">
            <!-- エラー文 -->
            <?php if (!empty($this->errors)) : ?>
                <ul class="error-message">
                    <?php foreach ($this->errors as $error) : ?>
                        <li><?php $this->escape($error); ?></li>
                    <?php endforeach ?>
                </ul>
            <?php elseif (empty($this->errors)) : ?>
                <form action="" method="POST">
                    <input type="hidden" name="token" value="<?php $this->escape($this->token); ?>">
                    <table class="qualification-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>資格名</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->qualifications as $qualification) : ?>
                                <tr>
                                    <td><strong><?php $this->escape($qualification['id']); ?></strong></td>
                                    <td>
                                        <input required type="text" name="qualifications[<?php $this->escape($qualification['id']); ?>]" value="<?php $this->escape($qualification['qualification_name']); ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="text" name="new_qualification" value="">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div>
                        <input type="submit" name="add" class="form-submit" value="保存">
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>