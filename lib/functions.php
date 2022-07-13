<?php

const HOSTNAME = 'localhost';
const USERNAME = 'root';
const PASSWORD = '';
const DATABASE = 'bibaliba';

/**
 * get current page for backdoor.php
 * @param $page
 * @return string[]
 */
function getPage($page): array
{
    switch ($page) {
        case 1:
            $fileName = 'home.php';
            $title = 'BibaLiba | Каталог';
            break;
        case 2:
            $fileName = 'reg.php';
            $title = 'BibaLiba | Регистрация';
            break;
        case 3:
            $fileName = 'log.php';
            $title = 'BibaLiba | Авторизация';
            break;
        case 4:
            $fileName = 'profile.php';
            $title = 'Личный кабинет';
            break;
        case 5:
            $fileName = 'adminLog.php';
            $title = 'Панель администратора';
            break;
        case 7:
            $fileName = 'search.php';
            $title = 'Найти пользователя';
            break;
        case 8:
            $fileName = 'userProf.php';
            $title = 'Просмотр профиля';
            break;
        case 10:
            $fileName = 'about.php';
            $title = 'О компании';
            break;
        case 11:
            $fileName = 'bookPage.php';
            $title = 'Книга';
            break;
        case 'root':
            $fileName = 'backdoor.php';
            $title = '#404 error#';
            break;
        default:
            $fileName = 'home.php';
            $title = 'Каталог';
    }

    return [
        'File' => $fileName,
        'Title' => $title
    ];
}

/**
 * open connection to database
 * @return false|mysqli|void|null
 */
function db_connect()
{
    $mysqli = @mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);

    if (mysqli_connect_errno()) {
        printf(
          'Error connection to database: %s',
          mysqli_connect_error()
        );
    }

    if (!mysqli_set_charset($mysqli, 'utf8')) {
        printf(
          'Error to set charset: %s',
          mysqli_error($mysqli)
        );
        die();
    }

    return $mysqli;
}

/**
 * close connection to database
 * @param $mysqli
 * @return bool
 */
function db_close($mysqli): bool
{
    return mysqli_close($mysqli);
}

/**
 * Add new user and check on existing!
 * @param $name
 * @param $surname
 * @param $patronymic
 * @param $login
 * @param $password
 * @return bool|mysqli_result|string|void
 */
function addNewUser($name, $surname, $patronymic, $series, $number, $login, $password)
{
    $mysqli = db_connect();

    $name = mysqli_real_escape_string($mysqli, $name);
    $surname = mysqli_real_escape_string($mysqli, $surname);
    $patronymic = mysqli_real_escape_string($mysqli, $patronymic);
    $series = mysqli_real_escape_string($mysqli, $series);
    $number = mysqli_real_escape_string($mysqli, $number);

    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);
    $login = trim($login);
    $password = trim($password);

    $sql = "SELECT * FROM user_log WHERE login = '{$login}'";

    $result = mysqli_query($mysqli, $sql);
    $search = mysqli_fetch_array($result);


    if (empty($search['login'])) {

        $sql = "
            INSERT INTO `user` 
                (`name`, `surname`, `patronymic`, `series`, `number`) 
            VALUES 
                   ('{$name}', '{$surname}', '{$patronymic}', '{$series}', '{$number}');
        ";

        $result = mysqli_query($mysqli, $sql);

        if ($result) {
            $user_id = mysqli_insert_id($mysqli);
        } else {
            var_dump(mysqli_error($mysqli));
            exit();
        }

        $sql = "
            INSERT INTO `user_log` 
                (`login`, `password`, `user_id`) 
                VALUES ('{$login}', '{$password}', '{$user_id}');
        ";

        $result = mysqli_query($mysqli, $sql);

    } else {
        $result =  'Такой логин уже существует! Попробуйте еще раз!';
    }

    db_close($mysqli);

    return $result;
}


/**
 * searching information about user on his id
 * @param $login
 * @return array
 */
