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

function ebooks_add_taxonomy()
{
	$labels = array(
		'name'              => _x('书籍分类', 'taxonomy 名称'),
		'singular_name'     => _x('书籍分类', 'taxonomy 单数名称'),
		'search_items'      => __('搜索书籍分类'),
		'all_items'         => __('所有书籍分类'),
		'parent_item'       => __('该书籍分类的上级分类'),
		'parent_item_colon' => __('该书籍分类的上级分类：'),
		'edit_item'         => __('编辑书籍分类'),
		'update_item'       => __('更新书籍分类'),
		'add_new_item'      => __('添加新的书籍分类'),
		'new_item_name'     => __('新书籍分类'),
		'menu_name'         => __('书籍分类'),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
		'rewrite' => array('slug' => 'bookshelf'),
	);
	register_taxonomy('ebook_category', 'book', $args);
}
add_action('init', 'ebooks_add_taxonomy', 0);


/**
 * Register a custom post type called "book".
 *
 * @see get_post_type_labels() for label keys.
 */
function ebooks_add_book_init()
{
	$labels = array(
		'name'                  => _x('书籍', 'Post type general name', 'textdomain'),
		'singular_name'         => _x('书籍', 'Post type singular name', 'textdomain'),
		'menu_name'             => _x('书籍', 'Admin Menu text', 'textdomain'),
		'name_admin_bar'        => _x('书籍', 'Add New on Toolbar', 'textdomain'),
		'add_new'               => __('添加新书籍', 'textdomain'),
		'add_new_item'          => __('添加新书籍', 'textdomain'),
		'new_item'              => __('添加新书籍', 'textdomain'),
		'edit_item'             => __('编辑书籍', 'textdomain'),
		'view_item'             => __('查看书籍', 'textdomain'),
		'all_items'             => __('所有书籍', 'textdomain'),
		'search_items'          => __('搜索书籍', 'textdomain'),
		'parent_item_colon'     => __('父书籍:', 'textdomain'),
		'not_found'             => __('未发现任何书籍.', 'textdomain'),
		'not_found_in_trash'    => __('垃圾箱没有任何书籍.', 'textdomain'),
		'featured_image'        => _x('书籍封面', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain'),
		'set_featured_image'    => _x('设置书籍封面', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain'),
		'remove_featured_image' => _x('移除书籍封面', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain'),
		'use_featured_image'    => _x('设置为封面', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain'),
		'archives'              => _x('书籍归档', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain'),
		'insert_into_item'      => _x('插入到书籍', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain'),
		'uploaded_to_this_item' => _x('上传该书籍', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain'),
		'filter_items_list'     => _x('筛选书籍', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain'),
		'items_list_navigation' => _x('书籍导航', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain'),
		'items_list'            => _x('书籍列表', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain'),
	);

	$args = array(
		'labels'             => $labels,
		//是否公开
		'public'             => true,
		//是否可查询
		'publicly_queryable' => true,
		//显示在后台菜单中
		'show_ui'            => true,
		//显示在后台菜单中
		'show_in_menu'       => true,
		//是否可以查询，和publicly_queryable一起使用
		'query_var'          => true,
		//重写url
		'rewrite'            => array('slug' => 'book'),
		//该文章类型的权限
		'capability_type'    => 'post',
		//是否有归档
		'has_archive'        => true,
		//是否水平，如果水平就是页面，否则类似文章这种可以有分类目录（需要自定义分类目录）
		'hierarchical'       => false,
		//菜单定位
		'menu_position'      => 12,
		//该文章类型支持的功能
		'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
	);
	register_post_type('book', $args);
}
add_action('init', 'ebooks_add_book_init');


/**
 * Register a custom post type called "book".
 *
 * @see get_post_type_labels() for label keys.
 */
function ebooks_add_chapter_init()
{
	$labels = array(
		'name'                  => _x('章节', 'Post type general name', 'textdomain'),
		'singular_name'         => _x('章节', 'Post type singular name', 'textdomain'),
		'menu_name'             => _x('章节', 'Admin Menu text', 'textdomain'),
		'name_admin_bar'        => _x('章节', 'Add New on Toolbar', 'textdomain'),
		'add_new'               => __('添加新章节', 'textdomain'),
		'add_new_item'          => __('添加新章节', 'textdomain'),
		'new_item'              => __('添加新章节', 'textdomain'),
		'edit_item'             => __('编辑章节', 'textdomain'),
		'view_item'             => __('查看章节', 'textdomain'),
		'all_items'             => __('所有章节', 'textdomain'),
		'search_items'          => __('搜索章节', 'textdomain'),
		'parent_item_colon'     => __('父章节:', 'textdomain'),
		'not_found'             => __('未发现任何章节.', 'textdomain'),
		'not_found_in_trash'    => __('垃圾箱没有任何章节.', 'textdomain'),
		'featured_image'        => _x('章节封面', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain'),
		'set_featured_image'    => _x('设置章节封面', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain'),
		'remove_featured_image' => _x('移除章节封面', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain'),
		'use_featured_image'    => _x('设置为封面', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain'),
		'archives'              => _x('章节归档', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain'),
		'insert_into_item'      => _x('插入到章节', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain'),
		'uploaded_to_this_item' => _x('上传该章节', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain'),
		'filter_items_list'     => _x('筛选章节', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain'),
		'items_list_navigation' => _x('章节导航', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain'),
		'items_list'            => _x('章节列表', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain'),
	);

	$args = array(
		'labels'             => $labels,
		//是否公开
		'public'             => true,
		//是否可查询
		'publicly_queryable' => true,
		//显示在后台菜单中
		'show_ui'            => true,
		//显示在后台菜单中
		'show_in_menu'       => true,
		//是否可以查询，和publicly_queryable一起使用
		'query_var'          => true,
		//重写url
		'rewrite'            => array('slug' => 'chapter'),
		//该文章类型的权限
		'capability_type'    => 'post',
		//是否有归档
		'has_archive'        => true,
		//是否水平，如果水平就是页面，否则类似文章这种可以有分类目录（需要自定义分类目录）
		'hierarchical'       => false,
		//菜单定位
		'menu_position'      => 12,
		//该文章类型支持的功能
		'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
	);
	register_post_type('chapter', $args);
}
add_action('init', 'ebooks_add_chapter_init');

// 您需要使用after_switch_theme挂钩刷新刷新规则一次。这将确保用户激活主题后，重写规则会自动刷新。
function theme_prefix_rewrite_flush()
{
	flush_rewrite_rules();
}
add_action('after_switch_theme', 'theme_prefix_rewrite_flush');

function ashuwp_posts_per_page($query)
{
	if ((is_home() || is_search()) && $query->is_main_query()) //首页或者搜索页的主循环
		$query->set('post_type', array('post', 'book')); //只显示book自定义类型，
	//$query->set( 'post_type', array( 'post', 'book' ) ); //主循环中显示post和book
	return $query;
}
add_action('pre_get_posts', 'ashuwp_posts_per_page');


//添加文章列表
function ebooks_add_chapters_column($columns)
{
	$columns['post_chapters'] = '高级管理';
	return $columns;
}
add_filter('manage_book_posts_columns', 'ebooks_add_chapters_column');

function views_column_content($column, $post_id)
{
	switch ( $column ) {
 
        // in this example, a Product has custom fields called 'product_number' and 'product_name'
        case 'post_chapters'   :
			echo "<a href=\"post-new.php?post_type=chapter&post_parent={$post_id}\">【章节管理】</a>";
        case 'product_name'     :
            echo get_post_meta( $post_id , $column , true );
        break;
 
        // in this example, $buyer_id is post ID of another CPT called "buyer"
        case 'product_buyer'    :
            $buyer_id = get_post_meta( $post_id , $column , true );
            if( $buyer_id ){
                echo get_post_meta( $buyer_id , 'buyer_name' , true );
            } else {
                echo '<div class="dashicons dashicons-minus"></div>';
            }
        break;
 
    }
}
add_action('manage_book_posts_custom_column', 'views_column_content', 10, 2);

function ebooks_set_chapter_parent($post_id) {

	wp_update_post( array( 'ID' => $post_id, 'post_parent' => 100) );
}
add_action( 'save_post_book', 'ebooks_set_chapter_parent' );