<?php

if (!empty($_POST['btn']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['login']) && !empty($_POST['password'])) {

        $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
        $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

        $mysqli = db_connect();

        $sql = "SELECT * FROM stuff_log WHERE login = '$login'";

        $result = mysqli_query($mysqli, $sql);
        $arrayAdmin = mysqli_fetch_array($result);

        if (!empty($arrayAdmin['login'])) {
            if ($password === $arrayAdmin['password']) {
                $id = getAdminId($login);
                $_SESSION['logged_admin'] = $id[0]['stuff_id'];
                header('Location: index.php?page=4');
                exit();
            } else {
                echo '<div class="error">Неверный пароль!</div>';
            }
        } else {
            echo  '<div class="error">Данный логин не существует!</div>';
        }
    } else {
        echo '<div class="error">Пожалуйста, заполните все поля!</div>';
    }
}

?>

<?php if (empty($_SESSION['logged_user']) && empty($_SESSION['logged_admin'])): ?>
    <div class="content">
        <div class="container">
            <div class="form-block">
                <div class="enter-name">
                    <h3>Вход</h3>
                </div>
                <hr>
                <form action="#" method="post">
                    <input type="text" name="login" id="login" placeholder="Логин">
                    <input type="password" name="password" id="password" placeholder="Пароль">
                    <button type="submit" name="btn" value="1">Войти</button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>