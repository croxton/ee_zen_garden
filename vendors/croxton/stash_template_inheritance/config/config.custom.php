<?php

/* Debug */
$config['template_debugging'] = 'y';
$config['show_profiler'] = 'y';

/* Templates */
$config['save_tmpl_files'] = 'y';
$config['tmpl_file_basepath'] = $_SERVER['DOCUMENT_ROOT'] . '/vendors/croxton/stash_template_inheritance/templates';
$config['hidden_template_indicator'] = '_'; /* must be set explicitly for Resource Router */

/* Stash */
$config['stash_file_basepath'] =  $_SERVER['DOCUMENT_ROOT'] . '/vendors/croxton/stash_template_inheritance/templates/stash/';
$config['stash_file_sync'] = FALSE; // set to FALSE for production
$config['stash_static_basepath'] = $_SERVER['DOCUMENT_ROOT'] . '/vendors/croxton/stash_template_inheritance/static_cache/';
$config['stash_static_url'] = '/vendors/croxton/stash_template_inheritance/static_cache/'; // should be a relative url
$config['stash_static_cache_enabled'] = TRUE; // set to TRUE to enable static caching
$config['stash_prune_enabled'] = FALSE; // using CRON
$config['stash_cookie_enabled'] = FALSE; // not using USER scope, so we don't need it

/**
 * Routes
 * 
 * Defines rules for routing requests to templates
 * 
 */
$config['resource_router'] = array(

	/* homepage, optionally with pagination in segment_1 (e.g. /P1) */
	'(|P\d+)' => function($router, $wildcard="") {

		// require an entry with url_title 'home'
		$router->setWildcard(1, 'home');
		if (FALSE === $wildcard->isValidUrlTitle(array('channel_id' => 2))) {
			$router->set404(); 
		}
	},

	/* one-off pages (channel_id = 2), but DON'T match segment_1 pagination */
	'(?!P\d+):url_title' => function($router, $wildcard) {
		if ($wildcard->isValidUrlTitle(array('channel_id' => 2))) {	

			switch($wildcard->value) {

				// contact form
				case "contact" :
					$router->setTemplate('contact');
					break;

				// homepage
				case "home" :
					return; // don't allow homepage to be duplicated at /home
					break;

				// any other single entry page
				default :
					$router->setTemplate('site/_page');
			}

			// contact form
			if ($wildcard->value === 'contact') {
				$router->setTemplate('contact');
			}
		}
	},

	/* blog category listing with pagination */
	'blog/category/:category_url_title(/P\d+)?' => function($router, $wildcard, $page="") {

		// valid blog category (in group 1)?
		if ($wildcard->isValidCategoryUrlTitle(array('group_id' => 1))) {
			$router->setTemplate('blog/_category');

			// set globals for this category
			$router->setGlobal('pg_title', $wildcard->getMeta('cat_name'));
			$router->setGlobal('pg_subtitle', $wildcard->getMeta('cat_description'));

			// we need to parse the image filedir_X
			ee()->load->library('file_field');
			$image = ee()->file_field->parse_string($wildcard->getMeta('cat_image'));
			$router->setGlobal('pg_img', $image);
		}
	},

	/* blog post (channel_id = 1) */
	'blog/:url_title' => function($router, $wildcard) {

		// valid blog entry (in channel 1)?
		if ($wildcard->isValidUrlTitle(array('channel_id' => 1))) {
			$router->setTemplate('blog/_post');
		}
	},

	// JSON endpoint: get related blog entries (that are assigned to the one or more of the same categories)
	'api/related/(\d+)' => function($router, $entry_id) {

		$now = ee()->localize->now;
		$entry_id = ee()->db->escape($entry_id);
		$channel_id = 1; // blog
		$site_id = 1;
		$limit = 6;
		$result = array();

		// get categories assigned to the entry
		$sql = "SELECT exp_categories.cat_id
				FROM exp_channel_titles
				INNER JOIN exp_category_posts 
					ON exp_channel_titles.entry_id = exp_category_posts.entry_id
				INNER JOIN exp_categories 
					ON exp_category_posts.cat_id = exp_categories.cat_id
				WHERE exp_categories.cat_id IS NOT NULL
					AND exp_channel_titles.site_id = {$site_id}
					AND exp_channel_titles.entry_id = {$entry_id}";

		$query = ee()->db->query($sql);

        if ($query->num_rows() > 0) {

			$cat_ids = array();

			foreach ($query->result() as $row) {
  				$cat_ids[] = $row->cat_id;
  			}
			$cat_ids = implode(",", array_map( array(ee()->db, 'escape'), $cat_ids));

			// get related entries
			$sql = "SELECT DISTINCT(t.entry_id), t.title, t.url_title FROM exp_channel_titles AS t
					LEFT JOIN exp_channels ON t.channel_id = exp_channels.channel_id 
					LEFT JOIN exp_members AS m ON m.member_id = t.author_id 
					INNER JOIN exp_category_posts ON t.entry_id = exp_category_posts.entry_id
					INNER JOIN exp_categories ON exp_category_posts.cat_id = exp_categories.cat_id 
					WHERE t.entry_id !='' 
						AND t.site_id = {$site_id}  
						AND t.entry_date < {$now}  
						AND (t.expiration_date = 0 OR t.expiration_date > {$now}) 
						AND t.entry_id != {$entry_id} 
						AND t.channel_id = {$channel_id} 
						AND exp_categories.cat_id IN ({$cat_ids}) 
						AND t.status = 'open' 
					ORDER BY t.sticky desc, t.entry_date desc, t.entry_id desc 
					LIMIT 0, {$limit}";

			$query = ee()->db->query($sql);
			$result = $query->result();
		}	

		// return
        $router->json($result);
	},

	/* 404 for any other url patterns, except segment_1 pagination */
	'((?!P\d+$)\S+)' => function($router, $wildcard) {

		// require an entry with url_title 'page-not-found'
		$router->setWildcard(1, 'page-not-found');

		// generate metadata
		$wildcard->isValidUrlTitle(array('channel_id' => 2));

		$router->set404();
	},
 
);