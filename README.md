# Slim Popups
A lightweight developer-based popups plugin utilizing [oiubounce](https://github.com/carlsednaoui/ouibounce).
* Use a simple function to create 1 or more popups (or slideups) throughout your website
* Various options allow fine-tuning
* Content template system allows clean and efficient loading of popup content


![Slim Popups modal example](assets/slim-popups-modal.png)

![Slim Popups slideup example](assets/slim-popups-slideup.png)

## Basic Usage
1. Create a directory in your theme called /slim-popups/
1. Include one or more files with your popup content
1. File location is /child-theme-name/slim-popups/my-file-name.php
1. Use CSS to style your content any way you'd like
1. Use the template tag/function in anywhere before or in wp_footer, to ensure js file has time to load
1. Tip: A browser extention like [Cookie Inspector](https://chrome.google.com/webstore/detail/cookie-inspector/jgbbilmfbammlbbhmmgaagdkbkepnijn) is helpful as it lets you manually clear individual cookies 1 at a time

```
slim_popup( 'my-file-name' );
```

### Example with default settings

```
$options = array(
	'css'  	=> true, 	// whether or not to load the stylesheet
	'style'	=> 'modal', // 'modal' or 'slideup'
	'time'	=> '4000',  // time in milliseconds
	'type' 	=> 'exit',  // 'exit' or 'timed'
);
slim_popup( 'my-file-name', $options );
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
slim_popup( 'my-file-name', $options, $args );
```

## Full example

```
add_action( 'wp_footer', 'prefix_do_slim_popup' );
function prefix_do_slim_popup() {
	// Bail if Slim Popups is not active
	if ( ! function_exists('slim_popup') ) {
		return;
	}
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
    slim_popup( 'my-file-name', $options, $args );
}
```