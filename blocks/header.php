<?php

if (isset($_GET['logout']))
{
    session_destroy();
    header('Location: index.php');
    exit();
}

?>

<header>
    <div class="container">
        <div class="head-content">
            <div class="logo-name">
                <a href="index.php?page=1" style="color: white;text-decoration: none"><h3><i class="fa fa-book" aria-hidden="true"></i> BibaLiba</h3></a>
            </div>
            <div class="links">
                <ul>
                    <?php if (isset($_SESSION['logged_user'])): ?>
                        <li><a href="index.php?page=1">Каталог</a></li>
                        <li><a href="index.php?page=4">Личный Кабинет</a></li>
                        <li><a href="index.php?logout">Выйти</a></li>
                    <?php elseif(isset($_SESSION['logged_admin'])): ?>
                        <li><a href="index.php?page=1">Каталог</a></li>
                        <li><a href="index.php?page=7">Поиск</a></li>
                        <li><a href="index.php?page=4">Личный Кабинет</a></li>
                        <li><a href="index.php?logout">Выйти</a></li>
                    <?php else: ?>
                        <li><a href="index.php?page=3"><i class="fa fa-sign-in" aria-hidden="true"></i> Войти</a></li>
                        <li><a href="index.php?page=2"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Создать аккаунт</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</header>