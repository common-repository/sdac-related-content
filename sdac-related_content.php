<?php
/*
Plugin Name: SDAC Related Content
Plugin URI: Plugin URI: http://www.sandboxdev.com/blog-and-cms-development/wordpress/wordpress-plugins
Description: Show Related Content by Category or by Tag with Caching. 
Author: Jennifer Zelazny/SDAC Inc.
Version: 2.3.1
Author URI: http://www.sandboxdev.com

---------------------------------------------------
Originally Query Based on the un-cached/no admin version:
http://playground.ebiene.de/400/related-posts-by-category-the-wordpress-plugin-for-similar-posts/ 
By Sergej M&uuml;ller
---------------------------------------------------
Released under the GPL license
http://www.opensource.org/licenses/gpl-license.php
---------------------------------------------------
This is an add-on for WordPress
http://wordpress.org/
---------------------------------------------------
This plugin is distributed  WITHOUT ANY WARRANTY; 
without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
---------------------------------------------------
*/

// House cleaning
if ( function_exists('register_deactivation_hook') ) { 
	register_deactivation_hook( __FILE__, 'sdac_related_content_cleanup_hook' );
}

//Clean up options table when deactivated	
function sdac_related_content_cleanup_hook() {
	delete_option('sdac_related_content_options');
}

//Custom Admin CSS
function sdac_related_content_admin_css_js() {
	global $sdac_plugin_url;
	echo '
		<style type="text/css">
			#sdacRelated fieldset {border:1px solid #aaa; margin-bottom:15px; padding:10px 10px 20px 20px; width:625px;}
			#sdacRelated fieldset legend {text-transform:uppercase;font-weight:bold;}
			#sdacRelated label {font-weight:bold;float:left;width:375px;}
			#sdacRelated input.text, #sdacRelated select {width:200px;border:1px solid #bbb;padding:2px;}
			#sdacRelated .item {float:left;width:600px;border-bottom:1px dashed #bbb;padding:5px;}
			#sdacRelated .default {font-weight:normal;font-style:italic;font-size:.85em;}
			.clearjz {clear:both;}
		</style>
		';
}

add_action('admin_init', 'sdac_related_content_init' );
add_action('admin_menu', 'sdac_related_content_add_page');


// Init plugin options to white list our options
function sdac_related_content_init(){
	register_setting( 'sdac_related_content_options', 'sdac_related_content_options', 'sdac_related_content_options_validate' );
}

// Add menu page
function sdac_related_content_add_page() {
	$sdac_related_content = add_options_page( 'SDAC Related Content', 'SDAC Related Content', 'manage_options', 'sdac_related_content_options', 'sdac_related_content_options_do_page' );
	add_action( "admin_head-$sdac_related_content", 'sdac_related_content_admin_css_js' );
}


