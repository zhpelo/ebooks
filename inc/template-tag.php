<?php

function get_chapter_url($chapter_id) {
	global $wp_rewrite;

	if ( ! $wp_rewrite->using_permalinks() ) {
		return home_url( "/?chapter={$chapter_id}" );
	}
	return home_url( "/chapter/{$chapter_id}/" );
}


/**
 * Displays the site logo, either text or image.
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
 */
function ebooks_site_description( $display = true ) {
	$description = get_bloginfo( 'description' );

	if ( ! $description ) {
		return;
	}

	$wrapper = '<div class="site-description">%s</div><!-- .site-description -->';

	$html = sprintf( $wrapper, esc_html( $description ) );

	$html = apply_filters( 'ebooks_site_description', $html, $description, $wrapper );

	if ( ! $display ) {
		return $html;
	}

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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


/**
 * Filters classes of wp_list_pages items to match menu items.
 * 过滤 wp_list_pages 项的类以匹配菜单项。
 * Filter the class applied to wp_list_pages() items with children to match the menu class, to simplify.
 * 过滤应用于带有子项的 wp_list_pages() 项目的类以匹配菜单类，以简化。
 * styling of sub levels in the fallback. Only applied if the match_menu_classes argument is set.
 * 后备中子级别的样式。仅在设置了 match_menu_classes 参数时应用。
 *
 * @since Twenty Twenty 1.0
 *
 * @param string[] $css_class    An array of CSS classes to be applied to each list item.
 * @param WP_Post  $page         Page data object.
 * @param int      $depth        Depth of page, used for padding.
 * @param array    $args         An array of arguments.
 * @param int      $current_page ID of the current page.
 * @return array CSS class names.
 */
function ebooks_filter_wp_list_pages_item_classes( $css_class, $page, $depth, $args, $current_page ) {

	// Only apply to wp_list_pages() calls with match_menu_classes set to true.
	$match_menu_classes = isset( $args['match_menu_classes'] );

	if ( ! $match_menu_classes ) {
		return $css_class;
	}

	// Add current menu item class.
	if ( in_array( 'current_page_item', $css_class, true ) ) {
		$css_class[] = 'current-menu-item';
	}

	// Add menu item has children class.
	if ( in_array( 'page_item_has_children', $css_class, true ) ) {
		$css_class[] = 'menu-item-has-children';
	}

	return $css_class;

}

add_filter( 'page_css_class', 'ebooks_filter_wp_list_pages_item_classes', 10, 5 );


/**
 * Adds a Sub Nav Toggle to the Expanded Menu and Mobile Menu.
 * 向扩展菜单和移动菜单添加子导航切换。
 * @since Twenty Twenty 1.0
 *
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param WP_Post  $item  Menu item data object.
 * @param int      $depth Depth of menu item. Used for padding.
 * @return stdClass An object of wp_nav_menu() arguments.
 */
function ebooks_add_sub_toggles_to_main_menu( $args, $item, $depth ) {

	// Add sub menu toggles to the Expanded Menu with toggles.
	if ( isset( $args->show_toggles ) && $args->show_toggles ) {

		// Wrap the menu item link contents in a div, used for positioning.
		$args->before = '<div class="ancestor-wrapper">';
		$args->after  = '';

		// Add a toggle to items with children.
		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {

			$toggle_target_string = '.menu-modal .menu-item-' . $item->ID . ' > .sub-menu';
			$toggle_duration      = twentytwenty_toggle_duration();

			// Add the sub menu toggle.
			$args->after .= '<button class="toggle sub-menu-toggle fill-children-current-color" data-toggle-target="' . $toggle_target_string . '" data-toggle-type="slidetoggle" data-toggle-duration="' . absint( $toggle_duration ) . '" aria-expanded="false"><span class="screen-reader-text">' . __( 'Show sub menu', 'twentytwenty' ) . '</span>' . twentytwenty_get_theme_svg( 'chevron-down' ) . '</button>';

		}

		// Close the wrapper.
		$args->after .= '</div><!-- .ancestor-wrapper -->';

		// Add sub menu icons to the primary menu without toggles.
	} elseif ( 'primary' === $args->theme_location ) {
		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$args->after = '<span class="icon"></span>';
		} else {
			$args->after = '';
		}
	}

	return $args;

}

add_filter( 'nav_menu_item_args', 'ebooks_add_sub_toggles_to_main_menu', 10, 3 );

?>