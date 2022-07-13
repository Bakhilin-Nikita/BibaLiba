<?php

$genres = getAllGenres();
$booksByGenre = booksByGenre($_GET['genre']);
$lastbooks = getLastBooks();
$c = 0;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['btn'])) {
    header("Location: index.php?page=11&book_id={$_GET['btn']}");
    exit();
}

//if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['btnBook'])) {
//    header("Location: backdoor.php?page=11&book_id={$_GET['btnBook']}");
//    exit();
//}

if (empty($_SESSION)) {
    header('Location: index.php?page=3');
    exit();
}

if (!empty($_GET['btnSearch']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET['search'])) {
        $searchBooks = searchBook($_GET['search']);
    }
}

?>

<?php if (isset($_SESSION['logged_user']) || isset($_SESSION['logged_admin'])): ?>
    <div class="container">
        <div class="home-block">
                <div class="search-book">
                    <form action="#" method="get">
                        <input type="text" name="search" id="search" placeholder="Введите название книги">
                        <input type="submit" name="btnSearch" value="Найти">
                    </form>
                </div>
                <div class="catalog">
                    <h5>Жанры</h5>
                    <ul>
                        <?php foreach ($genres as $genre): ?>
                            <li><a href="index.php?page=1&genre=<?= $genre['id'] ?>&name=<?= $genre['name'] ?>"><?= $genre['name'] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <hr>
                </div>
                <?php if (!empty($_GET['genre'])): ?>
                    <div>
                        <h4><i class="fa fa-search" aria-hidden="true"></i> Результаты поиска &#171;<?= $_GET['name'] ?> &#187; :</h4>
                    </div>
                        <?php foreach ($booksByGenre as $bookByGenre): ?>
                                <div class="book">
                                    <div>
                                        <div class="name-book">
                                            <b>Книга:</b> <?= $bookByGenre['name'] ?>
                                        </div>
                                        <div class="author-book">
                                            <b>Автор:</b> <?= $bookByGenre['author'] ?>
                                        </div>
                                        <hr>
                                        <div class="description-book">
                                            <b>Описание книги:</b> <br> <?= $bookByGenre['description'] ?>
                                        </div>
                                    </div>
                                    <div class="order">
                                        <form action="#" method="get">
                                            <button type="submit" value="<?= $bookByGenre['article'] ?>" name="btn">Подробнее</button>
                                        </form>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                <?php elseif(!empty($searchBooks)): ?>
                    <?php foreach ($searchBooks as $searchBook): ?>
                        <div class="book">
                            <div>
                                <div class="name-book">
                                    <b>Книга:</b> <?= $searchBook['name'] ?>
                                </div>
                                <div class="author-book">
                                    <b>Автор:</b> <?= $searchBook['author'] ?>
                                </div>
                                <hr>
                                <div class="description-book">
                                    <b>Описание книги:</b> <br> <?= $searchBook['description'] ?>
                                </div>
                            </div>
                            <div class="order">
                                <form action="#" method="get">
                                    <button type="submit" value="<?= $searchBook['article'] ?>" name="btn">Подробнее</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php if (!empty($lastbooks)): ?>
                        <?php foreach ($lastbooks as $book): ?>
                            <div class="book">
                                <div>
                                    <div class="name-book">
                                        <b>Книга:</b> <?= $book['name'] ?>
                                    </div>
                                    <div class="author-book">
                                        <b>Автор:</b> <?= $book['author'] ?>
                                    </div>
                                    <hr>
                                    <div class="description-book">
                                        <b>Описание книги:</b> <br> <?= $book['description'] ?>
                                    </div>
                                </div>
                                <div class="order">
                                    <form action="#" method="get">
                                        <button type="submit" value="<?= $book['article'] ?>" name="btn">Подробнее</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
        </div>
    </div>
<?php endif; ?>




