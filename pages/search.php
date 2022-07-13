<?php

$lastUsers = getLastUsers();

if (isset($_GET['go'])) {
    if (!empty($_POST['btnSearch'])) {
        $results = searchOption($_POST['search'], $_POST['searchAll']);
    }
}

?>

<?php if (isset($_SESSION['logged_admin'])): ?>
    <div class="container">
        <div class="search" style="margin: 15px 0 40px;">
            <form action="index.php?page=7&go" method="post" style="padding: 0;">
                <label for="search">Поиск</label>
                <select name="search" id="search">
                    <option value="user">пользователя</option>
                    <option value="book">книги</option>
                </select>
                <input type="text" name="searchAll" value="" maxlength="30">
                <span style="font-size: 12px">*поиск пользователя осуществляется по <span style="color: #fc1c17;text-decoration: underline 1px black">фамилии</span></span>
                <input type="submit" value="&#128270;Найти информацию" name="btnSearch">
            </form>
        </div>

        <div style="margin-bottom: 100px">
            <div>
                <h4>Результаты поиска:</h4>
            </div>
            <?php if (!empty($results)): ?>
                <div class="users" style="margin-bottom: 100px">
                    <?php foreach ($results as $result): ?>
                        <?php if ($_POST['search'] === 'user'): ?>
                            <div class="search-user">
                                <div>
                                    <?= $result['surname'] ?>
                                    <?= $result['name'] ?>
                                    <?= $result['patronymic'] ?>
                                </div>
                                <div>
                                    <a href="index.php?page=8&id=<?= $result['id'] ?>">
                                        <button class="searchBtn">Перейти в профиль</button>
                                    </a>
                                </div>
                            </div>
                        <?php elseif ($_POST['search'] === 'book'): ?>
                            <div class="search-user">
                                <div>
                                    <b>Название:</b>  <?= $result['name'] ?> <br>
                                    <b>Автор:</b> <?= $result['author'] ?>
                                </div>
                                <div>
                                    <a href="index.php?page=4&book_id=<?= $result['article'] ?>">
                                        <button class="searchBtn">Читать</button>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <?php if (!empty($lastUsers)): ?>
                    <?php foreach ($lastUsers as $lastUser): ?>
                        <div class="search-user">
                            <div>
                                <?= $lastUser['surname'] ?>
                                <?= $lastUser['name'] ?>
                                <?= $lastUser['patronymic'] ?>
                            </div>
                            <div>
                                <a href="index.php?page=8&id=<?= $lastUser['id'] ?>">
                                    <button class="searchBtn">Перейти в профиль</button>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
      </div>
    </div>

<?php else: ?>
        <div>
            <a href="index.php?page=3" style="color: black">Авторизация</a>
        </div>
<?php endif; ?>

