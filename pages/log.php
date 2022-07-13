<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['btn'])) {
    if (!empty($_POST['login'] && !empty($_POST['password']))) {

        $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
        $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

        $mysqli = db_connect();

        $sql = "SELECT * FROM user_log WHERE login = '{$login}'";

        $result = mysqli_query($mysqli, $sql);
        $arrayUser = mysqli_fetch_array($result);

        if (!empty($arrayUser['login'])) {
            if (password_verify($password, $arrayUser['password'])) {
                $id = getUserId($login);
                $_SESSION['logged_user'] = $id[0]['user_id'];
                $_SESSION['login_user'] = $_POST['login'];
                header('Location: index.php?page=1');
                exit();
            } else {
                echo '<div class="error">Неправильный пароль!</div>';
            }
        } else {
            echo  '<div class="error">Данный логин не существует! <a href="backdoor.php?page=2">Регистрация</a></div>';
        }
    } else {
        echo '<div class="error">Пожалуйста, заполните все поля!</div>';
    }
}

?>

<?php if (empty($_COOKIE['user_id']) && empty($_COOKIE['admin_id'])): ?>
    <div class="content">
        <div class="container">
            <div class="form-block">
                <div class="enter-name">
                    <h3>Авторизация</h3>
                    <hr>
                </div>
                <form action="index.php?page=3" method="post">
                    <input type="text" name="login" id="login" placeholder="Логин">
                    <input type="password" name="password" id="password" placeholder="Пароль">
                    <div style="margin: 0 auto;width: fit-content;">Не получается войти?
                        <a href="index.php?page=2">Регистрация</a>
                    </div>
                    <button type="submit" name="btn" value="1">Войти в BibaLiba</button>
                </form>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php

    header('Location: index.php?page=1');
    exit();

    ?>
<?php endif; ?>
