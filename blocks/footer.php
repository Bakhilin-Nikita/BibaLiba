<footer>
    <div class="container">
        <div class="footer-content">
            <div style="color: white;font-weight: 600">
                &#169; 2022
            </div>
            <div class="links-footer">
                <?php if (empty($_SESSION)): ?>
                    <a href="index.php?page=5"><i class="fa fa-lock" aria-hidden="true"></i> Сотрудник</a>
                <?php endif; ?>
                <a href="index.php?page=10">Описание BibaLiba</a>
            </div>
        </div>
    </div>
</footer>