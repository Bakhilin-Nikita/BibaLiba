<?php

$date = date('Y-m-d');

if (!empty($_GET['book_id'])) {
    $book = getBookById($_GET['book_id']);
    $comments = getAllComments($_GET['book_id']);
    if (!empty($_POST['btnAddComment'])) {
        $comment = trim($_POST['comment']);
        if (!empty($comment)) {
            $comment = addComment($comment, $_GET['book_id'], $_SESSION['login_user']);
            header("Location:backdoor.php?page=11&book_id={$_GET['book_id']}");
            exit();
        }
    }
}

?>

<?php if (!empty($_SESSION)): ?>
    <div class="container">
        <div class="main-content">
            <div class="under-header">
                <div class="block-1">
                    <div class="content-info">
                        <h3> <i class="fa fa-hand-peace-o" aria-hidden="true"></i> Скачивай книги бесплатно</h3>
                        <p>
                            Скачивай любимые книги и читай в электронной книжке, на iPad или на компьютере! Никаких паролей и смс! Любую книгу можно скачать абсолютно бесплатно.
                        </p>
                    </div>
                </div>
                <div class="block-1">
                    <div class="content-info">
                        <h3> <i class="fa fa-cog" aria-hidden="true"></i> Книги доступны в формате pdf</h3>
                        <p>
                            Скачивай любимые книги и читай в электронной книжке, на iPad или на компьютере! Никаких паролей и смс! Любую книгу можно скачать абсолютно бесплатно.
                        </p>
                    </div>
                </div>
            </div>
            <?php if (!empty($book)): ?>
                <div class="profile-book">
                    <div class="main-info-book">
                        <div class="name-book">
                            Название книги: <?= $book[0]['name'] ?>
                        </div>
                        <hr>
                        <div class="author-book">
                            Автор: <?= $book[0]['author'] ?>
                        </div>
                        <hr>
                        <div class="description-book">
                            Описание: <?= $book[0]['description'] ?>
                        </div>
                    </div>
                    <div class="btnDownload">
                        <form method="post">
                            <a href="<?= $book[0]['file'] ?>" download="<?= $book[0]['name'] ?>"><i class="fa fa-download fa-2x" aria-hidden="true"></i> Скачать книгу</a>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            <div class="comment">
                <div class="head-text">
                    <h4>Оставьте свой отзыв</h4>
                </div>
                <div class="comment-content">
                    <div class="write-comment">
                        <form action="#" method="post">
                            <textarea name="comment" id="comment"></textarea>
                            <input type="submit" value="Оставить отзыв" name="btnAddComment">
                        </form>
                    </div>
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                            <div>
                                <b><?= $comment['user_log'] ?></b>: <?= $comment['comment'] ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

