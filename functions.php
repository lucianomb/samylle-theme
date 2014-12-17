<?php
/**
 * Samylle Aguiar functions and definitions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * @package Samylle Aguiar
 * @since 0.1.0
 */

setlocale (LC_ALL, 'pt_BR');

 // Useful global constants
define( 'WPTHEME_VERSION', '0.1.0' );
 
 /**
  * Set up theme defaults and register supported WordPress features.
  *
  * @uses load_theme_textdomain() For translation/localization support.
  *
  * @since 0.1.0
  */
 function wptheme_setup() {
	/**
	 * Makes Samylle Aguiar available for translation.
	 *
	 * Translations can be added to the /lang directory.
	 * If you're building a theme based on Samylle Aguiar, use a find and replace
	 * to change 'wptheme' to the name of your theme in all template files.
	 */
	load_theme_textdomain( 'wptheme', get_template_directory() . '/languages' );
 }
 add_action( 'after_setup_theme', 'wptheme_setup' );
 
 /**
  * Enqueue scripts and styles for front-end.
  *
  * @since 0.1.0
  */
 function wptheme_scripts_styles() {
	$postfix = ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ? '' : '.min';
		
	wp_enqueue_style( 'wptheme', get_template_directory_uri() . "/assets/css/samylle_aguiar{$postfix}.css", array(), WPTHEME_VERSION );
 }
 add_action( 'wp_enqueue_scripts', 'wptheme_scripts_styles' );

/*
* Thumbnails
*/
add_theme_support( 'post-thumbnails' );

//100x100
add_image_size( "sidebar", 100, 100, 1 );
//635x???
add_image_size( "post", 635, 9999 );
//130x94
add_image_size( "related", 130, 94, 1 );
//80x80
add_image_size( "profile", 80, 80, 1 );
//320x320
add_image_size( "home-square", 320, 320, 1 );
//650x320
add_image_size( "home-wide", 650, 320, 1 );

