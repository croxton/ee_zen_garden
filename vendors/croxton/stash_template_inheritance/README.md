#Stash template inheritance

This solution implements a version of the 'template inheritance' pattern using [Stash](https://github.com/croxton/Stash), with full page static caching.

## Routes / URL design

We're using [Resource Router](https://github.com/rsanchez/resource_router) to define routes to templates and return the 404 page for non-valid urls, or when entries or categories do not exist. Carefully defining routing allows our application responses to be predictable, prevents duplication of content, and prevents non-valid URLs from bloating the cache. It also allows us to remove routing logic from our templates and to separate out our templates so they can assume a singular responsibility (e.g. displaying a single entry, or listing posts in a blog category). This *separation of concerns* means that future edits to a template are less likely to break unrelated parts of the website.

#### Homepage 
`/`, `/P10`, `/P20` etc    
A paginated list of blog posts

#### Blog category list 
`/blog/category/[category]`, `/blog/category/[category]/P10`, etc   
A paginated list of posts assigned to given category

#### Single blog post
`/blog/[url_title]`  
A single blog post entry

#### 'Static' pages and contact form
`/[url_title]`    
A single page entry

#### API - JSON endpoint
`/api/related/[entry_id]`    
Returns category-related entries for a given entry_id in JSON format

## Template organisation

Template inheritance allows you to build a base "skeleton" template that contains all the common elements of your site and defines blocks that child templates can override ("extend"). Blocks contain default markup & content that is displayed if the block is *not* overridden by the child template. 

	default_site
	└─ blog.group
	   └─ _category.html
	   └─ _post.html
	   └─ index.html
	└─ contact.group
	   └─ index.html
	└─ site.group
	   └─ 404.html  
	   └─ _page.html 	
	Stash
	└─ layouts
	   └─ base.html
	└─ models
	   └─ post_model.html
	└─ partials
	   └─ contact_form.html
	   └─ post.html
	   └─ post_header.html
	   └─ post_list.html
	   
	   
The ExpressionEngine templates under `default_site` provide routes into the website and act a little like *controllers*. Their job is to grab data (either directly from tags or from a model), assemble a layout (from the wrapper and partials) and inject the data into it.

Our Stash directory is set up as follows:
	   
#### Layouts
Base "wrapper" templates, containing blocks and variables ("holes") that can be filled by child templates.

#### Partials
Contain chunks of template code (html markup and tags) that can be used to fill the holes in the base template(s).

#### Models
Capture and format a single entry's data, with one model typically representing a single fieldgroup. Models can also be used to encapsulate a set of data that is shared by multiple templates. As a model is intended to be reused in different contexts it should not contain markup or display logic.


## Caching

Stash static caching has been implemented to allow our blog to survive traffic volumes far beyond ExpressionEngine's normal concurrency limits (even with native caching), as static-cached pages bypass PHP entirely. Only the contact form and follow-on paginated pages remain un-cached. Logged-in editors always see the un-cached version of the website.

### Cache breaking
Mustash cache-breaking rules are used to clear individual pages when they the page is edited. Cached blog post listing pages are set to refresh automatically every 60 minutes.

### Related entries
Single blog posts load related entries via AJAX from the JSON endpoint defined in our Resource Router config, so that even when cached the page will display up-to-date related entries without needing to instantiate the full EE stack to render the related entries.

---

## Configuration

### Initial set up
* Copy `/vendors/croxton/stash_template_inheritance/_htaccess` to the ee_zen_garden root directory and rename as `.htaccess`
* Edit `/system/user/config/config.php` and add this line:

		/* Custom rules */
		require $_SERVER['DOCUMENT_ROOT'] . '/vendors/croxton/stash_template_inheritance/config/config.custom.php'; 
		
* In the same file, scroll down to `$config['encryption_key'] = "";` and enter a unique value for the key.
* Delete the existing templates folders `/system/user/templates`
* Create (or change) the symlink to the templates folder, e.g.:
	
		ln -s ~/Sites/ee_zen_garden/vendors/croxton/stash_template_inheritance/templates ~/Sites/ee_zen_garden/system/user/templates

	If you already have a symlink and need to change it:
	
		ln -nfs ~/Sites/ee_zen_garden/vendors/croxton/stash_template_inheritance/templates ~/Sites/ee_zen_garden/system/user/templates

If you can't create a symlink, simply move the templates from `/vendors/croxton/stash_template_inheritance/templates` to `/system/user/templates`.

* In the CP go to `Template Manager`, and check that the templates appear. If not, create a new template group `Blog` and save, this should force EE to sync the template files.

* Click on the `Blog` template group, click the edit button, then check 'Yes' for `Make default group?`.
		
* Go to `System Settings > Template settings` and set the following:
	* Enable Strict URLs: 'Yes'
	* 404 Page: site/404


### Install add-ons

##### First-party

* Email

##### Third-party: essential

* [Stash](https://github.com/croxton/Stash)
* [Resource Router](https://github.com/rsanchez/resource_router)

##### Third-party: optional

* [Mustash](https://github.com/croxton/Stash/wiki/Mustash)

### Create file upload locations

* **Images**
	* Server path: /images/uploads/    
 	* URL of upload Directory: /path/to/ee_zen_garden/images/uploads/


### Create field groups

##### Post
* **Subtitle** (post_subtitle) - Text input
* **Body** (post_body) - Textarea (Rich Text)
* **Image** (post_img) - File, set to 'Images' file upload location

### Add a category group

Create a category group 'Blog' (ID 1) and add categories as follows, adding a description and assigning an image to each:

##### 
* Animals
* Beards
* Beer
* Brighton
* Cheesy peas
* Featured
* Idoltry
* In depth
* Reginald P. Horse
* Sausages
* Space monkeys
* Tentacles

### Add channels

* **Blog** (channel ID 1) - assigned to the 'Posts' fieldgroup and 'Blog' category group
* **Pages** (channel ID 2) - assigned to the 'Posts' fieldgroup 


### Entries

##### Pages

Create the following entries (url_title in brackets), populating the fields with the appropriate text and image as per the original source pages:

* About (about)
* Archive (archive)
* Contact Me (contact)
* EE Zen Garden (home)
* Page Not Found (page-not-found)

##### Blog
Publish a few random entries - anything you like - and assign to one or more categories.

### Member data
* Go to `Admin > Settings > Avatars` and set max width to 1200, max height to 800 and max file size to 1024.
* Go to Members > Member fields and create
	* **Strapline** (mf_strap) - Text input
	* **Telephone** (mf_telephone) - Text input
* Now go to your `Account > My profile` page and fill in the fields.

### Mustash (optional)

* On the `Settings` screen, enable the `Channel Entries`,  `Categories` and `API` plugins.
* On the same screen, enter an API key (up to 32 character random string) and make a note of the Cache pruning URL displayed there.
* On the `Rules` screen add these cache-breaking rules:

Hook | Group | Bundle | Scope | Pattern | Comment
---- | ----- | ------ | ----- | ------- | ------
Channel Entries: all hooks | Blog | Static | Site | #^blog/{url_title}:static$# | Single blog post entry
Channel Entries: all hooks | Pages | Static | Site | #^{url_title}:static$# | Single page entry
Channel Entries: all hooks | Pages | Static | Site | #^\\\[index\\\]:{url_title}:static$# | Homepage
Categories: all hooks | Blog | Static | Site | #^blog/category/{cat_url_title}:static$# | Blog category listing

* Add a CRON to prune the cache periodically by pinging the pruning URL you noted earlier. E.g.:
 
		*/15 * * * * wget -qO- 'http://ee_zen_garden.dev/?ACT=123&key=456&prune=1' >/dev/null 2>&1













