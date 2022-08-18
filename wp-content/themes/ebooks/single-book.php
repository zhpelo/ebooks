<?php
get_header();
?>

<main class="container-fluid container-lg mt-4">
    <div class="my-3 p-3 bg-burlywood rounded shadow-sm">
        <div class="ebook-item-info">
            <div class="row">
                <div class="col-md-3">
                    <div class="cover">
                        <?php the_post_thumbnail('cover-md'); ?>
                    </div>
                </div>
                <div class="col-md-9">
                    <h1><?php the_title(); ?></h1>
                    <div class="meta"> 作者： 巴尔扎克、 &nbsp;/&nbsp;译者：傅雷 </div>
                    <?php the_excerpt(); ?>
                    <div class="d-flex"><span class="h1 me-3">5.75 万字</span>
                        <div>
                            <p class="mb-0">成书年代：<a href="/ebook/近现代/index.html">近现代</a></p>
                            <p>最近更新：2022-08-11</p>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-flex"><a class="btn btn-primary" href="/ebook/241/18735.html"><i class="bi bi-book"></i>在线阅读</a><a class="btn btn-primary" target="_blank" title="下载epub格式电子书" href="https://user.7sbook.com/down/241"><i class="bi bi-download"></i>立即下载</a></div>
                    <div class="tags"><a href="/tags/%E9%87%91%E9%92%B1%E7%A4%BE%E4%BC%9A/index.html"><i class="bi bi-tags-fill"></i>金钱社会</a><a href="/tags/%E6%B3%95%E5%9B%BD/index.html"><i class="bi bi-tags-fill"></i>法国</a></div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-9">
            <div class="mb-4 p-3 bg-burlywood rounded shadow-sm">
                <h2 class="border-bottom pb-2 mb-0 h5">目录列表</h2>
                <ul class="chapter-list">
                    <?php 
                        $chapters = ebooks_get_chapters(get_the_ID()); 
                        foreach($chapters as $chapter){
                            echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"/chapter/{$chapter->chapter_id}/\">{$chapter->chapter_title}</a><span class=\"d-none d-md-block\">2022-05-06</span></li>";
                        }
                    ?>
                    
                </ul>
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
