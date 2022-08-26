<?php get_header(); ?>

<main class="container-fluid container-lg">
    <div class="row mt-4">
        <div class="col-12">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="/">首页</a></li>
                    <li class="breadcrumb-item"><?php the_category( '<li class="breadcrumb-item"><li>' ); ?></li>
                        <li class="breadcrumb-item"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-8">
            <img class="blogs-imgae" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" src="/uploads/20220722/17dc5fbc81a4af481059f5e2dc01eb7f.jpeg" style="max-width:100%">

            <?php the_post_thumbnail(); ?>

            <div class="mb-4 p-4 bg-light rounded-bottom shadow-sm">
                <h1 class="mb-4"><?php the_title(); ?></h1>
                <div class="d-flex flex-wrap mb-4">
                <?php
                printf(
                    /* translators: %s: Author name. */
                    ' <span class="me-2">作者：%s</span>',
                    '<a href="' . esc_url( get_author_posts_url( $post->post_author ) ) . '">' . esc_html( get_the_author_meta( 'display_name' ,$post->post_author) ) . '</a>'
                );
                ?>
                    <span class="me-2"><i class="bi bi-clock"></i>2022-07-22</span>
                    <span class="me-2"><i class="bi bi-shield-exclamation"></i><a target="_blank" href="/blogs/3.html">举报</a></span>
                </div>

                
                            
                <div class="blog-content">
                    <?php the_content(); ?>
                </div>
            </div>
            <div class="mb-4 p-4 bg-burlywood rounded shadow-sm">
                <h6 class="border-bottom pb-2 mb-2"><b>相关推荐</b></h6>
                
            </div>

        </div>
        <div class="col-md-4">
aaa
        </div>
</main>

<?php
get_footer();
