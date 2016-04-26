##EE Zen Garden

ExpressionEngine is famously flexible. This can be both a blessing and a curse; sometimes it’s hard to know where to start and we are left guessing at what might be the most optimal approach. Embeds or layouts? Categories or relationships? Long-form or discrete custom fields? Should we rely on third-party addons? What about caching?

This is a growing collection of template design patterns that explore different approaches to the same problem, with the aim of allowing fair comparisons to be made. I am hopeful that a common set of principles - 'best practice' - might emerge, but at the least it will allow us to share our favoured solutions to some typical problems faced by ExpressionEngine developers.

### The challenge

Our task is to build the templates for a simple blog, adapted from the open-source 'Clean blog' bootstrap theme by [startbootstrap.com](startbootstrap.com). Your challenge is to find a solution that is as efficient, maintainable and scaleable as possible, while allowing editors to edit content in a logical fashion.

Check it out here: [http://croxton.github.io/ee_zen_garden](http://croxton.github.io/ee_zen_garden)

### Getting started

1. Fork [croxton/ee_zen_garden](https://github.com/croxton/ee_zen_garden/fork) on Github

2. Make a local clone of your fork: 

		git clone git@github.com:my-account/ee_zen_garden.git
		cd ee_zen_garden
		
3. Add [croxton/ee_zen_garden](https://github.com/croxton/ee_zen_garden) as a git remote:

		git remote add upstream https://github.com/croxton/ee_zen_garden.git
		
4. Create and checkout a new branch in your clone, prefixed with your vendor name and a name for your solution (e.g. 'croxton-stash_template_partials'), and create your branch from the `develop` branch of the upstream repo:

		git fetch upstream
		git checkout -b vendor_name-solution_name upstream/develop

5. Download and install a fresh copy of ExpressionEngine 3 in the root directory of your local clone, following the [insallation instructions](https://ellislab.com/expressionengine/user-guide/installation/installation.html).
		
6. Create the following files/directories inside the `vendors` directory of your local clone:

		vendors
		└─ your_vendor_name
		   └─ your_solution_name
			  └─ README.md
			  └─ config
			  └─ templates


7. 	Edit `/system/user/config/config.php` and add this rule:

		$conf['save_tmpl_files'] = "y";

8: Optional: create a symlink to the templates folder in your vendor directory from EE's default template location, e.g:
	
	ln -s ~/Sites/ee_zen_garden/vendors/[your vendor name]/[your solution name]/templates ~/Sites/ee_zen_garden/system/user/templates

	If you need to change the symlink:
	ln -nfs ~/path/to/the/new/location ~/Sites/ee_zen_garden/system/user/templates

If you don't want to do this, you'll need to put your templates directly in the `/system/user/templates` folder and copy them to your vendor template folder when you make changes.
		
9. Now it's time to create your solution: in the `assets` directory you will find the project that you are going to build: a simple blog adapted from the open-source ['Cool Blog' bootstrap theme](http://startbootstrap.com/template-overviews/clean-blog) by [startbootstrap.com](http://startbootstrap.com/). 

	The HTML files can be found in the `assets/html` directory:
	* `index.html` - blog post listing
	* `post.html` - blog post detail
	* `category.html` - blog listing filtered by category/tag 
	* `about.html` - a one-off "static" page
	* `contact.html` - a contact form
	* `headlines.html` - an alternative newspaper-style homepage
	* `profile.html` - an author profile
	* `archive.html` - a list of entries organised by category

	Your task is to configure channels and custom fields, populate with sample data, and write the templates to generate the HTML markup and interaction states represented in the html files above. These are some points you might want to consider, although please don't feel obliged to cover everything:
	
	* Field & variable naming schemes (nomenclature)
	* URL design & routing
	* Template organisation, code re-use and global variables
	* What, aside from content, would you make manageable by editors?
	* Third-party add-ons - which would you use, if any, and why?
	* Filtering / sorting / paginating lists of entries
	* Managing relationships between content
	* Long form content, where images, video, pullout quotes and other elements may be included within a single blog post
	* Managing one-off entries/content
	* Entry / draft previews
	* Static file and image management, image transforms and CDNs
	* Caching strategies and surviving high traffic
	* How would your solution scale, if new content types are required later?
	* Security
	* SEO
	
	Make a note of the steps you take to complete your solution in the `README.md` file, list any third-party dependencies you choose to install and anthing else you think might be relevant to your solution.
	
10. Edit and commit changes to your branch.
	
		git add .
		git commit -m 'What you did'
		
	(Don't worry, the `.gitignore` will mean ExpressionEngine core files are excluded when you `add .` new/modified files)
	
### Sharing your solution
		
1. Push your local commits to your GitHub fork. You might want to rebase first:

		git fetch upstream
		git rebase upstream/develop vendor_name-solution_name
		git push --set-upstream origin vendor_name-solution_name
		
2. Find the branch on your GitHub ee_zen_garden fork. E.g.
	[https://github.com/my-account/ee_zen_garden/branches/vendor_name-solution_name](https://github.com/your-account/ee_zen_garden/branches/vendor_name-solution_name)
	
3. Open a new pull request:

	* Click on `Pull Request` on the right near the top of the page.
	* Choose `develop` as the base branch.	
	* Write a descriptive comment briefly describing your solution.
	* Click `Create pull request`.
	
4. We will review your solution for inclusion in the repo. If we ask you to make changes:

	* Make the new changes in your local clone on the same local branch.
	* Push the branch to GitHub again using the same commands as before.
	* New and updated commits will be added to the pull request automatically.
	* Add any comments to the discussion you like.


#### Image credits

**Page headers:**

* Homepage: [Barney Moss](https://www.flickr.com/photos/barneymoss/), [https://flic.kr/p/tRMwyF](https://flic.kr/p/tRMwyF)
* About Me: [Bob Mical](https://www.flickr.com/photos/small_realm/), [https://flic.kr/p/rWd5gJ](https://flic.kr/p/rWd5gJ)
* Category: [Andrea](https://www.flickr.com/photos/sheepies/), [https://flic.kr/p/atwTbf](https://flic.kr/p/atwTbf)
* Post: [Beth Scupham](https://www.flickr.com/photos/bethscupham), [https://flic.kr/p/cFb9Am](https://flic.kr/p/cFb9Am)
* Contact: [Heather Buckley](https://www.flickr.com/photos/heatherbuckley/), [https://flic.kr/p/d9pxBm](https://flic.kr/p/d9pxBm)
* Archive: [Cat Burton](https://www.flickr.com/photos/catburton/), [https://flic.kr/p/8EtSuw](https://flic.kr/p/8EtSuw)


**Headlines:**

* Mustache: [Aaron Morton](https://www.flickr.com/photos/amorton/), [https://flic.kr/p/35Vzgy](https://flic.kr/p/35Vzgy)
* Seagull: [Maxime Raphael](https://www.flickr.com/photos/maximeraphael/), [https://flic.kr/p/e3pHxa](https://flic.kr/p/e3pHxa)
* Kitten: [brownpau](https://www.flickr.com/photos/brownpau/), [https://flic.kr/p/dZ5e6o](https://flic.kr/p/dZ5e6o)
* Turkey: [Kurman Communications, Inc.](https://www.flickr.com/photos/kurmanphotos/), [https://flic.kr/p/pFpXBK](https://flic.kr/p/pFpXBK)
* Death: [Jack Lu](https://www.flickr.com/photos/leo19981/), [https://flic.kr/p/4B8KJf](https://flic.kr/p/4B8KJf)
* Richard Dawkins and Jesus: [Surian Soosay](https://www.flickr.com/photos/ssoosay/), [https://flic.kr/p/8ReJb2](https://flic.kr/p/8ReJb2)
* Brighton Pride: [heather buckley](https://www.flickr.com/photos/heatherbuckley/), [https://flic.kr/p/acYyEG](https://flic.kr/p/acYyEG)
* Chipmunk:[Karim Rezk](https://www.flickr.com/photos/krezk/), [https://flic.kr/p/4A5Zsz](https://flic.kr/p/4A5Zsz)
* Mating toads: [philip hay](https://www.flickr.com/photos/minipixel/), [https://flic.kr/p/KwqJN](https://flic.kr/p/KwqJN)
* Statue of Liberty: [Celso FLORES](https://www.flickr.com/photos/celso/), [https://flic.kr/p/7cqfD3](https://flic.kr/p/7cqfD3)
* Middle-aged footballer [Ken K](https://www.flickr.com/photos/kenkuchih/), [https://flic.kr/p/bCqzhP](https://flic.kr/p/bCqzhP)
* Lego builder: [clement127](https://www.flickr.com/photos/clement127/), [https://flic.kr/p/qJvJ6u](https://flic.kr/p/qJvJ6u)
* Artillery: [The US Army](https://www.flickr.com/photos/soldiersmediacenter/), [https://flic.kr/p/5tWobB](https://flic.kr/p/5tWobB)

**Post content images:**

* Astronaut: [NASA on The Commons](https://www.flickr.com/photos/nasacommons/)







