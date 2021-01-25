
Your app's webpack.mix.js file is the entry point for all asset compilation.

It's a light configuration wrraper around webpack. Mix tasks can be chained together to define exactly how your assets should be compiled.



Tailwind CSS
==============

Tailwind CSS is a modern, utility-first framework for building amazing sites without ever leaving your HTML.

To install Tailwind and generate our Tailwind config file:

<?
	npm install

	npm install -D tailwindcss

	npx tailwindcss init 
?>

The init command generates a tailwind.config.js file. This file configures the paths to all of your app's templates and JS so that Tailwind can tree-shake unused styles when optimizing your CSS for production.

	<script>
		purge: [
		    './storage/framework/views/*.php',
		    './resources/**/*.blade.php',
		    './resources/**/*.js',
		    './resources/**/*.vue',
		],
	</script>

Next, add each of Tailwind's "layers" to your app's resources/css/app.css file:

	<style>
		@tailwind base;
		@tailwind components;
		@tailwind utilities;
	</style>

Once you've configured Tailwind's layers, update your webpack.mix.js file to compile your Tailwind powered CSS:

	<script>
		mix.js('resources/js/app.js', 'public/js')
		    .postCss('resources/css/app.css', 'public/css', [
		        require('tailwindcss'),
		    ]);
	</script>

Finally, reference your stylesheet in your app's primary layout template. Many app's store this template at resources/views/layouts/app.blade.php. Also add the responsive viewport meta tag if not already present:

	<head>
	    <meta charset="UTF-8" />
	    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	    <link href="/css/app.css" rel="stylesheet">
	</head>


PostCSS
==========

PostCSS, a powerful tool for transforming your CSS, is included with Mix out of the box.

By default, Mix leverages the popular Autoprefixer plugin to automatically apply all necessary CSS3 vendor prefixes.

First, install the desired plugin through NPM and include it in your array of plugins when calling Mix's postCss method. 

This method accepts the path to your CSS file as its 1st argument and the directory where the compiled file should be placed as its 2nd argument.

	<script>
		mix.postCss('resources/css/app.css', 'public/css', [
		    require('postcss-custom-properties')
		]);

		// Or, execute postCss with no additional plugins for simple CSS compilation and minification
		mix.postCss('resources/css/app.css', 'public/css');
	</script>


Sass
============

The sass method allows you to compile Sass into CSS that can be understood by web browsers. 

This method accepts the path to your Sass file as its 1st argument and the directory where the compiled file should be placed as its 2nd argument.

	<script>
		mix.sass('resources/sass/app.scss', 'public/css');

		// Or, call the sass method multiple times to compile multiple Sass files into their own respective CSS files and customize the output directory of the resulting CSS 
		mix.sass('resources/sass/app.sass', 'public/css')
	    .sass('resources/sass/admin.sass', 'public/css/admin');
	</script>


URL Processing
==================

Because Mix is built on top of Webpack, it's important to understand a few webpack concepts.

For CSS compilation, webpack will rewrite and optimize and url() calls within your stylesheets. Imagine that we want to compile Sass that includes a relative URL to an image:

	<style>
		.example {
	    	background: url('../images/example.png');
		}
	</style>

By default, Mix and webpack will find example.png and copy it to your public/images folder. Then it will rewrite the url() withing your generated stylesheet, making your compiled CSS be:

	<style>
		.example {
		    background: url(/images/example.png?d41d8cd98f00b204e9800998ecf8427e);
		}
	</style>

To turn this feature off:
	<script>
		mix.sass('resources/sass/app.scss', 'public/css').options({
		    processCssUrls: false
		});

		// Or, this to turn off URL matching or copying assets to the public directory
		.example {
		    background: url("../images/thing.png");
		}
	</script>


Source Maps
==============

Though disabled by default, source maps can be activated by calling the mix.sourceMaps() method in your webpack.mix.js file.

It comes with a compile/performance cost but will provide extra debugging info to your browser's dev tools:

	<script>
		mix.js('resources/js/app.js', 'public/js')
	    	.sourceMaps();
	</script>








