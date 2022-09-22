<?php

require get_template_directory() . "/inc/template-tag.php";
// Custom page walker.
require get_template_directory() . '/inc/classes/class-ebooks-walker-page.php';

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
		'primary'  => __("Main menu","ebooks"),
		'footer'  => __("Footer menu","ebooks"),
		'social'  => __("Social menu","ebooks"),
	);

	register_nav_menus($locations);
	load_theme_textdomain( 'ebooks', get_template_directory());
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

	//启用自定义logo
	$defaults = array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'site-description' ),
    );
    add_theme_support( 'custom-logo', $defaults );
	//加载翻译文件
	load_theme_textdomain( 'ebooks', get_template_directory());
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

// Register Sidebars
function custom_sidebar() {

	$args = array(
		'id'            => 'sidebar-primary',
		'name'          => __("Default sidebar","ebooks"),
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
		'before_widget' => '<aside id="%1$s" class="my-3 p-3 bg-burlywood rounded shadow-s widget %2$s">',
		'after_widget'  => '</aside>',
	);
	register_sidebar( $args );

}
add_action( 'widgets_init', 'custom_sidebar' );


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
		'name'                  => _x('Books', 'Post type general name', 'ebooks'),
		'singular_name'         => _x('Book', 'Post type singular name', 'ebooks'),
		'menu_name'             => _x('Books', 'Admin Menu text', 'ebooks'),
		'name_admin_bar'        => _x('Book', 'Add New on Toolbar', 'ebooks'),
		'add_new'               => __('Add new book', 'ebooks'),
		'add_new_item'          => __('Add new book', 'ebooks'),
		'new_item'              => __('Add new book', 'ebooks'),
		// 'edit_item'             => __('编辑书籍', 'ebooks'),
		// 'view_item'             => __('查看书籍', 'ebooks'),
		// 'all_items'             => __('所有书籍', 'ebooks'),
		// 'search_items'          => __('搜索书籍', 'ebooks'),
		// 'parent_item_colon'     => __('父书籍:', 'ebooks'),
		// 'not_found'             => __('未发现任何书籍.', 'ebooks'),
		// 'not_found_in_trash'    => __('垃圾箱没有任何书籍.', 'ebooks'),
		// 'featured_image'        => _x('书籍封面', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'ebooks'),
		// 'set_featured_image'    => _x('设置书籍封面', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'ebooks'),
		// 'remove_featured_image' => _x('移除书籍封面', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'ebooks'),
		// 'use_featured_image'    => _x('设置为封面', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'ebooks'),
		// 'archives'              => _x('书籍归档', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'ebooks'),
		// 'insert_into_item'      => _x('插入到书籍', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'ebooks'),
		// 'uploaded_to_this_item' => _x('上传该书籍', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'ebooks'),
		// 'filter_items_list'     => _x('筛选书籍', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'ebooks'),
		// 'items_list_navigation' => _x('书籍导航', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'ebooks'),
		// 'items_list'            => _x('书籍列表', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'ebooks'),
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
	$columns['post_chapters'] = __("Operate", "ebooks");
	return $columns;
}
add_filter('manage_book_posts_columns', 'ebooks_add_chapters_column');

function views_column_content($column, $post_id)
{
	switch ($column) {
		case 'post_chapters':
			echo "<a href=\"edit.php?post_type=book&page=manage-chapters&post_id={$post_id}\">".__("Chapter list", "ebooks")."</a>";
			break;
	}
}
add_action('manage_book_posts_custom_column', 'views_column_content', 10, 2);


//注册后台管理模块  
add_action('admin_menu', 'add_theme_options_menu');
function add_theme_options_menu()
{
	// add_theme_page(
	// 	'ebooks主题设置', //页面title  
	// 	'ebooks主题设置', //后台菜单中显示的文字  
	// 	'edit_theme_options', //选项放置的位置  
	// 	'theme-options', //别名，也就是在URL中GET传送的参数  
	// 	'theme_settings_admin' //调用显示内容调用的函数  
	// );
	add_submenu_page(
		'edit.php?post_type=book', 
		__("Chapter list", "ebooks"), 
		__("Chapter list", "ebooks"),
		'manage_options',
		'manage-chapters', //别名，也就是在URL中GET传送的参数  
		'ebooks_manage_chapters' //调用显示内容调用的函数  
	);
}
// function theme_settings_admin()
// {
// 	require get_template_directory() . "/inc/admin/theme-options.php";
// }

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

		echo "<div id=\"message\" class=\"updated notice notice-success is-dismissible\"><p>数据保存成功</p></div>";
	} 

	$chapters = ebooks_get_chapters($post_id);
	if ($chapter_id > 0) {
		//当前章节
		$chapter = $wpdb->get_row(
			$wpdb->prepare("SELECT * FROM {$wpdb->prefix}chapters WHERE chapter_id=%d", $chapter_id)
		);
	}
	
	require get_template_directory() . "/inc/admin/manage-chapters.php";
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


/**
 * Related posts
 * 
 * @global object $post
 * @param array $args
 * @return
 */
function ebooks_related_posts($args = array()) {
    global $post;

    // default args
    $args = wp_parse_args($args, array(
        'post_id' => !empty($post) ? $post->ID : '',
        'taxonomy' => 'category',
        'limit' => 3,
        'post_type' => !empty($post) ? $post->post_type : 'post',
        'orderby' => 'date',
        'order' => 'DESC'
    ));

    // check taxonomy
    if (!taxonomy_exists($args['taxonomy'])) {
        return;
    }

    // post taxonomies
    $taxonomies = wp_get_post_terms($args['post_id'], $args['taxonomy'], array('fields' => 'ids'));

    if (empty($taxonomies)) {
        return;
    }

    // query
    $related_posts = get_posts(array(
        'post__not_in' => (array) $args['post_id'],
        'post_type' => $args['post_type'],
        'tax_query' => array(
            array(
                'taxonomy' => $args['taxonomy'],
                'field' => 'term_id',
                'terms' => $taxonomies
            ),
        ),
        'posts_per_page' => $args['limit'],
        'orderby' => $args['orderby'],
        'order' => $args['order']
    ));

    include( locate_template('template-parts/related-posts.php', false, false) );

    wp_reset_postdata();
}

