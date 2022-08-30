<?php if ( has_nav_menu( 'social' ) ) { ?>
<div class="mb-4 p-3 bg-burlywood rounded shadow-sm sticky-top">
    <h6 class="border-bottom pb-2 mb-0">可通过以下方式联系我们</h6>
    <ul class="mt-4">

        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'social',
                'container' => false,
                'depth'          => 1,
                'items_wrap'     => '%3$s',
                
            )
        );
        ?>

    </ul>
</div>

<?php } ?>
