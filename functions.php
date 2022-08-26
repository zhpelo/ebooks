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

	wp_enqueue_script('ebooks', get_template_directory_uri().'/assets/js/ebooks.js', array(), $theme_version);
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
		'primary'  => "主菜单",
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


//尽在启用主题时加载
function ebooks_after_switch_theme()
{
	ebooks_create_data_table();
	// 您需要使用after_switch_theme挂钩刷新刷新规则一次。这将确保用户激活主题后，重写规则会自动刷新。
	flush_rewrite_rules();
}
add_action('after_switch_theme', 'ebooks_after_switch_theme');

function ebooks_create_data_table()
{
	global $wpdb;
	$table_name = "{$wpdb->prefix}chapters"; //获取表前缀，并设置新表的名称 

	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE `{$table_name}` (
					`chapter_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					`post_id` bigint(20) UNSIGNED NOT NULL,
					`chapter_title` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
					`chapter_content` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
					`chapter_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
					`chapter_order` int(8) NOT NULL DEFAULT '0',
					`chapter_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					`chapter_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					`chapter_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  					`chapter_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					`chapter_status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'publish',
					PRIMARY KEY  (`chapter_id`),
					KEY `chapter_parent` (`chapter_parent`),
					KEY `chapter_status` (`chapter_status`)
				) $charset_collate;";
		require_once(ABSPATH . ("wp-admin/includes/upgrade.php"));
		dbDelta($sql);
	}
}


function ebooks_init()
{
	ebooks_add_post_type();

	add_filter('query_vars', function ($query_vars) {
		$query_vars[] = 'chapter';
		return $query_vars;
	});
	
	add_rewrite_rule( '^chapter/(\d+)[/]?$', 'index.php?chapter=$matches[1]', 'top' );

	flush_rewrite_rules();
}
add_action('init', 'ebooks_init');


add_action('template_include', function ($template) {
	if (get_query_var('chapter') == false || get_query_var('chapter') == '') {
		return $template;
	}
	return get_template_directory() . '/chapter.php';
});


/**
 * Register a custom post type called "book".
 *
 * @see get_post_type_labels() for label keys.
 */
function ebooks_add_post_type()
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
		'taxonomies'		 => array( 'category', 'post_tag' ),
		//是否有归档
		'has_archive'        => true,
		//是否水平，如果水平就是页面，否则类似文章这种可以有分类目录（需要自定义分类目录）
		'hierarchical'       => false,
		'menu_icon'	=> 'dashicons-media-document',
		//菜单定位
		'menu_position'      => 5,
		//该文章类型支持的功能
		'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
	);
	register_post_type('book', $args);
}

function ashuwp_posts_per_page($query)
{
	//首页或者搜索页的主循环
	if ((is_home() || is_search() || is_category() || is_tag()) && $query->is_main_query()) {
		$query->set('post_type', array('post', 'book')); 
	}
	return $query;
}
add_action('pre_get_posts', 'ashuwp_posts_per_page');


//添加文章列表
function ebooks_add_chapters_column($columns)
{
	$columns['post_chapters'] = '管理';
	return $columns;
}
add_filter('manage_book_posts_columns', 'ebooks_add_chapters_column');

function views_column_content($column, $post_id)
{
	switch ($column) {
		case 'post_chapters':
			echo "<a href=\"edit.php?post_type=book&page=manage-chapters&post_id={$post_id}\">章节列表</a>";
			break;
	}
}
add_action('manage_book_posts_custom_column', 'views_column_content', 10, 2);


// function ebooks_register_meta_boxes()
// {
// 	add_meta_box('chapters', "章节列表", 'chapter_display_callback', 'book', 'side');
// }
// add_action('add_meta_boxes_book', 'ebooks_register_meta_boxes');


// function chapter_display_callback()
// {
// 	echo "这里要显示章节列表";
// }


//注册后台管理模块  
add_action('admin_menu', 'add_theme_options_menu');
function add_theme_options_menu()
{
	add_theme_page(
		'ebooks主题设置', //页面title  
		'ebooks主题设置', //后台菜单中显示的文字  
		'edit_theme_options', //选项放置的位置  
		'theme-options', //别名，也就是在URL中GET传送的参数  
		'theme_settings_admin' //调用显示内容调用的函数  
	);
	add_submenu_page(
		'edit.php?post_type=book', //页面title  
		'章节管理', //后台菜单中显示的文字  
		'章节管理', //选项放置的位置  
		'manage_options',
		'manage-chapters', //别名，也就是在URL中GET传送的参数  
		'ebooks_manage_chapters' //调用显示内容调用的函数  
	);
}
function theme_settings_admin()
{
	require get_template_directory() . "/inc/admin/theme-options.php";
}

function ebooks_manage_chapters()
{
	if (!isset($_GET['post_id']) || !$_GET['post_id']) {
		die("错误！");
	}
	global $wpdb;
	$post_id = (int)$_GET['post_id'];
	$chapter_id = isset($_GET['chapter_id']) ? (int)$_GET['chapter_id'] : 0;
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($chapter_id > 0) {

			// echo "<pre>";
			// var_dump(stripslashes($_POST['chapter_content']));
			// // var_dump(esc_html(stripslashes($_POST['chapter_content'])) );
			// echo "</pre>";
			// exit();

			//更新章节
			$wpdb->update(
				$wpdb->prefix . 'chapters',
				array(
					'chapter_title'     => esc_sql($_POST['chapter_title']),
					'chapter_content'   => stripslashes($_POST['chapter_content']),
					'chapter_modified'       => current_time('mysql'),
					'chapter_modified_gmt'       => current_time('mysql', 1),
				),
				array(
					'chapter_id' => $chapter_id
				)
			);
		} else {
			//新增章节
			$wpdb->insert(
				$wpdb->prefix . 'chapters',
				array(
					'post_id'           => $post_id,
					'chapter_title'     => esc_sql($_POST['chapter_title']),
					'chapter_content'   => addslashes($_POST['chapter_content']),
					'chapter_date'       => current_time('mysql'),
					'chapter_date_gmt'       => current_time('mysql', 1),
					'chapter_modified'       => current_time('mysql'),
					'chapter_modified_gmt'       => current_time('mysql', 1),
				)
			);
		}

		die("数据保存成功");
	} else {
		//章节列表
		$chapters = ebooks_get_chapters($post_id);
		if ($chapter_id > 0) {
			//当前章节
			$chapter = $wpdb->get_row(
				$wpdb->prepare("SELECT * FROM {$wpdb->prefix}chapters WHERE chapter_id=%d", $chapter_id)
			);
		}
	}
	require get_template_directory() . "/inc/admin/manage-chapters.php";
}


function ebooks_get_chapter($chapter_id)
{
	global $wpdb;
	$chapter = $wpdb->get_row(
		$wpdb->prepare("SELECT * FROM {$wpdb->prefix}chapters WHERE chapter_id=%d", $chapter_id)
	);
	return $chapter;
}


function ebooks_get_chapters($post_id)
{
	global $wpdb;
	$chapters = $wpdb->get_results(
		$wpdb->prepare("SELECT `chapter_id`,`post_id`,`chapter_title`,`chapter_date`,`chapter_modified` FROM {$wpdb->prefix}chapters WHERE post_id=%d", $post_id)
	);
	return $chapters;
}


class bootstrap_5_wp_nav_menu_walker extends Walker_Nav_menu
{
  private $current_item;
  private $dropdown_menu_alignment_values = [
    'dropdown-menu-start',
    'dropdown-menu-end',
    'dropdown-menu-sm-start',
    'dropdown-menu-sm-end',
    'dropdown-menu-md-start',
    'dropdown-menu-md-end',
    'dropdown-menu-lg-start',
    'dropdown-menu-lg-end',
    'dropdown-menu-xl-start',
    'dropdown-menu-xl-end',
    'dropdown-menu-xxl-start',
    'dropdown-menu-xxl-end'
  ];

  function start_lvl(&$output, $depth = 0, $args = null)
  {
    $dropdown_menu_class[] = '';
    foreach($this->current_item->classes as $class) {
      if(in_array($class, $this->dropdown_menu_alignment_values)) {
        $dropdown_menu_class[] = $class;
      }
    }
    $indent = str_repeat("\t", $depth);
    $submenu = ($depth > 0) ? ' sub-menu' : '';
    $output .= "\n$indent<ul class=\"dropdown-menu$submenu " . esc_attr(implode(" ",$dropdown_menu_class)) . " depth_$depth\">\n";
  }

  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
  {
    $this->current_item = $item;

    $indent = ($depth) ? str_repeat("\t", $depth) : '';

    $li_attributes = '';
    $class_names = $value = '';

    $classes = empty($item->classes) ? array() : (array) $item->classes;

    $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
    $classes[] = 'nav-item';
    $classes[] = 'nav-item-' . $item->ID;
    if ($depth && $args->walker->has_children) {
      $classes[] = 'dropdown-menu dropdown-menu-end';
    }

    $class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    $class_names = ' class="' . esc_attr($class_names) . '"';

    $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
    $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

    $output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';

    $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
    $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
    $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
    $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

    $active_class = ($item->current || $item->current_item_ancestor || in_array("current_page_parent", $item->classes, true) || in_array("current-post-ancestor", $item->classes, true)) ? 'active' : '';
    $nav_link_class = ( $depth > 0 ) ? 'dropdown-item ' : 'nav-link ';
    $attributes .= ( $args->walker->has_children ) ? ' class="'. $nav_link_class . $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="'. $nav_link_class . $active_class . '"';

    $item_output = $args->before;
    $item_output .= '<a' . $attributes . '>';
    $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
    $item_output .= '</a>';
    $item_output .= $args->after;

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }
}