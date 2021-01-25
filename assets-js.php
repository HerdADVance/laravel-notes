
Mix works with your JS files by compiling modern ECMAScript, module bundling, minification, and concatenating plain JS files without any custom configuration.

	<script>
		mix.js('resources/js/app.js', 'public/js');
	</script>


Vue
===========

Mix automatically insalls the Babel plugins necessary for Vue single-file component compilation support when using the vue method:

	<script>
		mix.js('resources/js/app.js', 'public/js')
   			.vue();
	</script>

Once your JS has been compiled, you can reference it in your app:

	<head>
	    <!-- ... -->

	    <script src="/js/app.js"></script>
	</head>


React
===========

Mix can automatically install the Babel plugins necessary for React support. To get started, add a call to the react method:

	<script>
		mix.js('resources/js/app.jsx', 'public/js')
   			.react();
	</script>

Behind the scenes, Mix will download and include the appropriate babel-preset-react Babel plugin.

As with Vue, add the app.js script file to your app's HTML.


Vendor Extraction
==================

One potential downside to bundling all of your app-specific JS with vendor libraries such as React and Vue is that it makes long-term caching more difficult.

For example, a single update to your app's code will force the browser to re-download all of your vendor libraries even if they haven't changed.

If you intend to make frequent updates to your app's JS, you should consider extracting all of your vendor libraries into their own file. This way, a change to your app's code won't affect the caching of your large vendor.js file.

Mix's extract method makes this easy:

	<script>
		mix.js('resources/js/app.js', 'public/js')
    		.extract(['vue'])
	</script>

The extract method accepts an array of all libraries or modules that you wish to extract into a vendor.js file.

Using the snippet above as an example, Mix will generate the following files:

	public/js/manifest.js: The Webpack manifest runtime
	public/js/vendor.js: Your vendor libraries
	public/js/app.js: Your application code

To avoid JS errors, be sure to load these files in the proper order:

	<script src="/js/manifest.js"></script>
	<script src="/js/vendor.js"></script>
	<script src="/js/app.js"></script>


Custom Webpack Configuration
==============================

If you need to manually modify the underlying Webpack config (maybe if you have a special loader or plugin that needs to be referenced), Mix provides the webpackConfig method that allows you to merge any short Webpack config overrides.

This is appealing because it doesn't require you to maintain your own copy of the webpack.config.js file.

The webpackConfig method accepts an object, which should contain any Webpack-specific config that you wish to apply:

	<script>
		mix.webpackConfig({
		    resolve: {
		        modules: [
		            path.resolve(__dirname, 'vendor/laravel/spark/resources/assets/js')
		        ]
		    }
		});
	</script>