/*
* Excerpts
*/
function custom_excerpt_length( $length ) {
	return 120;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

function new_excerpt_more( $more ) {
	return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

/*
* Ajax Functions
*/

function sidebar_archive(){
	if( $_POST["order"] == "popular" ) {
		query_posts( "meta_key=wpb_post_views_count&orderby=meta_value_num&posts_per_page=4" );
		while ( have_posts() ) : the_post();
		?>
		<li><a href="<?=get_permalink();?>"><?
			the_post_thumbnail( 'sidebar' );
			echo "<p><strong>" . get_the_title() . "</strong> " . substr( get_the_excerpt(), 0, strrpos( substr( get_the_excerpt(), 0, 80), ' ' ) ) . "...</p>";
		?></a></li>
		<?
		endwhile;
		?>
		<li class="clear-both"></li>
		<?
	} else if( $_POST["order"] == "recent" ){
		query_posts( "posts_per_page=4" );
		while ( have_posts() ) : the_post();
		?>
		<li><a href="<?=get_permalink();?>"><?
			the_post_thumbnail( 'sidebar' );
			echo "<p><strong>" . get_the_title() . "</strong> " . substr( get_the_excerpt(), 0, strrpos( substr( get_the_excerpt(), 0, 80), ' ' ) ) . "...</p>";
		?></a></li>
		<?
		endwhile;
		wp_reset_query();
		?>
		<li class="clear-both"></li>
		<?
	}
	die();
}
add_action( 'wp_ajax_nopriv_sidebar_archive', 'sidebar_archive' );
add_action( 'wp_ajax_sidebar_archive', 'sidebar_archive' );

//contact form
function submit_contact_form(){
	$headers = 'From:' . $_POST["name"] . ' <' . $_POST["email"] . '>\r\n';
	wp_mail("sag_mg@hotmail.com", 'Contato pelo site', $_POST["text"], $headers );
	die();
}
add_action( 'wp_ajax_nopriv_submit_contact_form', 'submit_contact_form' );
add_action( 'wp_ajax_submit_contact_form', 'submit_contact_form' );

//random colors!
function colorize() {
	$colors = array( "another-pink", "brown", "blue", "another-blue", "wine", "pink" );
	$chosen = array_rand($colors);
	return $colors[$chosen];
}

//random sections!
function sectionize() {
	$sections = get_categories("exclude=1&hide_empty=0");
	$chosen = array_rand($sections);
	return $sections[$chosen]->cat_ID;
}

//set icon!
function show_icon( $icon ) {
	switch ( $icon ) {
		case 'text':
			return '<span class="text sprite">texto</span>';
			break;
		case 'video':
			return '<span class="video sprite">vídeo</span>';
			break;
		case 'instagram':
			return '<span class="instagram sprite">instagram</span>';
			break;
		case 'foto':
			return '<span class="foto sprite">foto</span>';
			break;
		default:
			return '';
			break;
	}
}

//choose category!
function find_section( $categories, $type ) {
	$section = (count($categories) > 1 && $categories[0]->term_id == 1) ? $categories[1] : $categories[0];
	if( $section->slug != "sem-categoria" ) {
		if( $type == "post" ) {
			return '<strong>' . $section->name . ':</strong> ';
		} else {
			return $section->slug;
		}
	} else {
		if( $type == "post" ) {
			return false;
		} else {
			return "sa";
		}
	}
}

//Count views
function set_post_views($postID) {
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
//To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

//Echo views
function get_post_views($postID){
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 views";
    }
    return $count.' views';
}

//Get related posts
function get_related_tag_posts_ids( $post_id, $number = 4 ) {
    $related_ids = false;
    $post_ids = array();
    // get tag ids belonging to $post_id
    $tag_ids = wp_get_post_tags( $post_id, array( 'fields' => 'ids' ) );
    if ( $tag_ids ) {
        // get all posts that have the same tags
        $tag_posts = get_posts(
            array(
                'posts_per_page' => -1, // return all posts
                'no_found_rows'  => true, // no need for pagination
                'fields'         => 'ids', // only return ids
                'post__not_in'   => array( $post_id ), // exclude $post_id from results
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'post_tag',
                        'field'    => 'id',
                        'terms'    => $tag_ids,
                        'operator' => 'IN'
                    )
                )
            )
		);
        // loop through posts with the same tags
        if ( $tag_posts ) {
            $score = array();
            $i = 0;
            foreach ( $tag_posts as $tag_post ) {
                // get tags for related post
                $terms = wp_get_post_tags( $tag_post, array( 'fields' => 'ids' ) );
                $total_score = 0;

                foreach ( $terms as $term ) {
                    if ( in_array( $term, $tag_ids ) ) {
                        ++$total_score;
                    }
                }

                if ( $total_score > 0 ) {
                    $score[$i]['ID'] = $tag_post;
                    // add number $i for sorting
                    $score[$i]['score'] = array( $total_score, $i );
                }
                ++$i;
            }

            // sort the related posts from high score to low score
            uasort( $score, 'sort_tag_score' );
            // get sorted related post ids
            $related_ids = wp_list_pluck( $score, 'ID' );
            // limit ids
            $related_ids = array_slice( $related_ids, 0, (int) $number );
    	}
	}
	return $related_ids;
}
 
function sort_tag_score( $item1, $item2 ) {
    if ( $item1['score'][0] != $item2['score'][0] ) {
        return $item1['score'][0] < $item2['score'][0] ? 1 : -1;
    } else {
        return $item1['score'][1] < $item2['score'][1] ? -1 : 1; // ASC
    }
}

function sa_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( 'Página %s', max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'sa_wp_title', 10, 2 );

function get_first_image( $size = false ) {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if( $size ) {
  	$first_img_parts = pathinfo( $first_img );
  	$size = ( $size ) ? "-" . $size . "." : "" ;
	$new_img = $first_img_parts{"dirname"} . "/" . $first_img_parts{"filename"} . $size . $first_img_parts{"extension"};
	$first_img = ( file_exists( $new_img ) ) ? $new_img : $first_img ;
  }

  if(empty($first_img)){ //Defines a default image
    $first_img = false;
  }
  return $first_img;
}