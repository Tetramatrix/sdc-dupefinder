<?php
/*
Plugin Name: Better Dupefinder by Skincaredupes
Plugin URI: git://
Description: Better Dupefinder by Skincaredupes
Version: 1.0
Author: Chi Hoang
Author URI: https://github.com/Tetramatrix 
Date: 28.02.2022

Note: Put do_shortcode('[scd_dupefinder posts_per_page=6 title="Searched 24,144 products for a match. Possible dupes found..."]') in your theme template. For example rehub theme put it in 
single-default.php
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', 'scd_scripts' );
function scd_scripts() {
	wp_enqueue_style ( 'scd_dupefinder1',  plugins_url( 'build/index.css', __FILE__ ) );
}

add_shortcode( 'scd_dupefinder', 'scd_dupefinder_SC' );
/**
 * Registers a shortcode that simply displays a placeholder for our React App.
 */
function scd_dupefinder_SC( $atts = array(), $content = null , $tag = 'scd_dupefinder' ){
	
		$args = shortcode_atts( array(
            'title' => '',
            'posts_per_page' => ''
        ), $atts );
        
    ob_start();
    ?>     	
			  <div id="scd_dupefinder_title"><?php echo $args['title'] ?></div>
        <div id="scd_dupefinder"></div>
        <?php	
        	wp_enqueue_script( 'scd_dupefinder', plugins_url( 'build/index.js', __FILE__ ), array( 'wp-element' ), time(), true );
        	
					wp_localize_script( 'scd_dupefinder', 'scd_dupefinder_obj',
																									          				[ 'ajax_url' => admin_url('admin-ajax.php'),
																									          					'post_id' =>  get_the_ID(),
																									          					'posts_per_page' => $args['posts_per_page']
																									           				]
          			);
        	 ?>
    <?php return ob_get_clean();
}

add_action('wp_ajax_dupefinderList', 'dupefinderCallback');
add_action('wp_ajax_nopriv_dupefinderList', 'dupefinderCallback');

function dupefinderCallback() {
  global $wp_query, $post, $wpdb; // wordpress database object
  
  $start = esc_sql($_POST['start']);
  $limit = esc_sql($_POST['limit']);
	$post_id = esc_sql($_POST['post_id']);
	 
  $terms = wp_get_post_terms($post_id); 
  $term_id=[];
  foreach ($terms as $term){
    $term_id[]=$term->term_id;
	}
	$term_count=count($term_id);
	$term_id=implode(',', $term_id); 
  
  $query = "SELECT wp_posts.ID, wp_posts.post_title, GROUP_CONCAT(tt.term_id SEPARATOR ',') as termcount, count(tt.term_id) as tterms, c.termid as matchid, c.num as matches, Round(if(count(tt.term_id)>{$term_count},c.num*({$term_count}/count(tt.term_id))/{$term_count}*100,c.num*(count(tt.term_id)/{$term_count})/{$term_count}*100)) as percentage
						FROM wp_posts FORCE INDEX (idx2)
						INNER JOIN (
						SELECT object_id as ID, GROUP_CONCAT(tt.term_id SEPARATOR ',') as termid, count(tt.term_id) as num
						FROM wp_term_relationships as tr FORCE INDEX (idx3)
						INNER JOIN wp_term_taxonomy tt FORCE INDEX (PRIMARY)
						ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
						WHERE tt.taxonomy = 'post_tag'
						AND tt.term_id IN ({$term_id})
						GROUP BY ID ) AS c
						ON (wp_posts.ID = c.ID
						AND wp_posts.ID NOT IN ({$post_id}) )
						INNER JOIN wp_term_relationships tr FORCE INDEX (PRIMARY)
						ON (wp_posts.ID = tr.object_id)
						INNER JOIN wp_term_taxonomy tt FORCE INDEX (PRIMARY)
						ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
						WHERE post_type = 'post'
						AND post_status = 'publish' and tt.taxonomy = 'post_tag'
						GROUP BY wp_posts.ID
						ORDER BY percentage DESC, matches desc
						LIMIT {$start},{$limit}";
  
  $result = $wpdb->get_results($query);
  
  foreach ( $result as $post )  {
      setup_postdata( $post );
      $post->image = wp_get_attachment_image( get_post_thumbnail_id( $post->ID ), array(150,150) );
      $post->permalink = get_permalink ($post->ID);
      $post->post_terms = wp_get_post_terms($post->ID);
      $matches = explode(',',$post->matchid);
     	$post->in_common=[];
     	foreach ($matches as $match) {
		 		foreach ($post->post_terms as $pterm) {
			 		if ($match == $pterm->term_id) {
				 		$post->in_common[]=$pterm->name;
				 		break;
					}
		 		}
			}
	}    
	wp_send_json(json_encode($result) );
	wp_die();
}