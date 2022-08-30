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
		'footer'  => "底部菜单",
		'social'  => "社交媒体",
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

	//启用自定义logo
	$defaults = array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'site-description' ),
    );
    add_theme_support( 'custom-logo', $defaults );
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

// Register Sidebars
function custom_sidebar() {

	$args = array(
		'id'            => 'sidebar-primary',
		'name'          => "默认侧边栏",
		'description'   => "此侧边栏在全站显示",
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
	);
	register_sidebar( $args );

}
add_action( 'widgets_init', 'custom_sidebar' );
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
 /**
  * 计算字数
  * @param string $str 文字
  * @return int
  */
function word_count($str){
	//转换html实体字符
	$str = htmlspecialchars_decode($str);
	//清楚html代码
	$str = strip_tags($str);
	//清除换行符
	$str = str_replace(PHP_EOL, '', $str);
	//统计总字数
	return mb_strlen($str);
 }
 /**
  * 获取语义化字数
  * @param int $strlen 数字
  * @return string
  */
 function human_strlen($strlen)
 {
	 if($strlen >= 100000000){
		 return number_format($strlen/100000000, 2)." 亿字";
	 }elseif($strlen >= 10000){
		 return number_format($strlen/10000, 2)." 万字";
	 }elseif($strlen >= 1000){
		 return number_format($strlen/1000, 2)." 千字";
	 }else{
		 return $strlen." 字";
	 }
 }






/**
 * Displays the site logo, either text or image.
 *
 * @since Twenty Twenty 1.0
 *
 * @param array $args    Arguments for displaying the site logo either as an image or text.
 * @param bool  $display Display or return the HTML.
 * @return string Compiled HTML based on our arguments.
 */
function ebooks_site_logo( $args = array(), $display = true ) {
	$logo       = get_custom_logo();
	$site_title = get_bloginfo( 'name' );
	$contents   = '';
	$classname  = '';

	$defaults = array(
		'logo'        => '%1$s<span class="screen-reader-text">%2$s</span>',
		'logo_class'  => 'site-logo',
		'title'       => '<a href="%1$s">%2$s</a>',
		'title_class' => 'site-title',
		'home_wrap'   => '<h1 class="%1$s">%2$s</h1>',
		'single_wrap' => '<div class="%1$s faux-heading">%2$s</div>',
		'condition'   => ( is_front_page() || is_home() ) && ! is_page(),
	);

	$args = wp_parse_args( $args, $defaults );

	/**
	 * Filters the arguments for `ebooks_site_logo()`.
	 *
	 * @since Twenty Twenty 1.0
	 *
	 * @param array $args     Parsed arguments.
	 * @param array $defaults Function's default arguments.
	 */
	$args = apply_filters( 'ebooks_site_logo_args', $args, $defaults );

	if ( has_custom_logo() ) {
		$contents  = sprintf( $args['logo'], $logo, esc_html( $site_title ) );
		$classname = $args['logo_class'];
	} else {
		$contents  = sprintf( $args['title'], esc_url( get_home_url( null, '/' ) ), esc_html( $site_title ) );
		$classname = $args['title_class'];
	}

	$wrap = $args['condition'] ? 'home_wrap' : 'single_wrap';

	$html = sprintf( $args[ $wrap ], $classname, $contents );

	/**
	 * Filters the arguments for `twentytwenty_site_logo()`.
	 *
	 * @since Twenty Twenty 1.0
	 *
	 * @param string $html      Compiled HTML based on our arguments.
	 * @param array  $args      Parsed arguments.
	 * @param string $classname Class name based on current view, home or single.
	 * @param string $contents  HTML for site title or logo.
	 */
	$html = apply_filters( 'ebooks_site_logo', $html, $args, $classname, $contents );

	if ( ! $display ) {
		return $html;
	}

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

}

/**
 * Displays the site description.
 *
 * @since Twenty Twenty 1.0
 *
 * @param bool $display Display or return the HTML.
 * @return string The HTML to display.
 */
function ebooks_site_description( $display = true ) {
	$description = get_bloginfo( 'description' );

	if ( ! $description ) {
		return;
	}

	$wrapper = '<div class="site-description">%s</div><!-- .site-description -->';

	$html = sprintf( $wrapper, esc_html( $description ) );

	/**
	 * Filters the HTML for the site description.
	 *
	 * @since Twenty Twenty 1.0
	 *
	 * @param string $html        The HTML to display.
	 * @param string $description Site description via `bloginfo()`.
	 * @param string $wrapper     The format used in case you want to reuse it in a `sprintf()`.
	 */
	$html = apply_filters( 'ebooks_site_description', $html, $description, $wrapper );

	if ( ! $display ) {
		return $html;
	}

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

function get_chapter_url($chapter_id) {
	global $wp_rewrite;

	if ( ! $wp_rewrite->using_permalinks() ) {
		return home_url( "/?chapter={$chapter_id}" );
	}

	return home_url( "/chapter/{$chapter_id}/" );
}
