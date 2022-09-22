<?php 
get_header(); 
$image_url = ! post_password_required() ? get_the_post_thumbnail_url( get_the_ID(), 'twentytwenty-fullscreen' ) : ''; 
?>

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
            <?php if($image_url): ?>
                <img class="blogs-imgae" 
                    alt="<?php the_title(); ?>" 
                    title="<?php the_title(); ?>" 
                    src="<?php echo $image_url;?>" 
                    style="max-width:100%">
            <?php endif ?>
           


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

            <?php

                if ( is_single() ) {

                    get_template_part( 'template-parts/navigation' );

                }

                /*
                * Output comments wrapper if it's a post, or if comments are open,
                * or if there's a comment number – and check for password.
                */
                if ( ( is_single() || is_page() ) && ( comments_open() || get_comments_number() ) && ! post_password_required() ) {
                    ?>
                    <div class="my-3 p-3 bg-burlywood rounded shadow-sm">
                    <div class="comments-wrapper section-inner">

                        <?php comments_template(); ?>

                    </div><!-- .comments-wrapper -->
                    </div>
                    <?php
                }
                
                ebooks_related_posts(['limit' => 12]); 
                
            ?>

        </div>
        <div class="col-md-4">
            <?php get_sidebar(); ?>
        </div>
</main>

<?php
get_footer();
