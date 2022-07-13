<?php

$genres = getAllGenres();
$date = date('Y-m-d');

if (!empty($_SESSION['logged_user'])) {
    $user = getUserInfo($_SESSION['logged_user']);
    $status = 'Пользователь';
    if (!empty($_POST['message']) && !empty($_POST['email'])) {
        $sendMail = sendMail($_POST['message'], $_POST['email']);
    }
} elseif (!empty($_SESSION['logged_admin']))  {
    $user = getAdminInfo($_SESSION['logged_admin']);
    $status = 'Администратор';
}

//For administrator
if (!empty($_FILES)) {
    if ($_FILES['file']['size'] > 100000000) {
        echo '<div class="error">Этот файл превышает максимальный размер!</div>>';
    } else {
        if (move_uploaded_file($_FILES['file']['tmp_name'], 'library/' .$_FILES['file']['name'])) {
            if (!empty($_POST['name']) && !empty($_POST['author']) && !empty($_POST['description']) && !empty($_POST['genre'])){
                $file = "library/{$_FILES['file']['name']}";
                $date = date('Y-m-d H:i:s');
                $genre = $_POST['genre'];

                $result = addNewBook($_POST['name'], $_POST['author'], $_POST['description'], $genre, $file, $date);

                if ($result) {
                    header('Location: index.php?page=4');
                    exit();
                }
            }
        } else {
            echo '<div class="error">Этот файл не был отправлен на сервер!Попробуйте позже!</div>';
        }
    }
}

?>

<?php if (isset($_SESSION['logged_admin'])): ?>
    <h2 class="admin-panel">ПАНЕЛЬ АДМИНИСТРАТОРА</h2>
    <hr>
<?php endif; ?>

<div class="profile-content">
    <div class="container">
        <?php if (!empty($user)): ?>
            <div class="profile-block">
                    <div class="profile">
                        <div class="personal-block">
                            <div class="info">
                                <h3>Личная информация:</h3>
                            </div>
                            <b>Id:</b> <?= $user[0]['user_id'] ?> <br>
                            <b>Фамилия:</b> <?= $user[0]['user_surname'] ?> <br>
                            <b>Имя:</b> <?= $user[0]['user_name'] ?> <br>
                            <b>Отчество:</b> <?= $user[0]['user_patronymic'] ?> <br>
                            <b>Полномочия:</b> <?= $status ?>
                        </div>
                    </div>
                <?php if (isset($_SESSION['logged_user'])): ?>
                <div class="support">
                        <h4><i class="fa fa-question-circle" aria-hidden="true"></i>
                            Возникли трудности? Напишите нам!</h4>
                        <form action="#" method="post">
                            <input type="email" name="email" placeholder="Введите свой email" maxlength="30">
                            <input type="text" name="message" placeholder="Введите свой вопрос" maxlength="100">
                            <input type="submit" value="Отправить сообщение">
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['logged_admin'])): ?>
                <div class="addNewBook">
                    <h3>Добавить новую книгу:</h3>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="book-block">
                            <label for="name">Название книги:</label>
                            <input type="text" name="name" id="name">
                        </div>
                        <div class="book-block">
                            <label for="author">Автор:</label>
                            <input type="text" name="author" id="author">
                        </div>
                        <div class="book-block">
                            <label for="description">Описание:</label>
                            <input type="text" name="description" id="description" maxlength="250">
                        </div>
                        <div class="book-block">
                            <label  for="genre" style="margin: 15px 0">Выбрать жанр:</label>
                            <select name="genre" id="genre">
                                <?php foreach ($genres as $genre): ?>
                                    <option value="<?= $genre['id'] ?>"><?= $genre['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="book-block">
                            <label for="file">Файл:</label>
                            <input type="file" name="file" id="file" style="width: fit-content">
                        </div>
                        <button type="submit" name="btn">Добавить книгу</button>
                    </form>
                </div>
        <?php endif; ?>
</div>







