<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>支店登録</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <main>
        <!-- 成功メッセージ -->
        <?php if (empty($_POST['add']) && !empty($_SESSION['msg'])) : ?>
            <p class="message"><?php echo htmlspecialchars($_SESSION['msg'], ENT_QUOTES); ?></p>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <h1 class="title">支店登録</h1>
        <div class="content">
            <!-- エラーメッセージ表示 -->
            <?php if (!empty($errors)) : ?>
                <ul class="error-message">
                    <?php foreach ($errors as $error) : ?>
                        <li>・<?php echo htmlspecialchars($error, ENT_QUOTES); ?></li>
                    <?php endforeach ?>
                </ul>
            <?php endif; ?>
            <div class="add-form">
                <form action="" method="post">
                    <input type="hidden" name="token" value="<?php echo $this->token; ?>">
                    <!-- 支店名 -->
                    <div class="form-area">
                        <label class="label" for="branch_name">支店名<span>必須</span></label>
                        <input required type="text" id="branch_name" name="branch_name" class="form-input" value="<?php echo htmlspecialchars($this->branch->branch_name, ENT_QUOTES);?>">
                    </div>

                    <!-- 住所 -->
                    <div class="form-area">
                        <label class="label" for="ken_name">住所<span>必須</span></label>
                        <div class="form-address">
                            <select required name="ken_name" id="ken_name" class="form-select">
                                <option value="">都道府県を選択</option>
                                <?php foreach ($this->branch->ken_names as $index => $ken_name) : ?>
                                    <option <?php if ($this->branch->ken_name == $index + 1) { echo 'selected'; } ?> value="<?php echo htmlspecialchars($index + 1, ENT_QUOTES) ?>"><?php echo htmlspecialchars($ken_name, ENT_QUOTES) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-address">
                            <input placeholder="市区町村" required type="text" id="city_name" name="city_name" class="form-input" value="<?php echo htmlspecialchars($this->branch->city_name, ENT_QUOTES); ?>">
                        </div>
                        <div class="form-address">
                            <input placeholder="字番地" required type="text" id="street_address" name="street_address" class="form-input" value="<?php echo htmlspecialchars($this->branch->street_address, ENT_QUOTES); ?>">
                        </div>
                        <div class="form-address">
                            <input placeholder="建物名" type="text" id="building_name" name="building_name" class="form-input" value="<?php echo htmlspecialchars($this->branch->building_name, ENT_QUOTES); ?>">
                        </div>
                    </div>

                    <!-- 電話番号 -->
                    <div class="form-area">
                        <label class="label" for="phone_number">電話番号<span>必須</span></label>
                        <input required type="tel" id="phone_number" name="phone_number" class="form-input" value="<?php echo htmlspecialchars($this->branch->phone_number, ENT_QUOTES); ?>">
                    </div>

                    <!-- 並び順 -->
                    <div class="form-area">
                        <label class="label" for="sort_order">並び順<span>必須</span></label>
                        <input required type="number" step="1" min="1" id="sort_order" name="sort_order" class="form-input" value="<?php echo htmlspecialchars($this->branch->sort_order, ENT_QUOTES); ?>">
                    </div>

                    <!-- 登録ボタン -->
                    <div class="form-area">
                        <input type="submit" name="add" class="form-submit" value="登録">
                    </div>
                </form>
                <!-- ホームボタン -->
                <a href="./branch_index.php">Home</a>
            </div>
        </div>
    </main>
</body>
</html>