// Draw the menu page itself
function sdac_related_content_options_do_page() {
  	?>
	<div id="sdacRelated" class="wrap">
		<h2>SDAC Related Content Options</h2>
		<form method="post" action="options.php">
			<?php settings_fields('sdac_related_content_options'); ?>
			<?php $sdac_related_content_options = get_option('sdac_related_content_options'); ?>
			<fieldset>
				<legend>Support</legend>
				<p>Free support is available for this plugin: <a href="http://www.sandboxdev.com/forums/" target="_blank">http://www.sandboxdev.com/forums/</a>.</p>
				<p>If you would like further customization of this plugin - please <a href="http://www.sandboxdev.com/contact/" target="_blank">contact us</a>.</p>
			</fieldset>
			<fieldset>
				<legend>General  Settings</legend>
					<p>Use the options below to best configure your related content and enjoy!</p>
					<div class="item">
						<label>Related Content By: <span class="default">Default: Category</span></label>
						<select name="sdac_related_content_options[related_type]">
							<option value="<?php echo $sdac_related_content_options['related_type'];?>"><?php echo esc_attr( $sdac_related_content_options['related_type'] );?></option>
							<option value="">-------</option>
							<option value="Category">Category</option>
							<option value="Tag">Tag</option>
						</select>	
					</div>
					
					<div class="item">
						<label>Related Content By: <span class="default">Default: 5</span></label>
						<select name="sdac_related_content_options[related_limit]">
							<option value="<?php echo $sdac_related_content_options['related_limit'];?>"><?php echo esc_attr( $sdac_related_content_options['related_limit'] );?></option>
							<option value="">-------</option>
							<?php $i = 1; while ( $i <= 10 ) :?>
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php $i++; endwhile;?>
						</select>	
					</div>
					
					<div class="item">
						<label>Automatically Add to Posts: <span class="default">Default: Yes</span></label>
						<select name="sdac_related_content_options[related_auto_add]">
							<option value="<?php echo $sdac_related_content_options['related_auto_add'];?>"><?php echo esc_attr( $sdac_related_content_options['related_auto_add'] );?></option>
							<option value="">-------</option>
							<option value="Yes">Yes</option>
							<option value="No">No</option>
						</select>	
					</div>
					
					<div class="item">
						<label>Time to Cache Items: <span class="default">Default: 1 Day (86400 seconds)</span></label>
						<select name="sdac_related_content_options[related_cache_time]">
							<option value="<?php echo $sdac_related_content_options['related_cache_time'];?>"><?php echo esc_attr( $sdac_related_content_options['related_cache_time'] );?></option>
							<option value="">-------</option>
							<option value="0">0 Minutes (0 seconds)</option>
							<option value="1800">30 Minutes (1800 seconds)</option>
							<option value="3600">1 Hour (3600 seconds)</option>
							<option value="7200">2 Hours (7200 seconds)</option>
							<option value="14400">4 Hours (14400 seconds)</option>
							<option value="28800">8 Hours (28800 seconds)</option>
							<option value="43200">12 Hours (43200 seconds)</option>
							<option value="86400">1 Day (86400 seconds)</option>
							<option value="172800">2 Days (172800 seconds)</option>
							<option value="259200">3 Days (259200 seconds)</option>
							<option value="604800">1 Week (604800 seconds)</option>
						</select>	
					</div>
					
					<div class="item">
						<label>Link Relationship (Rel): <span class="default">Default: Follow</span></label>
						<select name="sdac_related_content_options[related_rel]">
							<option value="<?php echo $sdac_related_content_options['related_rel'];?>"><?php echo esc_attr( $sdac_related_content_options['related_rel'] );?></option>
							<option value="">-------</option>
							<option value="Follow">Follow</option>
							<option value="No Follow">No Follow</option>
						</select>	
					</div>
					
					<div class="item">
						<label>Related Content Title: <span class="default">Default: <code>< h4 ></code>Related Posts <code>< /h4></code></span></label>
						<input type="text" class="text" name="sdac_related_content_options[related_title]" value="<?php echo esc_attr( $sdac_related_content_options['related_title'] );?>" />
					</div>
					
					<div class="item">
						<label>No Matches Text: <span class="default">Default: No Related Posts</span></label>
						<input type="text" class="text" name="sdac_related_content_options[related_no_matches]" value="<?php echo esc_attr( $sdac_related_content_options['related_no_matches'] );?>" />
					</div>
				</fieldset>
				
				<fieldset>
					<legend>Styling Settings</legend>
					
					<div class="item">
						<label>Code Before Items: <span class="default">Default: <code>< ul ></code></span></label>
						<input type="text" class="text" name="sdac_related_content_options[related_code_before_items]" value="<?php echo esc_attr( $sdac_related_content_options['related_code_before_items'] );?>" />
					</div>
					
					<div class="item">
						<label>Code After Items: <span class="default">Default: <code>< /ul ></code></span></label>
						<input type="text" class="text" name="sdac_related_content_options[related_code_after_items]" value="<?php echo esc_attr( $sdac_related_content_options['related_code_after_items'] );?>" />
					</div>
					
					<div class="item">
						<label>Code Before Link: <span class="default">Default: <code>< li ></code></span></label>
						<input type="text" class="text" name="sdac_related_content_options[related_code_before_link]" value="<?php echo esc_attr( $sdac_related_content_options['related_code_before_link'] );?>" />
					</div>
					
					<div class="item">
						<label>Code After Link: <span class="default">Default: <code>< /li ></code></span></label>
						<input type="text" class="text" name="sdac_related_content_options[related_code_after_link]" value="<?php echo esc_attr( $sdac_related_content_options['related_code_after_link'] );?>" />
					</div>
					
					<div class="item">
						<label>Code Before Linked Title (within the link): <span class="default">No Default.</span></label>
						<input type="text" class="text" name="sdac_related_content_options[related_code_before_title]" value="<?php echo esc_attr( $sdac_related_content_options['related_code_before_title'] );?>" />
					</div>
					
					<div class="item">
						<label>Code After Linked Title (within the link): <span class="default">No Default.</span></label>
						<input type="text" class="text" name="sdac_related_content_options[related_code_after_title]" value="<?php echo esc_attr( $sdac_related_content_options['related_code_after_title'] );?>" />
					</div>
				
				</fieldset>
				
				<fieldset>
					<legend>Content Settings</legend>
					
					<div class="item">
						<label>Type of Content to Display: <span class="default">Default: Posts & Pages</span></label>
						<select name="sdac_related_content_options[related_content_type]">
							<option value="<?php echo $sdac_related_content_options['related_content_type'];?>"><?php echo esc_attr( $sdac_related_content_options['related_content_type'] );?></option>
							<option value="">-------</option>
							<option value="Post">Post</option>
							<option value="Page">Page</option>
							<option value="Post & Page">Post & Page</option>
							<option value="Attachment">Attachment</option>
						</select>	
					</div>
					
					<div class="item">
						<label>Order Related Content: <span class="default">Default: DESC</span></label>
						<select name="sdac_related_content_options[related_order]">
							<option value="<?php echo $sdac_related_content_options['related_order'];?>"><?php echo esc_attr( $sdac_related_content_options['related_order'] );?></option>
							<option value="">-------</option>
							<option value="DESC">DESC (Newest to Oldest)</option>
							<option value="ASC">ASC (Oldest to Newest)</option>
							
						</select>	
					</div>
					
					<div class="item">
						<label>Order Related Content By: <span class="default">Default: Post Date</span></label>
						<select name="sdac_related_content_options[related_orderby]">
							<option value="<?php echo $sdac_related_content_options['related_orderby'];?>"><?php echo esc_attr( $sdac_related_content_options['related_orderby'] );?></option>
							<option value="">-------</option>
							<option value="Post Date">Post Date</option>
							<option value="Random">Random</option>
						</select>	
					</div>
					
					<div class="item">
						<label>Display (Echo) Items: <span class="default">Default: Yes</span></label>
						<select name="sdac_related_content_options[related_echo]">
							<option value="<?php echo $sdac_related_content_options['related_echo'];?>"><?php echo esc_attr( $sdac_related_content_options['related_echo'] );?></option>
							<option value="">-------</option>
							<option value="Yes">Yes</option>
							<option value="No">No</option>
						</select>	
					</div>
				
				</fieldset>
				
				<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
		</form>					
	</div>
	<?php	
}


