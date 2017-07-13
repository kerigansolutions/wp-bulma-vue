<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
?>
<footer id="bot" class="footer" >
    <div class="container">
        <div class="content has-text-centered">
            <p id="copyright">&copy; <?php echo date('Y'); ?> {{ copyright }}</p>
            <p id="siteby" >{{ siteby }}</p>
            <p>
                <a class="icon" href="https://github.com/jgthms/bulma">
                    <i class="fa fa-github"></i>
                </a>
            </p>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>

</body>
</html>