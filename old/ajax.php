<?php

/*
Plugin Name: Related Posts By Taxonomy Template by Skincaredupes
Plugin URI: git://
Description: Display related posts with a template
Version: 1.0
Author: Chi Hoang
Author URI: https://github.com/Tetramatrix 

Note: Put file rpbt_skincaredupes.php in theme/{mytheme}/related-post-plugin/
Template can be called with shortcode: do_shortcode('[related_posts_by_tax format="skincaredupes" taxonomies="post_tag" posts_per_page="30" title="Searched 18,786 Products For A Match. Possible Dupes 
Found:"]')
*/


	require_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
  global $post, $wpdb; // wordpress database object
  
  $query = "SELECT wp_posts.ID, wp_posts.post_title, GROUP_CONCAT(tt.term_id SEPARATOR ',') as termcount, count(tt.term_id) as tterms, c.termid as matchid, c.num as matches, Round(if(count(tt.term_id)>27,c.num*(27/count(tt.term_id))/27*100,c.num*(count(tt.term_id)/27)/27*100)) as percentage
FROM wp_posts
INNER JOIN (
SELECT object_id as ID, GROUP_CONCAT(tt.term_id SEPARATOR ',') as termid, count(tt.term_id) as num
FROM wp_term_relationships as tr FORCE INDEX (idx3)
INNER JOIN wp_term_taxonomy tt FORCE INDEX (PRIMARY)
ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
WHERE tt.taxonomy = 'post_tag'
AND tt.term_id IN (481, 498, 548, 554, 637, 652, 655, 662, 705, 712, 952, 1209, 1281, 1341, 1606, 2582, 2589, 2591, 2649, 2677, 2680, 2915, 2960, 2969, 9341, 9343, 9347)
GROUP BY ID ) AS c
ON (wp_posts.ID = c.ID
AND wp_posts.ID NOT IN (304116) )
INNER JOIN wp_term_relationships tr FORCE INDEX (PRIMARY)
ON (wp_posts.ID = tr.object_id)
INNER JOIN wp_term_taxonomy tt FORCE INDEX (PRIMARY)
ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
WHERE post_type = 'post'
AND post_status = 'publish' and tt.taxonomy = 'post_tag'
GROUP BY wp_posts.ID
ORDER BY percentage DESC, matches desc
LIMIT 0,5";
  $result = $wpdb->get_results($query);
  
	//$result = do_shortcode('[related_posts_by_tax format="skincaredupes" taxonomies="post_tag" posts_per_page="5" title="Searched"]');
	
	
	wp_send_json(json_encode($result) );
	wp_die();
 