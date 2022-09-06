<?php get_header();
$chapters = ebooks_get_chapters(get_the_ID());


?>
<main class="container-fluid container-lg mt-4">

    <div class="row mt-4">
        <div class="col-md-9">
            <div class="mb-4 p-3 bg-burlywood rounded shadow-sm">
                <div class="ebook-item-info">
                    <div class="row">

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="col-md-3">
                        <?php the_post_thumbnail('cover-md',['class'=>'bookcover']); ?>
                        </div>
                    <?php endif; ?>
                        <div class="col-md-9">
                            <h1><?php the_title(); ?></h1>
                            <div class="meta">
                                <?php
                                    printf(
                                        '<p>作者: %s</p>',
                                        '<a href="' . esc_url( get_author_posts_url( $post->post_author ) ) . '">' . esc_html( get_the_author_meta( 'display_name' ,$post->post_author) ) . '</a>'
                                    );
                                ?>
                               
                            </div>
                            <p><strong><?php echo get_the_excerpt(); ?></strong></p>
                            
                            <p>最近更新：<?php the_time( get_option( 'date_format' ) ); ?></p>

                            <div class="d-grid gap-2 d-flex py-2">
                                <?php if ( isset($chapters[0]) &&  $chapters[0]->chapter_id ) : ?>
                                <a class="btn btn-primary" href="<?php echo get_chapter_url($chapters[0]->chapter_id); ?>">
                                    <i class="bi bi-book"></i>
                                    <?php _e("Read now","ebooks");?>
                                </a>
                                <?php endif; ?>
                            </div>
                            <div class="tags">
                                <?php the_tags(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php edit_post_link(); ?>
            </div>

            <div class="mb-4 p-3 bg-burlywood rounded shadow-sm">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                            <?php _e("Details","ebooks");?>
                        </button>
                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
                            <?php _e("Chapters","ebooks");?>
                        </button>
                    </div>
                </nav>
                <div class="tab-content py-2" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                        <?php the_content(); ?>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                        <ul class="chapter-list">
                            <?php
                            foreach ($chapters as $chapter) {
                                echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"".get_chapter_url($chapter->chapter_id)."/\">{$chapter->chapter_title}</a><span class=\"d-none d-md-block\">2022-05-06</span></li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php ebooks_related_posts(['limit' => 12]); ?>
        </div>
        <div class="col-md-3">
            <div class="my-3 p-3 bg-burlywood rounded shadow-sm">
                <?php get_sidebar(); ?>
            </div>

            <?php get_template_part( 'template-parts/social' ); ?>
            
        </div>
    </div>
</main>

<?php
get_footer();
