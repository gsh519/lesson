<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員登録</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <main>
        <!-- 共通メニュー -->
        <?php require('./views/menu.view.php'); ?>

        <!-- 成功メッセージ -->
        <?php if (!empty($_SESSION['msg'])) : ?>
            <p class="message"><?php $this->escape($_SESSION['msg']); ?></p>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <h1 class="title">社員登録</h1>
        <div class="content">
            <!-- エラーメッセージ表示 -->
            <?php if (!empty($errors)) : ?>
                <ul class="error-message">
                    <?php foreach ($errors as $error) : ?>
                        <li>・<?php $this->escape($error); ?></li>
                    <?php endforeach ?>
                </ul>
            <?php endif; ?>
            <div class="add-form">
                <form action="" method="post">
                    <input type="hidden" name="token" value="<?php $this->escape($this->token); ?>">
                    <!-- 氏名 -->
                    <div class="form-area">
                        <label class="label" for="name">氏名<span>必須</span></label>
                        <input required type="text" id="name" name="name" class="form-input" value="<?php $this->escape($this->employee->name);?>">
                    </div>

                    <!-- かな -->
                    <div class="form-area">
                        <label class="label" for="name_kana">かな<span>必須</span></label>
                        <input required type="text" id="name_kana" name="name_kana" class="form-input" value="<?php $this->escape($this->employee->name_kana); ?>">
                    </div>

                    <!-- 部門 -->
                    <div class="form-area">
                        <label class="label" for="branch_id">部門</label>
                        <select name="branch_id" id="branch_id" class="form-select">
                            <option value="">選択</option>
                            <?php foreach ($this->branches as $branch) : ?>
                                <option <?php if ($this->employee->branch_id === $branch['id']) { echo 'selected'; } ?> value="<?php $this->escape($branch['id']) ?>"><?php $this->escape($branch['branch_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 性別 -->
                    <div class="form-area">
                        <label class="label" for="sex">性別</label>
                        <select name="sex" id="sex" class="form-select">
                            <option value="">選択</option>
                            <option <?php if ($this->employee->sex === '0') { echo 'selected'; } ?> value="0">男</option>
                            <option <?php if ($this->employee->sex === '1') { echo 'selected'; } ?> value="1">女</option>
                            <option <?php if ($this->employee->sex === '2') { echo 'selected'; } ?> value="2">不明</option>
                        </select>
                    </div>

                    <!-- 生年月日 -->
                    <div class="form-area">
                        <label class="label" for="birthday">生年月日</label>
                        <input type="date" id="birthday" name="birthday" class="form-input" value="<?php $this->escape($this->employee->birthday); ?>">
                    </div>

                    <!-- メールアドレス -->
                    <div class="form-area">
                        <label class="label" for="email">メールアドレス<span>必須</span></label>
                        <input required type="email" id="email" name="email" class="form-input form-email" value="<?php $this->escape($this->employee->email); ?>">
                    </div>

                    <!-- パスワード -->
                    <div class="form-area">
                        <label class="label" for="password">パスワード<span>必須</span><span class="text-black">変更する場合のみ入力</span></label>
                        <input required type="password" id="password" name="password" class="form-input form-password" value="<?php $this->escape($this->employee->password); ?>">
                    </div>

                    <!-- 通勤時間 -->
                    <div class="form-area">
                        <label class="label" for="commute">通勤時間（分）</label>
                        <input type="number" id="commute" name="commute" class="form-input form-commute" min="1" max="999" step="1" value="<?php $this->escape($this->employee->commute); ?>">
                    </div>

                    <!-- 血液型 -->
                    <div class="form-area">
                        <label>血液型<span>必須</span></label>
                        <div class="blood-type">
                            <div>
                                <input <?php if ($this->employee->blood_type === '1') { echo 'checked'; } ?> required type="radio" id="a" name="blood_type" value="1">
                                <label for="a">A型</label>
                            </div>
                            <div>
                                <input <?php if ($this->employee->blood_type === '2') { echo 'checked'; } ?> required  type="radio" id="b" name="blood_type" value="2">
                                <label for="b">B型</label>
                            </div>
                            <div>
                                <input <?php if ($this->employee->blood_type === '3') { echo 'checked'; } ?> required type="radio" id="o" name="blood_type" value="3">
                                <label for="o">O型</label>
                            </div>
                            <div>
                                <input <?php if ($this->employee->blood_type === '4') { echo 'checked'; } ?> required type="radio" id="ab" name="blood_type" value="4">
                                <label for="ab">AB型</label>
                            </div>
                            <div>
                                <input <?php if ($this->employee->blood_type === '0') { echo 'checked'; } ?> required type="radio" id="not" name="blood_type" value="0">
                                <label for="not">不明</label>
                            </div>
                        </div>
                    </div>

                    <!-- 既婚 -->
                    <div class="form-area">
                        <label class="label" for="married">既婚</label>
                        <div class="married">
                            <input <?php if ($this->employee->married === '1') { echo 'checked'; } ?> type="checkbox" id="married" name="married" value="1">
                            <label for="married">既婚</label>
                        </div>
                    </div>

                    <!-- 保有資格 -->
                    <div class="form-area">
                        <label>保有資格</label>
                        <div class="qualification">
                            <?php foreach ($this->qualifications as $qualification) : ?>
                                <input type="checkbox" name="qualification_array[]" value="<?php $this->escape($qualification['id']); ?>">
                                <label><?php $this->escape($qualification['qualification_name']); ?></label>
                            <?php endforeach; ?>

                            <!-- <div>
                                <input <?php if (in_array('0', $this->employee->qualification_array)) { echo 'checked'; } ?> type="checkbox" id="car" name="qualification_array[]" value="0">
                                <label for="car">普通運転免許</label>
                            </div>
                            <div>
                                <input <?php if (in_array('1', $this->employee->qualification_array)) { echo 'checked'; } ?>  type="checkbox" id="it" name="qualification_array[]" value="1">
                                <label for="it">ITパスポート</label>
                            </div>
                            <div>
                                <input <?php if (in_array('2', $this->employee->qualification_array)) { echo 'checked'; } ?> type="checkbox" id="normal-skill" name="qualification_array[]" value="2">
                                <label for="normal-skill">基本情報技術者</label>
                            </div>
                            <div>
                                <input <?php if (in_array('3', $this->employee->qualification_array)) { echo 'checked'; } ?> type="checkbox" id="difficult-skill" name="qualification_array[]" value="3">
                                <label for="difficult-skill">応用情報技術者</label>
                            </div> -->
                        </div>
                    </div>

                    <!-- 登録ボタン -->
                    <div class="form-area">
                        <input type="submit" name="add" class="form-submit" value="登録">
                    </div>
                </form>
                <!-- ホームボタン -->
                <a href="./">Home</a>
            </div>
        </div>
    </main>
</body>
</html>