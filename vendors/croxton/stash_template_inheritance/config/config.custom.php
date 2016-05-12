<?php

/* Debug */
$config['template_debugging'] = 'n';
$config['show_profiler'] = 'n';

/* remove index.php */
$config['index_page'] = '';

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
$config['stash_static_cache_index'] = TRUE; // set to TRUE to use Stash as an index only when static caching (variable value not saved)
$config['stash_query_strings'] = FALSE; // set to TRUE to cache query strings when referencing the current uri with @URI
$config['stash_prune_enabled'] = FALSE; // using CRON
$config['stash_cookie_enabled'] = FALSE; // not using USER scope, so we don't need it
$config['stash_default_scope'] = 'local'; // default variable scope if not specified
$config['stash_default_refresh'] = 0; // default cache refresh period in minutes


/**
 * Routes
 * 
 * Defines rules for routing requests to templates
 * 
 */

$config['resource_router'] = array(

	/* runs before ALL routes 
		- set a flag to determine if further rules should be processed
	*/
	':before' => function($router) {
		$router->continue = TRUE;
	},

	/* homepage, optionally with pagination in segment_1 (e.g. /P1) 

		^		Start of line (automatically added by Resource Router)	
		( 		Start of capturing group ($wildcard)
		| 		Matches NULL (i.e. empty string) OR the following characters
		P 		Match the character 'P'
		\d+ 	Match one or more digits (+ makes \d "greedy")
		)		End of capturing group
		$ 		End of line (automatically added by Resource Router)
	*/
	'(|P\d+)' => function($router, $wildcard="") {

		// require an entry with url_title 'home'
		$router->setWildcard(1, 'home');
		if ($wildcard->isValidUrlTitle(array('channel_id' => 2))) {
			$router->setGlobal('pg_entry_id', $wildcard->getMeta('entry_id'));
		}

		// stop any other rules being processed, if this rule was matched
		$router->continue = FALSE;
	},

	/* headlines */
	'(headlines)' => function($router, $wildcard="") {

		// get Category ID for the 'featured' category
		$router->setWildcard(1, 'featured');

		if ($wildcard->isValidCategoryUrlTitle())
		{
			$router->setGlobal('pg_cat_id:featured', $wildcard->getMeta('cat_id'));
		}

		// get Category ID for the 'in-depth' category
		$router->setWildcard(1, 'in-depth');

		if ($wildcard->isValidCategoryUrlTitle())
		{
			$router->setGlobal('pg_cat_id:in_depth', $wildcard->getMeta('cat_id'));
		}

		// get Category ID for the 'animals' category
		$router->setWildcard(1, 'animals');

		if ($wildcard->isValidCategoryUrlTitle())
		{
			$router->setGlobal('pg_cat_id:animals', $wildcard->getMeta('cat_id'));
		}

		$router->setTemplate('headlines');
	},

	/* one-off pages (channel_id = 2), but DON'T match segment_1 pagination 
	
		^			Start of line (automatically added by Resource Router)
		(?! 		Start of negative lookahead - assert that we should NOT match the following characters
		P 			Match the character 'P'
		\d+ 		Match one or mnore digits (+ makes \d "greedy")
		)			End of negative lookahead
		:url_title 	Match the URL Title of an entry, save as a capture group ($wildcard)
		$ 			End of line (automatically added by Resource Router)
	*/
	':url_title' => function($router, $wildcard) {

		if ( ! $router->continue) return; // ignore pagination in segemnt_1

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

				// archive
				case "archive" :
					$router->setTemplate('archive');
					break;	

				// any other single entry page
				default :
					$router->setTemplate('site/_page');
			}

			// set globals for use in templates
			$router->setGlobal('pg_entry_id', $wildcard->getMeta('entry_id'));
		}
	},

	/* blog category listing, optionally with with pagination in segment 4

		^						Start of line (automatically added by Resource Router)
		blog/category/			Match these characters literally
		:category_url_title		Match a Category URL Title and save as a capture group ($wildcard)
		( 						Start of another capturing group ($page)
		/P 						Match these characters literally
		\d+ 					Match one or more digits (+ makes \d "greedy")
		)						End of capture group
		?						Quantifier meaning 'zero or more' of preceding capture group - i.e. make the capture group optional
		$ 						End of line (automatically added by Resource Router)
	*/
	'blog/category/:category_url_title(/P\d+)?' => function($router, $wildcard, $page="") {

		// valid blog category (in group 1)?
		if ($wildcard->isValidCategoryUrlTitle(array('group_id' => 1))) {
			$router->setTemplate('blog/_category');

			// set globals for this category
			$router->setGlobal('pg_cat_id', $wildcard->getMeta('cat_id'));
			$router->setGlobal('pg_title', $wildcard->getMeta('cat_name'));
			$router->setGlobal('pg_subtitle', $wildcard->getMeta('cat_description'));

			// we need to parse the image filedir_X
			ee()->load->library('file_field');
			$image = ee()->file_field->parse_string($wildcard->getMeta('cat_image'));
			$router->setGlobal('pg_img', $image);
		}
	},

	/* blog post (channel_id = 1) 

		^				Start of line (automatically added by Resource Router)
		blog/			Match these characters literally
		:url_title		Match the URL Title of an entry, save as a capture group ($wildcard)
		$ 				End of line (automatically added by Resource Router)
	*/
	'blog/:url_title' => function($router, $wildcard) {

		// valid blog entry (in channel 1)?
		if ($wildcard->isValidUrlTitle(array('channel_id' => 1))) {
			$router->setTemplate('blog/_post');

			// set globals for use in templates
			$router->setGlobal('pg_entry_id', $wildcard->getMeta('entry_id'));
		}
	},

	/* JSON endpoint: get related blog entries (that are assigned to the one or more of the same categories)

		^				Start of line (automatically added by Resource Router)
		api/related/	Match these characters literally
		( 				Start a capturing group ($entry_id)
		\d+ 			Match one or more digits (+ makes \d "greedy")
		) 				End of capturing group
		$ 				End of line (automatically added by Resource Router)
	*/
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

	'api/posts/by/(\d+)' => function($router, $member_id) {

		$now = ee()->localize->now;
		$member_id = ee()->db->escape($member_id);
		$channel_id = 1; // blog
		$site_id = 1;
		$limit = 6;
		$result = array();

		// get most recent entries for a given member ID
		$sql = "SELECT DISTINCT(t.entry_id), t.title, t.url_title FROM exp_channel_titles AS t
				WHERE t.entry_id !='' 
					AND t.site_id = {$site_id}  
					AND t.entry_date < {$now}  
					AND (t.expiration_date = 0 OR t.expiration_date > {$now}) 
					AND t.channel_id = {$channel_id} 
					AND t.author_id = {$member_id} 
					AND t.status = 'open' 
				ORDER BY t.sticky desc, t.entry_date desc, t.entry_id desc 
				LIMIT 0, {$limit}";

		$query = ee()->db->query($sql);
		$result = $query->result();

		// return
        $router->json($result);
	},

	/* member profile */
	'profile/:member_id' => function($router, $wildcard="") {
		if ($wildcard->isValidMemberId(array('group_id' => 1))) {
			$router->setTemplate('profile');
		}
	},

	/* member profile vcard */
	'profile/vcard/:member_id' => function($router, $wildcard="") {
		if ($wildcard->isValidMemberId(array('group_id' => 1))) {
			$router->setTemplate('profile/vcard');
		}
	},

	/* Generate a 404 for any other non-empty url, except segment_1 pagination 

		^				Start of line (automatically added by Resource Router)
		( 				Start a capturing group ($wildcard)
		.+				Match one or more characters
		)				End of capturing group
		$ 				End of line (automatically added by Resource Router)
	*/
	'(.+)' => function($router, $wildcard) {

		if ( ! $router->continue) return;

		// require an entry with url_title 'page-not-found'
		$router->setWildcard(1, 'page-not-found');

		// generate metadata
		$wildcard->isValidUrlTitle(array('channel_id' => 2));

		// set globals for use in templates
		$router->setGlobal('pg_entry_id', $wildcard->getMeta('entry_id'));

		return $router->set404();
	},

);