<?php

if (!empty($_POST['btn']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = !empty($_POST['name']) ? $_POST['name'] : null;
    $surname = !empty($_POST['surname']) ? $_POST['surname'] : null;
    $patronymic = !empty($_POST['patronymic']) ? $_POST['patronymic'] : null;
    $series = !empty($_POST['series']) ? $_POST['series'] : null;
    $number = !empty($_POST['number']) ? $_POST['number'] : null;

    if (!empty($name) && !empty($surname) && !empty($patronymic) && !empty($series) && !empty($number)) {
        if (!empty($_POST['login']) && !empty($_POST['password'])) {
            if (preg_match('~^[a-z0-9_\-]*$~i', $_POST['login'])) {
                if (preg_match('~^[a-z0-9_\-]*$~i', $_POST['password'])) {
                    $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
                    $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    $result = addNewUser($name, $surname, $patronymic, $series, $number, $login, $password);
                } else {
                    echo '<div class="error">Символы которые можно использовать для пароля ~^[a-z0-9_\-]*$~i </div>';
                }
            } else {
                echo '<div class="error">Символы которые можно использовать для логина ~^[a-z0-9_\-]*$~i </div>';
            }
        }

        if (!empty($result)) {
            if (is_bool($result)) {
                $id = getUserId($login);
                $_SESSION['logged_user'] = $id[0]['user_id'];
                $_SESSION['login_user'] = $_POST['login'];
                header('Location: index.php');
                exit();
            } else {
                echo "<div class='error'>$result</div>";
            }
        }
    } else {
        echo '<div class="error">Пожалуйста, заполните все поля регистрации!</div>';
    }
}

?>

<?php if (empty($_SESSION['logged_user']) && empty($_SESSION['logged_admin'])): ?>
    <div class="content">
        <div class="container">
            <div class="form-block">
                <div class="log-name">
                    <h3>Регистрация</h3>
                    <hr>
                </div>
                <form action="#" method="post">
                    <input type="text" name="surname" id="surname" placeholder="Фамилия">
                    <input type="text" name="name" id="name" placeholder="Имя">
                    <input type="text" name="patronymic" id="patronymic" placeholder="Отчество">
                    <input type="number" name="series" id="series" placeholder="Серия паспорта" maxlength="4">
                    <input type="number" name="number" id="number" placeholder="Номер пасорта" maxlength="6">
                    <input type="text" name="login" id="login" placeholder="Придумайте логин" minlength="6" maxlength="30">
                    <input type="password" name="password" id="password" placeholder="Придумайте пароль" minlength="6" maxlength="30">
                    <span style="color: black;font-size: 12px;text-decoration: underline">*логин и пароль должны состоять не менее чем из 6 символов!</span>
                    <button type="submit" name="btn" value="1">Создать аккаунт</button>
                </form>
            </div>
        </div>
    </div>
<?php

else:
    header('Location: index.php?page=1');
    exit();

endif;