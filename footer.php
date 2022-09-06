        <footer class="footer bg-burlywood">
            <div class="container">

            <?php if ( has_nav_menu( 'footer' ) ) { ?>

                <ul class="bs-docs-footer-links">
                    <?php
                    wp_nav_menu(
                        array(
                            'container'      => '',
                            'depth'          => 1,
                            'items_wrap'     => '%3$s',
                            'theme_location' => 'footer',
                        )
                    );
                    ?>
                </ul>

            </nav><!-- .site-nav -->

            <?php } ?>
                <p class="copyright">
                    <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'ebooks' ) ); ?>">
					    <?php _e( 'Powered by WordPress', 'ebooks' ); ?>
				    </a>
                </p>
            </div>
        </footer>
    </body>
</html>