//Validate
function sdac_related_content_options_validate($input) {
	//General
	$input['related_type'] =  esc_attr( $input['related_type']) ;
	$input['related_limit'] =  intval( $input['related_limit'] );
	$input['related_auto_add'] =  esc_attr( $input['related_auto_add'] );
	$input['related_cache_time'] =  intval( $input['related_cache_time'] );
	$input['related_rel'] =  esc_attr( $input['related_rel'] );
	$input['related_title'] =  esc_attr( $input['related_title'] );
	$input['related_no_matches'] =  esc_attr( $input['related_no_matches'] );
	
	//Styling
	$input['related_code_before_items'] = esc_html( $input['related_code_before_items'] );
	$input['related_code_after_items'] = esc_html( $input['related_code_after_items'] );
	$input['related_code_before_link'] = esc_html( $input['related_code_before_link'] );
	$input['related_code_after_link'] = esc_html( $input['related_code_after_link'] );
	$input['related_code_before_title'] = esc_html( $input['related_code_before_title'] );
	$input['related_code_after_title'] = esc_html( $input['related_code_after_title'] );
	
	//Content
	$input['related_content_type'] = esc_attr( $input['related_content_type']);
	$input['related_order'] = esc_attr( $input['related_order']);
	$input['related_orderby'] = esc_attr( $input['related_orderby']);
	$input['related_echo'] = esc_attr( $input['related_echo']);
		
	return $input;
}