function getUserId($login): array
{
    $mysqli = db_connect();

    $sql = "SELECT * FROM `user_log` WHERE `login` LIKE '{$login}'";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * allows you to find out the administrator's ID
 * @param $login
 * @return array
 */
function getAdminId($login): array
{
    $mysqli = db_connect();

    $sql = "SELECT * FROM `stuff_log` WHERE `login` LIKE '{$login}'";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * uploads information about the user by his ID
 * @param $user_id
 * @return array
 */
function getUserInfo($user_id): array
{
    $mysqli = db_connect();

    $sql = "
        SELECT
            user_log.user_id AS user_id,
            user.name AS user_name,
            user.surname AS user_surname,
            user.patronymic AS user_patronymic
        FROM user 
        JOIN user_log ON user.id = user_log.user_id
        WHERE user.id = {$user_id}
    ";

    $user = mysqli_query($mysqli, $sql);
    $user = mysqli_fetch_all($user, MYSQLI_ASSOC);

    db_close($mysqli);

    return $user;
}

/**
 * uploads information about the admin by his ID
 * @param $admin_id
 * @return array
 */
function getAdminInfo($admin_id): array
{
    $mysqli = db_connect();

    $sql = "
        SELECT
            stuff_log.stuff_id AS user_id,
            stuff.name AS user_name,
            stuff.surname  AS user_surname,
            stuff.patronymic AS user_patronymic
        FROM stuff
        JOIN stuff_log ON stuff.id = stuff_log.stuff_id
        WHERE stuff.id = {$admin_id}
    ";

    $admin = mysqli_query($mysqli, $sql);
    $admin = mysqli_fetch_all($admin, MYSQLI_ASSOC);

    db_close($mysqli);

    return $admin;
}

/**
 * adding a book by an administrator
 * @param $name
 * @param $author
 * @param $description
 * @param $genre
 * @param $fileDirectory
 * @param $date
 * @return bool|mysqli_result
 */
function addNewBook($name, $author, $description, $genre, $fileDirectory, $date)
{
    $mysqli = db_connect();

    $sql = "
        INSERT INTO `book`
            (`name`, `author`, `description`, `genre_id`, `file`, `date`)
            VALUES ('{$name}', '{$author}', '{$description}', '{$genre}', '{$fileDirectory}', '{$date}')
    ";

    $result = mysqli_query($mysqli, $sql);

    db_close($mysqli);

    return $result;
}

/**
 * unloads all books
 * @return array
 */
function getAllBooks(): array
{
    $mysqli = db_connect();

    $sql = "SELECT * FROM `book`";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * unloads all genres
 * @return array
 */
function getAllGenres(): array
{
    $mysqli = db_connect();

    $sql = "SELECT * FROM `genre`";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * unloads the book by its id
 * @param $book_id
 * @return array
 */
function getBookById($book_id): array
{
    $mysqli = db_connect();

    $sql = "
        SELECT * FROM `book`
            WHERE `article` = {$book_id}
    ";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 *
 * @param $book_id
 * @param $user_id
 * @param $date
 * @return array
 */
function takeBook($book_id, $user_id, $date)
{
    $mysqli = db_connect();

    $sql = "
        INSERT INTO `extradition` 
            (`book_id`, `date_of_issue`, `stuff_id`, `user_id`) 
        VALUES ('{$book_id}', '{$date}', '1', '{$user_id}');
    ";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * upload all userBooks
 * @param $user_id
 * @return array
 */
function userBook($user_id): array
{
    $mysqli = db_connect();

    $sql = "
        SELECT * FROM `extradition` WHERE `user_id` = {$user_id}
    ";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * upload book by user id
 * @param $user_id
 * @return array
 */
function bookIdByUserId($user_id): array
{
    $mysqli = db_connect();

    $sql = "SELECT `book_id` FROM `extradition` WHERE `user_id` = {$user_id}";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * upload name of the book on his id
 * @param $book_id
 * @return array
 */
function getNameBookById($book_id): array
{
    $mysqli = db_connect();

    $sql = "
        SELECT `name` FROM `book`
            WHERE `article` = {$book_id}
    ";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * @param $booksId
 * @return array
 */
function BooksId($booksId): array
{
    foreach ($booksId as $books) {
        foreach ($books as $bookI){
            $idBook[] = $bookI;
        }
        foreach ($books as $bookN) {
            $nameBooks[] = getNameBookById($bookN);
            foreach ($nameBooks as $nameBook) {
                foreach ($nameBook as $bookNs) {
                    foreach ($bookNs as $bookn) {
                        $bookById[] = $bookn;
                    }
                }
            }
        }
    }

    return $bookById;
}

/**
 * books by id
 * @param $bookById
 * @return array
 */
function Books($bookById): array
{
    $Books = [];
    foreach ($bookById as $nameById) {
        if (in_array($nameById, $Books)) {
            continue;
        } else {
            $Books[] = $nameById;
        }
    }

    return $Books;
}

/**
 * delete book from user
 * @param $id
 * @param $date
 * @return bool
 */
function deleteBook($id, $date): bool
{
    $mysqli = db_connect();

    $sql = "DELETE FROM `extradition` WHERE `id` = {$id}";

    $result = mysqli_query($mysqli, $sql);

    db_close($mysqli);

    return $result;
}

/**
 * record about fixing book from user
 * @param $id
 * @param $date
 * @return bool
 */
function fixing($id, $date): bool
{
    $mysqli = db_connect();

    $sql = "INSERT INTO `fixing` (`date`, `issue_id`, `stuff_id`)
                VALUES ('{$date}', '{$id}', '1')";

    $result = mysqli_query($mysqli, $sql);

    db_close($mysqli);

    return $result;
}

/**
 * @param $name
 * @return array
 */
function searchBook($name): array
{
    $mysqli = db_connect();

    $sql = "SELECT * FROM `book` WHERE `name` LIKE '%{$name}%'";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * @param $genre_id
 * @return array
 */
function booksByGenre($genre_id): array
{
    $mysqli = db_connect();

    $sql = "SELECT * FROM `book` WHERE `genre_id` = '{$genre_id}'";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * @param $name
 * @param $surname
 * @return array
 */
function searchUser($surname): array
{
    $mysqli = db_connect();

    $sql = "SELECT * FROM `user` WHERE `surname` LIKE '%{$surname}%'";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * @param $option
 * @param $name
 * @return array|void
 */
function searchOption($option, $name)
{
    switch ($option){
        case 'book':
            return searchBook($name);
        case 'user':
            return searchUser($name);
    }
}

/**
 * last 10 books from database
 * @return array
 */
function getLastBooks(): array
{
    $mysqli = db_connect();

    $sql = "
        SELECT * FROM `book` 
            ORDER BY `article` DESC
            LIMIT 10
    ";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * get last 10 users
 * @return void
 */
function getLastUsers()
{
    $mysqli = db_connect();

    $sql = "
        SELECT * FROM `user`
            ORDER BY `id` DESC 
            LIMIT 10
    ";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * send mail
 * @param $message
 * @param $from
 * @return void
 */
function sendMail($message, $from): bool
{
    $to = 'bahilinnikita04@mail.ru';

    $subject = 'Вопрос в поддержку';

    $headers =  "From: $from" . "\r\n" .
                "Reply-To: $from" . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

    return mail($to, $subject, $message, $headers);
}

function addComment($comment, $book_id, $login)
{
    $mysqli = db_connect();

    $sql = "
        INSERT INTO `comment`
            (`comment`, `book_id`, `user_log`)
            VALUES ('{$comment}', '{$book_id}', '{$login}')        
    ";

    mysqli_query($mysqli, $sql);

    db_close($mysqli);
}

function getAllComments($book_id)
{
    $mysqli = db_connect();

    $sql = "
        SELECT * FROM `comment`
            WHERE `book_id` = '{$book_id}'

    ";

    $result = mysqli_query($mysqli, $sql);
    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);

    db_close($mysqli);

    return $result;
}

/**
 * пока хз, может понадобится
 * @param $item
 * @return bool
 */
function check($item): bool
{
    if (!empty($item)) {
        return true;
    } else {
        return false;
    }
}

