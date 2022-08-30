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
?>