//Expensive Query Setup...
function sdac_related_content_by_type( $sdac_paramaters = '',  $post_id = 0 ) {
	global $wpdb, $post;
	$sdac_paramaters = get_option('sdac_related_content_options');
		
	//General Defaults
	if (!$sdac_paramaters['related_type']) {
		$sdac_related_type = 'category';
	} else {
		if ($sdac_paramaters['related_type'] == 'Category') {
			$sdac_related_type = 'category';
		} else {
			$sdac_related_type = 'post_tag';
		}	
	}
	
	if (!$sdac_paramaters['related_limit']) {
		$sdac_related_limit = '5';
	} else {
		$sdac_related_limit = $sdac_paramaters['related_limit'];
	}
	
	if (!$sdac_paramaters['related_auto_add']) {
		$sdac_related_auto_add = 'Yes';
	} else {
		$sdac_related_auto_add = $sdac_paramaters['related_auto_add'];
	}
	
	if (!$sdac_paramaters['related_cache_time']) {
		$sdac_related_cache_time = '86400';
	} else {
		$sdac_related_cache_time = $sdac_paramaters['related_cache_time'];
	}
	
	if (!$sdac_paramaters['related_rel']) {
		$sdac_related_rel = 'follow';
	} else {
		if ($sdac_paramaters['related_rel'] == 'Follow') {
			$sdac_related_rel = 'follow';
		} else {
			$sdac_related_rel = 'no-follow';
		}	
	}
	
	if (!$sdac_paramaters['related_title']) {
		$sdac_related_title = '<h4>Related Posts</h4>';
	} else {
		$sdac_related_title = html_entity_decode($sdac_paramaters['related_title']);
	}
	
	if (!$sdac_paramaters['related_no_matches']) {
		$sdac_related_no_matches = 'No Related Posts';
	} else {
		$sdac_related_no_matches = html_entity_decode($sdac_paramaters['related_no_matches']);
	}
	
	//Styling Defaults
	if (!$sdac_paramaters['related_code_before_items']) {
		$sdac_related_code_before_items = '<ul>';
	} else {
		$sdac_related_code_before_items = html_entity_decode($sdac_paramaters['related_code_before_items']);
	}
	
	if (!$sdac_paramaters['related_code_after_items']) {
		$sdac_related_code_after_items = '</ul>';
	} else {
		$sdac_related_code_after_items = html_entity_decode($sdac_paramaters['related_code_after_items']);
	}
	
	if (!$sdac_paramaters['related_code_before_link']) {
		$sdac_related_code_before_link = '<li>';
	} else {
		$sdac_related_code_before_link = html_entity_decode($sdac_paramaters['related_code_before_link']);
	}
	
	if (!$sdac_paramaters['related_code_after_link']) {
		$sdac_related_code_after_link = '</li>';
	} else {
		$sdac_related_code_after_link = html_entity_decode($sdac_paramaters['related_code_after_link']);
	}
	
	if (!$sdac_paramaters['related_code_before_title']) {
		$sdac_related_code_before_title = '';
	} else {
		$sdac_related_code_before_title = html_entity_decode($sdac_paramaters['related_code_before_title']);
	}
	
	if (!$sdac_paramaters['related_code_after_title']) {
		$sdac_related_code_after_title = '';
	} else {
		$sdac_related_code_after_title = html_entity_decode($sdac_paramaters['related_code_after_title']);
	}
	
	
	//Content Defaults
	if (!$sdac_paramaters['related_content_type']) {
		$sdac_related_content_type = 'post';
	} else {
		if ($sdac_paramaters['related_content_type'] == 'Post') {
			$sdac_related_content_type = 'post';
		} elseif ($sdac_paramaters['related_content_type'] == 'Page') {
			$sdac_related_content_type = 'page';
		} elseif ($sdac_paramaters['related_content_type'] == 'Attachment') {
			$sdac_related_content_type = 'attachment';
		} else {
			$sdac_related_content_type = '';
		}	
	}
	if (!$sdac_paramaters['related_order']) {
		$sdac_related_order = 'DESC';
	} else {
		if ($sdac_paramaters['related_order'] == 'DESC') {
			$sdac_related_order = 'DESC';
		} else {	
			$sdac_related_order = 'ASC';
		}
	}
	
	if (!$sdac_paramaters['related_orderby']) {
		$sdac_related_orderby = 'post_date';
	} else {
		if ($sdac_paramaters['related_orderby'] == 'RAND') {
			$sdac_related_orderby = 'RAND';
		} else {	
			$sdac_related_orderby = 'post_date';
		}
	}
	
	if (!$sdac_paramaters['related_echo']) {
		$sdac_related_echo = true;
	} else {
		$sdac_related_echo = false;
	}
	
	
	// Set Up Query
	$related_entries = array();
	$output = '';
	
	if (!$post_id) { 
		$post_id = $post->ID;
	}
	
	$related_entries = wp_cache_get($post_id, "sdac_related_content_cache");
	if (false === $related_entries) {
 		$related_entries = $wpdb->get_results(
		sprintf(
			"SELECT DISTINCT object_id, post_title FROM {$wpdb->term_relationships} r, {$wpdb->term_taxonomy} t, {$wpdb->posts} p WHERE t.term_id IN (SELECT t.term_id FROM {$wpdb->term_relationships} r, {$wpdb->term_taxonomy} t WHERE r.term_taxonomy_id = t.term_taxonomy_id AND t.taxonomy = '$sdac_related_type' AND r.object_id = $post_id) AND r.term_taxonomy_id = t.term_taxonomy_id AND p.post_status = 'publish' AND p.ID = r.object_id AND object_id <> $post_id %s %s %s",
			("AND p.post_type = '" .$sdac_related_content_type. "'"),
			('ORDER BY ' .$sdac_related_orderby. ' ' .$sdac_related_order),
			('LIMIT '.$sdac_related_limit)
		),
		OBJECT
		);
    	wp_cache_set($post_id, $related_entries, "sdac_related_content_cache", $sdac_related_cache_time);
    } 
  
	if ( $related_entries ) { 
		$output .= '<div id="sdac_related_posts">';
		$output .= $sdac_related_title . $sdac_related_code_before_items. "\n";
		foreach ( $related_entries as $entry ) {
			$output .=  $sdac_related_code_before_link . '<a href="'.get_permalink( $entry->object_id ).'" rel="'.$sdac_related_rel.'" title="'.$entry->post_title.'">' . $sdac_related_code_before_title .$entry->post_title . $sdac_related_code_after_title.'</a>' . $sdac_related_code_after_link. "\n";
		}
		$output .=   $sdac_related_code_after_items. "\n";
		$output .= '</div>';
	} else {
		$output = '<div id="sdac_related_posts">' . $sdac_related_no_matches . '</div>';
	}
	if ( isset($sdac_related_echo ) === true && $sdac_related_echo ) {
		return $output;
	} else {
		return $output;
	}
}

function sdac_related_content() {
	global $post;
	$related_content = sdac_related_content_by_type();
	return $related_content;
}

function sdac_add_related_content($content) {
	$sdac_related_content_options = get_option('sdac_related_content_options');
	if ( (is_page() && $sdac_related_content_options['related_auto_add'] != 'No' ) || ( !is_page() && $sdac_related_content_options['related_auto_add'] != 'No') ) {
		if (!is_single()) {
			$content = $content;
		} else {
			$content .= sdac_related_content();
		}
	
	}		
	return $content;
}

function sdac_remove_related_content($content) {
	remove_action('the_content', 'sdac_add_related_content');
	return $content;
}


$sdac_related_content_options = get_option('sdac_related_content_options');
if ($sdac_related_content_options['related_auto_add'] != 'No' || $sdac_related_content_options['related_auto_add'] != 'No') {
	add_filter('the_content', 'sdac_add_related_content');
	add_filter('the_excerpt', 'sdac_add_related_content');
	add_filter('get_the_excerpt', 'sdac_remove_related_content',9);
}
	

?>
