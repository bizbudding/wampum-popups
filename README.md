# Wampum Popups
A lightweight developer-based popups WordPress plugin utilizing [oiubounce](https://github.com/carlsednaoui/ouibounce).
* Use a simple function to create 1 or more popups (or slideups) throughout your website
* Various options allow fine-tuning
* Content template system allows clean and efficient loading of popup content
* Easy plugin updates in the WordPress Dashboard via [GitHub Updater plugin](https://github.com/afragen/github-updater)

![Wampum Popups modal example](assets/wampum-popups-modal.png)

![Wampum Popups slideup example](assets/wampum-popups-slideup.jpg)

## Basic Usage
1. Create a directory in your theme called /wampum-popups/
1. Include one or more files with your popup content
1. File location is /child-theme-name/wampum-popups/my-file-name.php
1. Use CSS to style your content any way you'd like
1. Use the template tag/function in anywhere before or in wp_footer, to ensure js file has time to load
1. Tip: A browser extention like [Cookie Inspector](https://chrome.google.com/webstore/detail/cookie-inspector/jgbbilmfbammlbbhmmgaagdkbkepnijn) is helpful as it lets you manually clear individual cookies 1 at a time

```
wampum_popup( 'my-file-name' );
```

### Example with default settings

```
$options = array(
	'css'  	=> true, 	// whether or not to load the stylesheet
	'style'	=> 'modal', // 'modal' or 'slideup'
	'time'	=> '4000',  // time in milliseconds
	'type' 	=> 'exit',  // 'exit' or 'timed'
);
wampum_popup( 'my-file-name', $options );
```

### Example showing the ability to use multiple/different popups on the same site. (Please don't be annoying!)

```
$options = array(
	'style'	=> 'slideup', 	// 'modal' or 'slideup'
	'time'	=> '4000',  	// time in milliseconds
	'type'	=> 'timed',  	// 'exit' or 'timed'
);
$args = array(
	'cookieName' => 'customCookieName_2',
);
wampum_popup( 'my-file-name', $options, $args );
```

## Full example

```
add_action( 'wampum_popups', 'prefix_do_wampum_popup' );
function prefix_do_wampum_popup() {
	// Bail if not a single post
	if ( ! is_singular('post') ) {
		return;
	}
	$options = array(
		'style'	=> 'modal',
		'type'	=> 'exit',
	);
	$args = array(
    	'cookieName' => 'prefixCustomCookiePosts',
	);
    wampum_popup( 'my-file-name', $options, $args );
}
```