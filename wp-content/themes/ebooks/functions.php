<?php
//去除window._wpemojiSettings
remove_action("admin_print_scripts","print_emoji_detection_script");
remove_action("admin_print_styles","print_emoji_styles");
remove_action("wp_head","print_emoji_detection_script",7);
remove_action("wp_print_styles","print_emoji_styles");
remove_filter("the_content_feed","wp_staticize_emoji");
remove_filter("comment_text_rss","wp_staticize_emoji");
remove_filter("wp_mail","wp_staticize_emoji_for_email");


/**
 * Register and Enqueue Styles.
 *
 * @since Ebooks
 */
function ebooks_register_styles() {
	$theme_version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'style', get_stylesheet_uri(), array(), $theme_version );
}
add_action( 'wp_enqueue_scripts', 'ebooks_register_styles' );


/**
 * Register navigation menus uses wp_nav_menu in five places.
 *
 * @since Twenty Twenty 1.0
 */
function ebooks_menus() {

	$locations = array(
		'primary'  => __( 'Desktop Horizontal Menu', 'ebooks' ),
		'expanded' => __( 'Desktop Expanded Menu', 'ebooks' ),
		'mobile'   => __( 'Mobile Menu', 'ebooks' ),
		'footer'   => __( 'Footer Menu', 'ebooks' ),
		'social'   => __( 'Social Menu', 'ebooks' ),
	);

	register_nav_menus( $locations );
}

add_action( 'init', 'ebooks_menus' );


function wpmaker_menu_classes($classes, $item, $args) {
	$classes[] = 'nav-item';
	return $classes;
}
add_filter('nav_menu_css_class','wpmaker_menu_classes',1,3);

function sonliss_menu_link_atts( $atts, $item, $args ) {
	$atts['class'] = 'nav-link';//将test修改为你的类名
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'sonliss_menu_link_atts', 10, 3 );


//开启特色图像
add_theme_support( "post-thumbnails" );

add_image_size( 'cover-sm', 130, 184 ); 
add_image_size( 'cover-md', 215, 302 ); 


// function wanlimm_function(){
// 	add_theme_page( '主题设置', 'wanlimm主题设置', 'administrator', 'ashu_slug','ssmay_function');
// }
// add_action('admin_menu', 'wanlimm_function');


add_action( 'load-themes.php', 'the_table_install' );

function the_table_install() {    
    global $wpdb; 
    $table_name = isset($table_prefix) ? ($table_prefix . 'prestige') : ($wpdb->prefix . 'prestige'); //获取表前缀，并设置新表的名称 
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = " CREATE TABLE `" . $table_name . ("` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) DEFAULT NULL COMMENT '用户id',
        `post_id` int(11) DEFAULT NULL COMMENT '文章id',
        `old` int(11) DEFAULT '0' COMMENT '原始声望',
        `apply` int(11) DEFAULT NULL COMMENT '操作声望',
        `new` int(11) DEFAULT NULL COMMENT '新声望',
        `type` enum('register','login','publish_post','publish_course','comment','publish_question','answer_question','fabulous','cancel_fabulous','collect','cancel_collect') NOT NULL DEFAULT 'publish_post' COMMENT '类型：注册 登录 发文章 发教程 评论 提问 回答问题 被点赞 取消点赞 被收藏 取消收藏',
        `time` datetime DEFAULT NULL COMMENT '操作时间',
        `note` varchar(255) DEFAULT NULL COMMENT '说明备注',
        PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=") . DB_CHARSET . (" COMMENT='声望记录表';");
        require_once (ABSPATH . ("wp-admin/includes/upgrade.php"));
        dbDelta($sql);
    }
}