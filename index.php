<?php
	require_once __DIR__.'/core.php';
	\APLib\Core::init();
	\APLib\Config::set(
		'title',
		'APLib - A PHP library to create your website smooth, easy & secure'
	);
	\APLib\FrontEnd::add(
		array(
			'command'      =>  'refresh',
			'response'     =>  array(
				'command'    =>  'alert',
				'title'      =>  'Welcome to APLib',
				'message'    =>  'A PHP library to create your website smooth, easy & secure',
				'type'       =>  'success',
				'placement'  =>  array(
					'from'     =>  'bottom',
					'align'    =>  'left'
				)
			)
		)
	);
	\APLib\Response\Body::add(body());
	\APLib\Core::run();
	function body(){
		ob_start(); ?>
		<center><?php
		echo \APLib\Bootstrap::navbar(
			array(
				array(
					'action'     =>  '#welcome',
					'menu'       =>  '',
					'label'      =>  'Welcome'
				),
				array(
					'action'     =>  '#brief',
					'menu'       =>  '',
					'label'      =>  'Brief'
				)
				,
				array(
					'menu'       =>  '',
					'action'     =>  '#thumbs',
					'label'      =>  'Components',
					'left'       =>  ''
				),
				array(
					'menu'       =>  '',
					'dropmenu'   =>  '',
					'items'      =>  array(
						array('#saying1', 'Innovation'),
						array('divider', ''),
						array('#saying2', 'Creativity')
					),
					'label'     =>  'My Secrets'
				),
				array(
					'menu'      =>  '',
					'action'    =>  '#todo',
					'label'     =>  'To-Do',
					'left'      =>  ''
				)
			),
			array(
				'title'      =>  'APLib',
				'action'     =>  APLibHTML,
				'id'         =>  'mainNav',
				'collapses'  =>  '',
				'sr text'    =>  'APLib Navbar'
			),
			3,
			false
		);
		\APLib\Response\Body\CSS::add("body {background-color: #eee;} #mainNav{ z-index: 1000; width: 100%;}");
		\APLib\Bootstrap::affix("#mainNav", 0);
		\APLib\Response\Body\JavaScript::add(
			"$('#mainNav a').on('click', function(event){
					if (this.hash !== ''){
						event.preventDefault();
						var hash = this.hash;
						$('html, body').animate({ scrollTop: $(hash).offset().top-50 }, 1500, function(){
							$(hash).css({ border: '0px solid #00ff00' }).animate({ borderWidth: 4 }, 100, function(){
								$(this).animate({ borderWidth: 0 }, 100)
							});
						});
					}
				});"
		);
		\APLib\Bootstrap::scrollspy('body', '#mainNav', 100);
		echo \APLib\Bootstrap::jumbotron(
			'Welcome to APLib',
			'A PHP library to create your '.\APLib\Bootstrap::keyboard('website').' smooth, easy & secure',
			3,
			'welcome'
		);
		echo "\r\n			<div id='brief'>";
		echo \APLib\Bootstrap::dl(
			'Brief',
			'A brief of what '.\APLib\Bootstrap::keyboard('APLib').' is.',
			array(
				array('A PHP Library', 'A library full of ready-to-use components to help you create your website fast.'),
				array('ALMA PRO Library', 'Created with the sight of a hacker. Security, Automation & Hackablity.'),
				// array('ALMA PRO LEADER Library', 'A leading PHP library created with the porpuse of showing newbie websites\' creators the '.\APLib\Bootstrap::mark('difference').' between<BR> a '.\APLib\Bootstrap::abbreviation(\APLib\Bootstrap::keyboard('PHP').' developer', "A coder who writes PHP code.\r\nA REAL developer.").' & a '.\APLib\Bootstrap::abbreviation(\APLib\Bootstrap::code('WordPress').' developer', "A fool who thinks editing WordPress templates is developing :/").'.')
			),
			4
		);
		echo "\r\n			</div>";
		echo "\r\n			<div id='thumbs'>";
		echo "\r\n				".\APLib\Bootstrap::well(
			'<h1>What forms '.\APLib\Bootstrap::code('APLib').'</h1>',
			'well-lg'
		);
		echo \APLib\Bootstrap::thumbnails(
			array(
				array(
					'url'      =>  'http://php.net/manual/en/intro-whatis.php',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/PHP.png'),
					'alt'      =>  'PHP',
					'caption'  =>  'PHP (Hypertext Preprocessor): a widely-used open source general-purpose scripting language that is especially suited for web development and can be embedded into HTML.'
				),
				array(
					'url'      =>  'https://en.wikipedia.org/wiki/HTML5',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/html5.png'),
					'alt'      =>  'HTML5',
					'caption'  =>  'HTML5 is a markup language used for structuring and presenting content on the World Wide Web. It is the fifth and current major version of the HTML standard.'
				),
				array(
					'url'      =>  'https://en.wikipedia.org/wiki/JavaScript',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/js.png'),
					'alt'      =>  'JavaScript',
					'caption'  =>  'JavaScript (often abbreviated as JS): a high-level, dynamic, weakly typed, prototype-based, multi-paradigm, and interpreted programming language.'
				),
				array(
					'url'      =>  'https://developer.mozilla.org/en/docs/Web/CSS/CSS3',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/css3.png'),
					'alt'      =>  'CSS3',
					'caption'  =>  'CSS3 is the latest evolution of the Cascading Style Sheets language and aims at extending CSS2.1. It brings a lot of long-awaited novelties, like rounded corners, shadows, gradients, transitions or animations, as well as new layouts like multi-columns, flexible box or grid layouts.'
				),
				array(
					'url'      =>  'https://jquery.com/',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/jquery.svg'),
					'alt'      =>  'jQuery',
					'caption'  =>  'jQuery is a fast, small, and feature-rich JavaScript library. It makes things like HTML document traversal and manipulation, event handling, animation, and Ajax much simpler with an easy-to-use API that works across a multitude of browsers. With a combination of versatility and extensibility, jQuery has changed the way that millions of people write JavaScript.'
				),
				array(
					'url'      =>  'https://jqueryui.com/',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/jquery-ui.png'),
					'alt'      =>  'jQuery UI',
					'caption'  =>  'jQuery UI is a curated set of user interface interactions, effects, widgets, and themes built on top of the jQuery JavaScript Library. Whether you\'re building highly interactive web applications or you just need to add a date picker to a form control, jQuery UI is the perfect choice.'
				),array(
					'url'      =>  'https://getbootstrap.com/',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/bootstrap.png'),
					'alt'      =>  'Bootstrap',
					'caption'  =>  'Bootstrap is a free and open-source front-end web framework for designing websites and web applications.'
				),
				array(
					'url'      =>  'http://fontawesome.io/',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/fontawesome.png'),
					'alt'      =>  'Font-Awesome',
					'caption'  =>  'Font Awesome is a font and icon toolkit based on CSS and LESS. It was made by Dave Gandy for use with Twitter Bootstrap, and later was incorporated into the BootstrapCDN.'
				),
				array(
					'url'      =>  'https://github.com/daneden/animate.css/',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/animate.css.png'),
					'alt'      =>  'Animate.css',
					'caption'  =>  'Animate.css is a bunch of cool, fun, and cross-browser animations for you to use in your projects. Great for emphasis, home pages, sliders, and general just-add-water-awesomeness.'
				),
				array(
					'url'      =>  'https://www.highcharts.com/',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/highcharts.png'),
					'alt'      =>  'HighCharts',
					'caption'  =>  'Highcharts is a charting library written in pure JavaScript, offering an easy way of adding interactive charts to your web site or web application.'
				),
				array(
					'url'      =>  'https://github.com/rendro/easy-pie-chart',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/easypiechart.jpg'),
					'alt'      =>  'Easy Pie Chart',
					'caption'  =>  'Easy Pie Chart is a lightweight plugin to draw simple, animated pie charts for single values.'
				),
				array(
					'url'      =>  'http://bootstrap-notify.remabledesigns.com/',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/bootstrap-notify.png'),
					'alt'      =>  'Bootstrap Notify',
					'caption'  =>  'Bootstrap Notify formally known as Bootstrap notify was renamed at version 3.0.0. This project originally started out to be a pull request for <a href="https://github.com/ifightcrime/bootstrap-notify" target="_blank">ifightcrime\'s Bootstrap notify</a> plugin, but quickly grew into it\'s own. This is the reason the two plugins shared a name and I chose that it was time that my plugin got its own name.'
				),
				array(
					'url'      =>  'https://select2.org',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/select2.png'),
					'alt'      =>  'Select2',
					'caption'  =>  'The jQuery replacement for select boxes<BR><BR>Select2 gives you a customizable select box with support for searching, tagging, remote data sets, infinite scrolling, and many other highly used options.'
				),
				array(
					'url'      =>  'http://fancyapps.com/fancybox/3/',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/fancybox.png'),
					'alt'      =>  'fancyBox3',
					'caption'  =>  'jQuery lightbox script for displaying images, videos and more.<BR>Touch enabled, responsive and fully customizable. '
				),
				array(
					'url'      =>  'http://gijsroge.github.io/tilt.js/',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/tilt.js.png'),
					'alt'      =>  'Tilt.js',
					'caption'  =>  'A tiny requestAnimationFrame powered 60+fps lightweight parallax hover tilt effect for jQuery.'
				),
				array(
					'url'      =>  'https://jonsuh.com/hamburgers/',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/hamburgers.gif'),
					'alt'      =>  'Hamburgers',
					'caption'  =>  'Tasty CSS-animated hamburgers.'
				),
				array(
					'url'      =>  'http://chartjs.org/',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/chart.js.svg'),
					'alt'      =>  'Chart.js',
					'caption'  =>  'Simple yet flexible JavaScript charting for designers & developers.'
				),
				array(
					'url'      =>  'http://responsiveslides.com/',
					'image'    =>  \APLib\Extras::NormalizePath(APLibHTML.'../imgs/logos/responsiveslides.js.png'),
					'alt'      =>  'ResponsiveSlides.js',
					'caption'  =>  'Simple & lightweight responsive slider plugin (in 1kb).'
				)
			),
			4
		);
		\APLib\Response\Body\JavaScript::add('$("img[src*=\'tilt.js.png\']").tilt({scale: 1.1, glare: true, maxGlare: .6, speed: 400, maxtilt: 50, perspective: 500});');
		echo "\r\n			</div>";
		echo "\r\n			<div id='saying1'>";
		echo \APLib\Bootstrap::blockquote(
			"Variety of thinking is the mother of innovation.",
			"ALMA PRO LEADER",
			4
		);
		echo "\r\n			</div>";
		echo "\r\n			<div id='saying2'>";
		echo \APLib\Bootstrap::blockquote(
			"Diversity is the mother of creativity.",
			"Unknown",
			4
		);
		echo "\r\n			</div>";
		echo \APLib\Bootstrap::table(
			array('Name', 'Function', 'Feature', 'Upgrade', 'Description', 'Progress'),
			array(
				array(
					'Examples',
					'',
					\APLib\Bootstrap::icon(\APLib\Bootstrap::ICON_CHECK),
					'',
					'Usage examples of each Function & Feature in the library and how to create specific website desings using the APLib.',
					\APLib\Bootstrap::progress(
						array(
							array(
								'extra classes'  =>  'progress-bar-success progress-bar-striped active',
								'width'          =>  '70',
								'value now'      =>  '70',
								'value min'      =>  '0',
								'value max'      =>  '100'
							)
						),
						9
					)
				),
				array(
					'Complete Components',
					'',
					'',
					\APLib\Bootstrap::icon(\APLib\Bootstrap::ICON_CHECK),
					'Include all bootstrap\'s components as well as all other APLib components\' functionalities.',
					\APLib\Bootstrap::progress(
						array(
							array(
								'extra classes'  =>  'progress-bar-danger progress-bar-striped active',
								'width'          =>  '30',
								'value now'      =>  '30',
								'value min'      =>  '0',
								'value max'      =>  '100'
							)
						),
						9
					)
				),
				array(
					'Object-alike elements',
					'',
					\APLib\Bootstrap::icon(\APLib\Bootstrap::ICON_CHECK),
					'',
					"This feature is planned before the library's first publish, but it will take a lot of work and time, so it's been delayed.<BR>This feature will allow you to customize your elements before you render them. As well as the ability to remove/add certain things from/to elements themselves.",
					\APLib\Bootstrap::progress(
						array(
							array(
								'extra classes'  =>  'progress-bar-danger progress-bar-striped active',
								'width'          =>  '10',
								'value now'      =>  '10',
								'value min'      =>  '0',
								'value max'      =>  '100'
							)
						),
						9
					)
				),
				array(
					'Render',
					\APLib\Bootstrap::icon(\APLib\Bootstrap::ICON_CHECK),
					'',
					'',
					'A function to be used with \'Object-alike elements\' after they\'ve been fully customized.',
					\APLib\Bootstrap::progress(
						array(
							array(
								'extra classes'  =>  'progress-bar-danger progress-bar-striped active',
								'width'          =>  '10',
								'value now'      =>  '10',
								'value min'      =>  '0',
								'value max'      =>  '100'
							)
						),
						9
					)
				),
				array(
					'Fully Responsive View',
					'',
					'',
					\APLib\Bootstrap::icon(\APLib\Bootstrap::ICON_CHECK),
					'Create costume CSS/CSS3 style sheets to make website\'s elements as responsive as possible on different screen sizes.',
					\APLib\Bootstrap::progress(
						array(
							array(
								'extra classes'  =>  'progress-bar-success progress-bar-striped active',
								'width'          =>  '90',
								'value now'      =>  '90',
								'value min'      =>  '0',
								'value max'      =>  '100'
							)
						),
						9
					)
				),
				array(
					'Support Needs',
					'',
					\APLib\Bootstrap::icon(\APLib\Bootstrap::ICON_CHECK),
					'',
					'Add support to User Login, Multiple Views, Files\' Upload handling, Forms Inputs\' filtering, etc...',
					\APLib\Bootstrap::progress(
						array(
							array(
								'extra classes'  =>  'progress-bar-warning',
								'width'          =>  '50',
								'value now'      =>  '50',
								'value min'      =>  '0',
								'value max'      =>  '100'
							)
						),
						9
					)
				),
				array(
					'Privacy',
					'',
					\APLib\Bootstrap::icon(\APLib\Bootstrap::ICON_CHECK),
					'',
					'A feature aiming on encrypting traffic between the client & the server in a way that even the server\'s monitoring system won\'t know what\'s being transferred.',
					\APLib\Bootstrap::progress(
						array(
							array(
								'extra classes'  =>  'progress-bar-danger',
								'width'          =>  '5',
								'value now'      =>  '5',
								'value min'      =>  '0',
								'value max'      =>  '100'
							)
						),
						8
					)
				),
				array(
					'Detect Attacks',
					'',
					'',
					\APLib\Bootstrap::icon(\APLib\Bootstrap::ICON_CHECK),
					'Detect attacks based on the behaviour of certain requests & clients.',
					\APLib\Bootstrap::progress(
						array(
							array(
								'extra classes'  =>  'progress-bar-warning progress-bar-striped active',
								'width'          =>  '65',
								'value now'      =>  '65',
								'value min'      =>  '0',
								'value max'      =>  '100'
							)
						),
						8
					)
				),
				array(
					'Block Attacks',
					'',
					'',
					\APLib\Bootstrap::icon(\APLib\Bootstrap::ICON_CHECK),
					'An upgrade of Security which comes to bring a sense of challenge to those attackers & pentesters whom trying to break-in. Still, nothing to worry about in present.',
					\APLib\Bootstrap::progress(
						array(
							array(
								'extra classes'  =>  'progress-bar-danger progress-bar-striped active',
								'width'          =>  '20',
								'value now'      =>  '20',
								'value min'      =>  '0',
								'value max'      =>  '100'
							)
						),
						8
					)
				)
			),
			3,
			'todo'
		);
		echo "\r\n"; ?>
		</center><?php
		return ob_get_clean();
	}
?>
