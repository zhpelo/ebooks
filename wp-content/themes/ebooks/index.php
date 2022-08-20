<?php
get_header();
?>
<main class="container-fluid container-lg">
    <div class="row">
        <div class="col-md-9">
            <div class="my-3 p-3 bg-burlywood rounded shadow-sm">
                <?php if (have_posts()) {
                    $i = 0;
                    while (have_posts()) {
                        $i++;
                        if ($i > 1) {
                            echo '<hr class="post-separator styled-separator is-style-wide section-inner" aria-hidden="true" />';
                        }
                        the_post();
                ?>
                        <div class="row g-0 overflow-hidden flex-md-row mb-4 h-md-250">
                            <div class="col p-4 d-flex flex-column position-static">
                                
                                <?php the_title( '<h2 class="h3 mb-1"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
                                <div class="mb-1 text-muted">
                                    <strong><?php the_category(' '); ?></strong>
                                    <font style="vertical-align: inherit;">11 月 12 日</font>
                                </div>
                                <?php the_excerpt(); ?>
                            </div>
                            <div class="col-auto d-none d-lg-block">
                                <?php the_post_thumbnail('cover-sm'); ?>

                            </div>
                        </div>
                <?php }
                } ?>
            </div>
        </div>
        <div class="col-md-3">
        <div class="my-3 p-3 bg-burlywood rounded shadow-sm">
            sss
        </div>
        </div>
    </div>

    <?php if(is_home()){ ?>
        <!-- 友情链接，仅在首页显示 -->
        <div class="my-3 p-3 bg-burlywood rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-2"><b>友情链接</b></h6>
        </div>
    <?php } ?>

    
</main>

<?php
get_footer();
