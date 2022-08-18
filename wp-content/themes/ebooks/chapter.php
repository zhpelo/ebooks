<?php get_header(); 

$chapter_id = get_query_var('chapter');

$chapter = ebooks_get_chapter($chapter_id);

$chapters = ebooks_get_chapters($chapter->post_id);

?>
这是章节内容页面


    <main class="container-fluid container-lg">
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">章节目录</h5><button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul style="list-style: none; padding: 0;">
                    <li class="nav-item bg-beige"><a class="nav-link" href="/ebook/384/22774.html">译者弁言</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ebook/384/22775.html">原序</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ebook/384/22776.html">弥盖朗琪罗</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ebook/384/22777.html"> 上编 战斗 </a></li>
                    <li class="nav-item"><a class="nav-link" href="/ebook/384/22778.html"> &nbsp;&nbsp;├ 一、力 </a></li>
                    <li class="nav-item"><a class="nav-link" href="/ebook/384/22779.html"> &nbsp;&nbsp;├ 二、力底崩裂 </a></li>
                    <li class="nav-item"><a class="nav-link" href="/ebook/384/22780.html"> &nbsp;&nbsp;└ 三、绝望 </a></li>
                    <li class="nav-item"><a class="nav-link" href="/ebook/384/22781.html"> 下编 舍弃 </a></li>
                    <li class="nav-item"><a class="nav-link" href="/ebook/384/22782.html"> &nbsp;&nbsp;├ 一、爱情 </a></li>
                    <li class="nav-item"><a class="nav-link" href="/ebook/384/22783.html"> &nbsp;&nbsp;├ 二、信心 </a></li>
                    <li class="nav-item"><a class="nav-link" href="/ebook/384/22784.html"> &nbsp;&nbsp;└ 三、孤独 </a></li>
                    <li class="nav-item"><a class="nav-link" href="/ebook/384/22785.html">尾声</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ebook/384/22786.html"> &nbsp;&nbsp;├ 死 </a></li>
                    <li class="nav-item"><a class="nav-link" href="/ebook/384/22787.html"> &nbsp;&nbsp;└ 这便是神圣的痛苦的生涯 </a></li>
                </ul>
            </div>
        </div>
        <div class="row mt-4 justify-content-center">
            <div class="col-1 d-none d-md-block" style="padding: 0;">
                <div class="sticky-top left-bar">
                    <dl class="bg-burlywood">
                        <dd data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"><i class="bi bi-card-list"></i><span>目录</span></dd>
                        <dd onclick="hear()"><i class="bi bi-ear"></i><span>听书</span></dd>
                        <dd onclick="printDOM()"><i class="bi bi-printer"></i><span>打印</span></dd><a href="/ebook/384/22775.html" id="next-chapter">
                            <dd><i class="bi bi-caret-right-fill"></i><span>下一章</span></dd>
                        </a>
                    </dl>
                </div>
            </div>
            <div class="col-12 col-md-9 col-lg-9 col-xl-8" style="padding: 0;">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">首页</a></li>
                        <li class="breadcrumb-item"><a href="/ebook/index.html">公版书</a></li>
                        <li class="breadcrumb-item"><a href="/ebook/近现代/index.html">近现代</a></li>
                        <li class="breadcrumb-item"><a href="/ebook/384.html">弥盖朗琪罗传</a></li>
                    </ol>
                </nav>
                <div class="p-3 bg-burlywood rounded shadow-sm">
                    <print-contents>
                        <h1 class="h3 my-3"><?php echo $chapter->chapter_title;?></h1>
                        <div class="d-flex flex-wrap"><span class="me-2"><i class="bi bi-book"></i><a href="/ebook/384.html">弥盖朗琪罗传</a></span><span class="me-2"><i class="bi bi-file-earmark-word"></i> 283 字 </span><span class="me-2"><i class="bi bi-clock"></i>2022-05-06</span></div>
                        <hr>
                        <div class="ebook-chapter-content">
                            <?php echo $chapter->chapter_content;?>
                        </div>
                    </print-contents>
                    <div class="d-grid gap-2 d-flex justify-content-end"><button class="btn btn-primary me-md-2" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">目录</button><a class="btn btn-primary" href="/ebook/384/22775.html">下一章</a></div>
                </div>
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6790232035711874" crossorigin="anonymous"></script><ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-6790232035711874" data-ad-slot="1536503756" data-ad-format="auto" data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
            <div class="col-1 d-none d-lg-block" style="padding: 0;"></div>
        </div>
    </main>
    

<?php get_footer(); ?>