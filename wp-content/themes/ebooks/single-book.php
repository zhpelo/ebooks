<?php get_header();
$chapters = ebooks_get_chapters(get_the_ID());
?>
<main class="container-fluid container-lg mt-4">

    <div class="row mt-4">
        <div class="col-md-9">
            <div class="mb-4 p-3 bg-burlywood rounded shadow-sm">
                <div class="ebook-item-info">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="cover">
                                <?php the_post_thumbnail('cover-md'); ?>
                            </div>
                        </div>
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
                                <a class="btn btn-primary" href="/chapter/<?php echo $chapters[0]->chapter_id; ?>">
                                    <i class="bi bi-book"></i>在线阅读
                                </a>
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
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">图书详情</button>
                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">章节列表</button>
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
                                echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"/chapter/{$chapter->chapter_id}/\">{$chapter->chapter_title}</a><span class=\"d-none d-md-block\">2022-05-06</span></li>";
                            }
                            ?>
                        </ul>
                    </div>

                </div>
            </div>


            <div class="mb-4 p-3 bg-burlywood rounded shadow-sm">
                <h6 class="border-bottom pb-2 mb-3">更多推荐</h6>
                <div class="book-list-one">
                    <div class="book-item"><a href="/ebook/73.html"><img class="book-cover" src="/uploads/book_covers/73.png" alt="淮南子" title="淮南子">
                            <div class="book-name text-center">淮南子</div>
                            <div class="book-author text-center">刘安</div>
                        </a></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-4 p-3 bg-burlywood rounded shadow-sm sticky-top">
                <h6 class="border-bottom pb-2 mb-0">可通过以下方式联系我们</h6>
                <ul class="mt-4">
                    <li><a href="https://twitter.com/7sbook" target="_blank"><i class="bi bi-twitter"></i>&nbsp;传硕公版书</a></li>
                    <li><a href="https://www.facebook.com/7sbook" target="_blank"><i class="bi bi-facebook"></i>&nbsp;传硕公版书</a></li>
                    <li><a href="mailto:kefu@7sbook.com" target="_blank"><i class="bi bi-envelope-fill"></i>&nbsp;kefu@7sbook.com</a></li>
                </ul>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
