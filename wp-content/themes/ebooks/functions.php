<?php
//去除window._wpemojiSettings
remove_action("admin_print_scripts", "print_emoji_detection_script");
remove_action("admin_print_styles", "print_emoji_styles");
remove_action("wp_head", "print_emoji_detection_script", 7);
remove_action("wp_print_styles", "print_emoji_styles");
remove_filter("the_content_feed", "wp_staticize_emoji");
remove_filter("comment_text_rss", "wp_staticize_emoji");
remove_filter("wp_mail", "wp_staticize_emoji_for_email");


/**
 * Register and Enqueue Styles.
 *
 * @since Ebooks
 */
function ebooks_register_styles()
{
	$theme_version = wp_get_theme()->get('Version');
	wp_enqueue_style('style', get_stylesheet_uri(), array(), $theme_version);
}
add_action('wp_enqueue_scripts', 'ebooks_register_styles');


/**
 * Register navigation menus uses wp_nav_menu in five places.
 *
 * @since Twenty Twenty 1.0
 */
function ebooks_menus()
{

	$locations = array(
		'primary'  => __('Desktop Horizontal Menu', 'ebooks'),
		'expanded' => __('Desktop Expanded Menu', 'ebooks'),
		'mobile'   => __('Mobile Menu', 'ebooks'),
		'footer'   => __('Footer Menu', 'ebooks'),
		'social'   => __('Social Menu', 'ebooks'),
	);

	register_nav_menus($locations);
}

add_action('init', 'ebooks_menus');


function wpmaker_menu_classes($classes, $item, $args)
{
	$classes[] = 'nav-item';
	return $classes;
}
add_filter('nav_menu_css_class', 'wpmaker_menu_classes', 1, 3);

function sonliss_menu_link_atts($atts, $item, $args)
{
	$atts['class'] = 'nav-link'; //将test修改为你的类名
	return $atts;
}
add_filter('nav_menu_link_attributes', 'sonliss_menu_link_atts', 10, 3);


//开启特色图像
add_theme_support("post-thumbnails");

add_image_size('cover-sm', 130, 184);
add_image_size('cover-md', 215, 302);


function wanlimm_function()
{
	add_theme_page('主题设置', '主题高级管理', 'administrator', 'settings', 'ebooks_admin_settings');
}
add_action('admin_menu', 'wanlimm_function');

function ebooks_admin_settings()
{
	echo "这是后台设置页面";
}

add_action('load-themes.php', 'the_table_install');

function the_table_install()
{
	global $wpdb;
	$table_name = "{$wpdb->base_prefix}cli_logins"; //获取表前缀，并设置新表的名称 

	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE `{$table_name}` (
					public_key varchar(191) NOT NULL,
					private_key varchar(191) NOT NULL,
					user_id bigint(20) UNSIGNED NOT NULL,
					created_at datetime NOT NULL,
					expires_at datetime NOT NULL,
					PRIMARY KEY  (public_key)
				) $charset_collate;";
		require_once(ABSPATH . ("wp-admin/includes/upgrade.php"));
		dbDelta($sql);
	}
}


function ebooks_add_type()
{
	$labels = array(
		'name'               => _x('Ebooks', 'post type 名称'),
		'singular_name'      => _x('ebook', 'post type 单个 item 时的名称，因为英文有复数'),
		'add_new'            => _x('新建电子书', '添加新内容的链接名称'),
		'add_new_item'       => __('新建一本电子书'),
		'edit_item'          => __('编辑电子书'),
		'new_item'           => __('新电子书'),
		'all_items'          => __('所有电子书'),
		'view_item'          => __('查看电子书'),
		'search_items'       => __('搜索电子书'),
		'not_found'          => __('没有找到有关电子书'),
		'not_found_in_trash' => __('回收站里面没有相关电子书'),
		'parent_item_colon'  => '',
		'menu_name'          => '电子书'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => '我们网站的电影信息',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
		'has_archive'   => true
	);
	register_post_type('ebook', $args);
}
add_action('init', 'ebooks_add_type');

function my_taxonomies_movie() {
	$labels = array(
	  'name'              => _x( '电影分类', 'taxonomy 名称' ),
	  'singular_name'     => _x( '电影分类', 'taxonomy 单数名称' ),
	  'search_items'      => __( '搜索电影分类' ),
	  'all_items'         => __( '所有电影分类' ),
	  'parent_item'       => __( '该电影分类的上级分类' ),
	  'parent_item_colon' => __( '该电影分类的上级分类：' ),
	  'edit_item'         => __( '编辑电影分类' ),
	  'update_item'       => __( '更新电影分类' ),
	  'add_new_item'      => __( '添加新的电影分类' ),
	  'new_item_name'     => __( '新电影分类' ),
	  'menu_name'         => __( '电影分类' ),
	);
	$args = array(
	  'labels' => $labels,
	  'hierarchical' => true,
	);
	register_taxonomy( 'movie_category', 'ebook', $args );
  }
  add_action( 'init', 'my_taxonomies_movie', 0 );
