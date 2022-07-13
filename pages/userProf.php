<?php

if (!empty($_GET['id'])) {
    $user = getUserInfo($_GET['id']);
}

?>

<?php if (!empty($_SESSION) && !empty($_GET['id'])): ?>
        <div class="profile-content">
            <div class="container">
                <div class="personal-block">
                    <div class="info">
                        <h3>Информация о пользователе:</h3>
                    </div>
                    <b>Id:</b> <?= $user[0]['user_id'] ?> <br>
                    <b>Имя:</b> <?= $user[0]['user_name'] ?> <br>
                    <b>Фамилия:</b> <?= $user[0]['user_surname'] ?> <br>
                    <b>Отчество:</b> <?= $user[0]['user_patronymic'] ?> <br>
                </div>
            </div>
        </div>
<?php else: ?>
    <div>
        <a href="../index.php?page=3" style="color: black">Авторизация</a>
    </div>
<?php endif; ?>
