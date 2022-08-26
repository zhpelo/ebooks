<?php if (!empty($related_posts)) { ?>
    <div class="mb-4 p-3 bg-burlywood rounded shadow-sm">
        <h6 class="border-bottom pb-2 mb-3"><?php _e('相关文章', 'ebooks'); ?></h6>
        
            <div class="row">
                <?php
                foreach ($related_posts as $post) {
                    setup_postdata($post);
                ?>
                <div class="col-6 col-md-4 col-lg-3 text-center">
                    <a class="title" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                        <?php if (has_post_thumbnail()) { ?>
                        <div class="thumb">
                            <?php echo get_the_post_thumbnail(null, 'cover-sm', array('alt' => the_title_attribute(array('echo' => false)))); ?>
                        </div>
                        <?php } ?>
                        <h4><?php the_title(); ?></h4>
                    </a>
                </div>
                <?php } ?>
            </div>
    </div>
<?php
}