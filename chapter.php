<?php 

get_header(); 

$chapter_id = get_query_var('chapter');

$chapter = ebooks_get_chapter($chapter_id);

$chapters = ebooks_get_chapters($chapter->post_id);
$chapter_ids = array_column($chapters, 'chapter_id');
$current_key = array_search($chapter_id,$chapter_ids);

$prev_id = $next_id = 0;
if($current_key >= 1){
    $prev_id = $chapter_ids[$current_key-1];
}
if(count($chapter_ids) > $current_key+1 ){
    $next_id = $chapter_ids[$current_key+1];
}



?>
    <main class="container-fluid container-lg">
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">章节目录</h5><button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul style="list-style: none; padding: 0;">
                <?php
                    foreach ($chapters as $c) {
                        echo "<li class=\"nav-item ". ($chapter_id == $c->chapter_id ? "bg-beige" : '') ."\"><a class=\"nav-link\" href=\"".get_chapter_url($c->chapter_id)."\">{$c->chapter_title}</a></li>";
                    }
                ?>
                </ul>
            </div>
        </div>
        <div class="row mt-4 justify-content-center">
            <div class="col-1 d-none d-md-block" style="padding: 0;">
                <div class="sticky-top left-bar">
                    <dl class="bg-burlywood">
                        <dd data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"><i class="bi bi-card-list"></i><span>目录</span></dd>

                        <dd onclick="printDOM()"><i class="bi bi-printer"></i><span>打印</span></dd>
                        
                        <?php
                            if($prev_id){
                                echo "<a href=\"".get_chapter_url($prev_id)."\"><dd><i class=\"bi bi-caret-left-fill\"></i><span>上一章</span></dd></a>";
                            }
                            if($next_id){
                                echo "<a href=\"".get_chapter_url($next_id)."\"><dd><i class=\"bi bi-caret-right-fill\"></i><span>下一章</span></dd></a>";
                            }
                        ?>
                    </dl>
                </div>
            </div>
            <div class="col-12 col-md-9 col-lg-9 col-xl-8" style="padding: 0;">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">首页</a></li>
                        <li class="breadcrumb-item"><?php the_category( '</li><li class="breadcrumb-item">' ); ?></li>
                        <li class="breadcrumb-item"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                    </ol>
                </nav>
                <div class="p-3 bg-burlywood rounded shadow-sm">
                    <print-contents>
                        <h1 class="h3 my-3">
                            <?php echo $chapter->chapter_title;?>
                        </h1>
                        <div class="d-flex flex-wrap">
                            <span class="me-2"><i class="bi bi-book"></i><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
                            
                            <span class="me-2"><i class="bi bi-file-earmark-word"></i> <?php echo word_count($chapter->chapter_content);?> 字 </span>
                            
                            <span class="me-2">
                                <i class="bi bi-clock"></i>
                                <?php echo $chapter->chapter_modified;?>
                            </span></div>
                        <hr>
                        <div class="ebook-chapter-content">
                            <?php echo wpautop($chapter->chapter_content);?>
                        </div>
                    </print-contents>
                    <div class="d-grid gap-2 d-flex justify-content-end">
                        
                        <?php
                            if($prev_id){
                                echo "<a class=\"btn btn-primary me-md-2\" href=\"".get_chapter_url($prev_id)."\">上一章</a>";
                            }
                        ?>

                        <button class="btn btn-primary me-md-2" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">目录</button>
                        

                        <?php
                            if($next_id){
                                echo "<a class=\"btn btn-primary\" href=\"".get_chapter_url($next_id)."\">下一章</a>";
                            }
                        ?>
                    </div>
                    
                </div>
            </div>
            <div class="col-1 d-none d-lg-block" style="padding: 0;"></div>
        </div>
    </main>
    

<?php get_footer(); ?>