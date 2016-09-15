# Slim Popups Usage
===================

## Template tag
* Use anywhere before wp_footer, to ensure js file has time to load
* Create a directory in your theme called /slim-popups/
* Include one or more files with your popup content
* This example has /child-theme-name/slim-popups/main-popup.php

slim_popup( 'main-popup' );

* This example shows the function with its' default settings
```
$options = array(
	'css'  			=> true, 	// whether or not to load the stylesheet
	'style'			=> 'modal', // 'modal' or 'slideup'
	'time'			=> '4000',  // time in milliseconds
	'type' 			=> 'exit',  // 'exit' or 'timed'
);
slim_popup( 'main-popup', $options );
```

* This example shows the ability to use multiple/different popups on the same site. (Please don't be annoying!)
```
$options = array(
	'css'  			=> true, 	// whether or not to load the stylesheet
	'style'			=> 'slide', // 'modal' or 'slideup'
	'time'			=> '4000',  // time in milliseconds
	'type' 			=> 'timed', // 'exit' or 'timed'
);
slim_popup( 'main-popup', $options, array(
	'cookieName' 	=> 'customCookieName_2',
) );
```