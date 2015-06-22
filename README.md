##EE Zen Garden

ExpressionEngine is famously flexible. This can be both a blessing and a curse; sometimes it’s hard to know where to start and we are left guessing at what might be the most optimal approach. Embeds or layouts? Categories or relationships? Long-form or discrete custom fields? Should we rely on third-party addons? What about caching?

This project is a collection of template design patterns that explore different approaches to the same problem, with the aim of allowing fair comparisons to be made. I am hopeful that a common set of principles - 'best practice' - might emerge, but at the least it will allow us to share our favoured solutions to some typical problems faced by ExpressionEngine developers.


### Getting started

1. Fork [croxton/ee_zen_garden](https://github.com/croxton/ee_zen_garden/fork) on Github

2. Make a local clone of your fork: 

		git clone git@github.com:my-account/ee_zen_garden.git
		cd ee_zen_garden
		
3. Add [croxton/ee_zen_garden](https://github.com/croxton/ee_zen_garden) as a git remote:

		git remote add upstream https://github.com/croxton/ee_zen_garden.git
		
4. Create and checkout a new branch in your clone, prefixed with your vendor name and a name for your solution, e.g. 'croxton-stash_template_partials', and create your branch from the `develop` branch of the upstream repo:

		git fetch upstream
		git checkout -b croxton-stash_template_partials upstream/develop

5. Download and install a fresh copy of ExpressionEngine in the root directory of your local clone, following the [insallation instructions](https://ellislab.com/expressionengine/user-guide/installation/installation.html).
		
6. Create the following files/directories inside the `vendors` directory of your local clone:

		vendors
		└─ your_vendor_name
		   └─ your_solution_name
			  └─ README.md
			  └─ templates
			     └─ default_site	
7. 	Edit `/system/expressionengine/config/config.php` and add the full path to the templates directory of your solution:

		$conf['save_tmpl_files'] = "y";
		$conf['tmpl_file_basepath'] = "/path/to/ee_zen_garden/vendors/your_vendor_name/your_solution_name/templates";	
		
8. Now it's time to create your solution: in the `assets` directory you will find the project that you are going to build: a simple blog adapted from the open-source ['Cool Blog' bootstrap theme](http://startbootstrap.com/template-overviews/clean-blog) by [startbootstrap.com](http://startbootstrap.com/). 

	The HTML files can be found in the `assets/html` directory:
	* `index.html` - blog post listing
	* `post.html` - blog post detail
	* `category.html` - blog listing filtered by category/tag 
	* `about.html` - a one-off "static" page
	* `contact.html` - a contact form

	Your task is to configure channels and custom fields, populate with sample data, and write the templates to generate the HTML markup and interaction states represented in the html files above. These are some points to consider:
	
	* Field & variable naming schemes (nonemaclature).
	* URL design & routing.	
	* Template organisation, code re-use, global variables.
	* What, aside from content, will you make manageable by editors?
	* Which third-party add-ons would you use, if any?
	* Entry filtering.
	* Long form content editing, where images, video, pullout quotes and other elements may be included within a blog post.
	* One-off entries and global variables.	
	* SEO.
	* Caching strategies.
	
	Make a note of the steps you take to complete your solution in the `README.md` file, list any third-party dependencies you choose to install and anthing else you think might be relevant to your solution.
	
9. Edit and commit changes to your branch.
	
		git add .
		git commit -m 'What you did'
		
	(Don't worry, the `.gitignore` will mean ExpressionEngine core files are excluded when you `add .` new/modified files)
	
### Sharing your solution
		
1. Push your local commits to your GitHub fork. You might want to rebase first:

		git fetch upstream
		git rebase upstream/develop pattern-your_pattern_name
		git push --set-upstream origin pattern-your_pattern_name
		
2. Find the branch on your GitHub ee_zen_garden fork. E.g.
	[https://github.com/my-account/ee_zen_garden/branches/pattern-your_pattern_name](https://github.com/your-account/ee_zen_garden/branches/vendor-your_solution_name)
	
3. Open a new pull request:

	* Click on `Pull Request` on the right near the top of the page.
	* Choose `develop` as the base branch.	
	* Write a descriptive comment briefly explaining what your solution.
	* Click `Create pull request`.
	
4. I will review your solution for inclusion in the repo. If I ask you to make changes:

	* Make the new changes in your local clone on the same local branch.
	* Push the branch to GitHub again using the same commands as before.
	* New and updated commits will be added to the pull request automatically.
	* Add any comments to the discussion you like.


#### Image credits

Homepage: [Barney Moss](https://www.flickr.com/photos/barneymoss/), [https://flic.kr/p/tRMwyF](https://flic.kr/p/tRMwyF)

About Me: [Bob Mical](https://www.flickr.com/photos/small_realm/), [https://flic.kr/p/rWd5gJ](https://flic.kr/p/rWd5gJ)

Category: [Andrea](https://www.flickr.com/photos/sheepies/), [https://flic.kr/p/atwTbf](https://flic.kr/p/atwTbf)

Post: [Beth Scupham](https://www.flickr.com/photos/bethscupham) [https://flic.kr/p/cFb9Am](https://flic.kr/p/cFb9Am)

Post content image: [NASA on The Commons](https://www.flickr.com/photos/nasacommons/)

Contact Me: [Heather Buckley](https://www.flickr.com/photos/heatherbuckley/), [https://flic.kr/p/d9pxBm](https://flic.kr/p/d9pxBm)






