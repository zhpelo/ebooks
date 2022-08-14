<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	<style>
		.current-menu-item{
			background-color: #999;
		}

		a, .nav-link {
    color: #19537d;
    text-decoration: none;
}

	/* 电子书详情页 start */
.chapter-list {
    list-style: none;
    padding: 15px 0;
}

.chapter-list .nav-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chapter-list .nav-item span {
    font-style: oblique;
    color: #838383;
}

.ebook-item-info .tags {
    margin: 15px 0;
    text-align: right;
    overflow: hidden;
}
.ebook-item-info .tags a {
    padding: 2px 5px;
    margin: 5px;
    text-decoration: none;
    font-size: 0.9rem;
    color: #24292e;
    border-radius: 3px;
    word-break: keep-all;
    display: inline-block;
}
.ebook-item-info .tags a > i{
    margin-right: 4px;
}

/* 电子书详情页 end */

	</style>
</head>

<body <?php body_class('bg-beige'); ?>>
	<?php wp_body_open(); ?>
	<!-- 这是导航栏 -->
	<div class="bg-olive">
		<div class="text-center py-3"><a class="header-logo text-white " href="/" title="我的书店">我的书店</a></div>

		<nav class="navbar navbar-expand-lg bg-burlywood">
			<div class="container">
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<?php if ( has_nav_menu( 'primary' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location'  => 'primary',
							'container_class' => 'collapse navbar-collapse',
							'container_id' => 'navbarScroll',
							'menu_class'   => 'nav-item',
							'items_wrap'      => '<ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">%3$s</ul>',
							'fallback_cb'     => false,
						)
					);
					?>
				<?php endif; ?>

				<form class="d-flex" role="search">
					<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
					<button class="btn btn-success" type="submit">Search</button>
				</form>
			</div>
		</nav>
	</div>