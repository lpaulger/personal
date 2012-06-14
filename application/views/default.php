<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <!-- Use the .htaccess and remove these lines to avoid edge case issues.
             More info: h5bp.com/i/378 -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title>LP Projects</title>
        <meta name="description" content="">

        <!-- Mobile viewport optimized: h5bp.com/viewport -->
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->
        <link rel="icon" type="image/png" href="assets/img/favicon.ico">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/bootstrap-responsive.min.css">

        <link rel="stylesheet" href="assets/css/layout.css">
        <link rel="stylesheet" href="assets/css/theme.css">


        <!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

        <!-- All JavaScript at the bottom, except this Modernizr build.
             Modernizr enables HTML5 elements & feature detects for optimal performance.
             Create your own custom Modernizr build: www.modernizr.com/download/ -->
        <script src="js/libs/modernizr-2.5.3.min.js"></script>

        <!-- Templates -->
        <script type="x-jquery-tmpl" id="user-template">
        <div>
            <div class="user-item">
                <a href="#TeamMember/${id}/${name}">
                    <img src="${imageLarge}"/>
                </a>
                <h3>${login}</h3>
            </div>
        </div>
    </script>

    <script type="x-jquery-tmpl" id="user-details-template">
        <div class="user-details well">
            <h3>${name}</h3>
            <a href="#userDetails/${id}/${name}"><img src="${imageLarge}"/></a>
            <label>Email:</label> <span>${email}</span>
            <label>Location:</label> <span>${location}</span>
            <div class="clear-both"></div>
        </div>
    </script>

    <script type="x-jquery-tmpl" id="post-template">
        <div class="post-item accordion-group">
            <div class="accordion-heading">
                <span class="badge">${source}</span>
                <span class="post-time" title="${timeago}">${timeago}</span>
                <img src="${image}" />
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#contentList" href="#listItem${datetime}">
                    <h4>${title}</h4>
                </a>
                
                
                <p>{{html message}}</p>

                <div class="clear-both"></div>
            </div>
            <div id="listItem${datetime}" class="accordion-body collapse">
                <div class="accordion-inner">
                    <p>Stuff about the user or something can go here!</p>
                </div>
            </div>
        </div>
    </script>

    <script type="x-jquery-tmpl" id="projects-dropdown-template">
        <li><a href="#Project/${id}/${name}" data-toggle="collapse" data-target=".nav-collapse">${name}</a></li>
    </script>

    <script type="x-jquery-tmpl" id="repo-details-template">
        <div class="repo-details">
            <h3>${name}</h3>
            <a href="${homepage}">${homepage}</a>
            <span class="badge">${language}</span>
            <span>${updated_at}</span>
        </div>
    </script>

    <script type="x-jquery-tmpl" id="homepage-content">

        <!-- homepage Carousel -->

        <!--Body content-->
        <div id="myCarousel" class="carousel hidden-phone">
            <!-- Carousel items -->
            <div class="carousel-inner">
                <div class="active item"><img src="assets/img/content/couch.jpg"/><div class="carousel-caption">
                        <h4>Chillin on the couch.</h4>
                        <p>Yea I do what I do</p>
                    </div></div>
                <div class="item"><img src="assets/img/content/snow.jpg"/><div class="carousel-caption">
                        <h4>Copper Mountain</h4>
                        <p>Yea, I went down that cliff!</p>
                    </div></div>
                <div class="item"><img src="assets/img/content/train.jpg"/><div class="carousel-caption">
                        <h4>Falling with a train</h4>
                        <p>Awesome picture taken by my sister</p>
                    </div></div>
            </div>
            <!-- Carousel nav -->
            <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
            <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
        </div>

    </script>

    <script type="x-jquery-tmpl" id="hobbies-history-content">
        <div class="span6">
            <h3>Hobbies</h3>
            <ul class="accordion" id="Hobbies">
                <li class="accordion-group">
                    <div class="accordion-heading">
                        <a href="#Hobbies1" class="accordion-toggle" data-toggle="collapse" data-parent="#Hobbies">Programming</a>
                        <div id="Hobbies1" class="accordion-body in">
                            <div class="accordion-inner">
                                <div class="thumbnail-container"><img src="assets/img/thumbnails/programming.jpg" title="programming"/></div>
                                <p>I am always finding new and interesting things to program, just check me out on <a href="http://github.com/lpaulger" title="github.com/lpaulger">github</a> to see some of the projects I've sunk my teeth into!</p>
                                <div class="clear-both"></div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-group">
                    <div class="accordion-heading">
                        <a href="#Hobbies2" class="accordion-toggle" data-toggle="collapse" data-parent="#Hobbies">Longboarding</a>
                        <div id="Hobbies2" class="accordion-body in">
                            <div class="accordion-inner">
                                <div class="thumbnail-container"><img src="assets/img/thumbnails/longboarding.jpg" title="longboarding"/></div>
                                <p>Last year I picked up longboarding after some convincing from my friends. I fell in love with cruising now the pavement and scaring the crap out of me.</p>
                                <div class="clear-both"></div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-group">
                    <div class="accordion-heading">
                        <a href="#Hobbies3" class="accordion-toggle" data-toggle="collapse" data-parent="#Hobbies">Snowboarding</a>
                        <div id="Hobbies3" class="accordion-body in">
                            <div class="accordion-inner">
                                <div class="thumbnail-container"><img src="assets/img/thumbnails/snowboarding.jpg" title="snowboarding"/></div>
                                <p>I've been snowboarding for years, and finally got a chance to go out west this year to Colorado. Colorado mountains are amazing!</p>
                                <div class="clear-both"></div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-group">
                    <div class="accordion-heading">
                        <a href="#Hobbies4" class="accordion-toggle" data-toggle="collapse" data-parent="#Hobbies">Gaming</a>
                        <div id="Hobbies4" class="accordion-body in">
                            <div class="accordion-inner">
                                <div class="thumbnail-container"><img src="assets/img/thumbnails/gaming.jpg" title="gaming"/></div>
                                <p>I'm an avid gamer. Find me on steam or playing any strategy or roleplaying games in what little free time I have.</p>
                                <div class="clear-both"></div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-group">
                    <div class="accordion-heading">
                        <a href="#Hobbies5" class="accordion-toggle" data-toggle="collapse" data-parent="#Hobbies">Artwork</a>
                        <div id="Hobbies5" class="accordion-body in">
                            <div class="accordion-inner">
                                <div class="thumbnail-container"><img src="assets/img/thumbnails/artwork.jpg" title="artwork"/></div>
                                <p>Artwork has been my passion and a great way to express myself in the past. I'm hoping to keep art as part of my life as I continue down this road.</p>
                                <div class="clear-both"></div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="span6">
            <h3>History</h3>
            <ul class="accordion" id="History">
                <li class="accordion-group">
                    <div class="accordion-heading">
                        <a href="#History1" class="accordion-toggle" data-toggle="collapse" data-parent="#History">Education</a>
                        <div id="History1" class="accordion-body in">
                            <div class="accordion-inner">
                                <div class="thumbnail-container"><img src="assets/img/thumbnails/uvm.gif" title="uvm"/></div>
                                <h4>BS in Computer Science & Information Systems @ <a href="http://www.uvm.edu">UVM</a></h4>
                                <p>Graduated in 2011 from the College of Engineering and Mathematical Sciences.</p>
                                <div class="clear-both"></div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-group">
                    <div class="accordion-heading">
                        <a href="#History2" class="accordion-toggle" data-toggle="collapse" data-parent="#History">Work</a>
                        <div id="History2" class="accordion-body in">
                            <div class="accordion-inner">
                                <div class="thumbnail-container"><img src="assets/img/thumbnails/mwg.png" title="mwg"/></div>
                                <h4>Software Engineer @ <a href="http://mywebgrocer.com">MyWebGrocer</a></h4>
                                <p>Since graduating in 2011, I started working at MWG as an intern and moved into a full time position working on the Plan2Gro team.</p>
                                <div class="clear-both"></div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-group">
                    <div class="accordion-heading">
                        <a href="#History3" class="accordion-toggle" data-toggle="collapse" data-parent="#History">Skills</a>
                        <div id="History3" class="accordion-body in">
                            <div class="accordion-inner">
                                <ul>
                                    <li>PHP/C#/JS</li>
                                    <li>MVC</li>
                                    <li>backbone.js/knockout.js</li>
                                    <li>REST API</li>
                                    <li>CSS/SCSS</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="accordion-group">
                    <div class="accordion-heading">
                        <a href="#History4" class="accordion-toggle" data-toggle="collapse" data-parent="#History">Projects</a>
                        <div id="History4" class="accordion-body in">
                            <div class="accordion-inner">
                                <ul id="projectsList"></ul>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </script>
    <script type="x-jquery-tmpl" id="contact-form-content">
        <form id="contact_form" class="form-horizontal">
            <fieldset>
                <legend>Contact Me.</legend>
                <div class="control-group well">
                    <label class="control-label" for="contact_name">Name:</label>
                    <div class="controls">
                        <input type="text" class="input-xlarge" id="contact_name" placeholder="Enter your name">
                    </div>
                    <label class="control-label" for="contact_email">Email:</label>
                    <div class="controls">
                        <input type="text" class="input-xlarge" id="contact_email" placeholder="Enter your email">
                    </div>
                    <label class="control-label" for="contact_message">Message:</label>
                    <div class="controls">
                        <textarea type="textarea" class="input-xlarge" id="contact_message" rows="3" placeholder="Whats up?"></textarea>
                    </div>
                    <div class="controls">
                        <button id="contact_submit" type="submit" class="btn">Submit</button>
                        <span id="contact_status"></span>
                    </div>
                </div>
            </fieldset>
        </form>
    </script>
</head>
<body>
    <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
         chromium.org/developers/how-tos/chrome-frame-getting-started -->
    <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
    <header>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#Home" data-toggle="collapse" data-target=".nav-collapse">LP Projects</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="active"><a href="#Home" data-toggle="collapse" data-target=".nav-collapse">Home</a></li>
                            <li><a href="#Team" data-toggle="collapse" data-target=".nav-collapse">My Team</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Projects<b class="caret"></b></a>
                                <ul id="projectsDropDown" class="dropdown-menu">

                                </ul>
                            </li>
                            <li><a href="#Contact" data-toggle="collapse" data-target=".nav-collapse">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="spacer-top visible-desktop"></div>
    <div id="MainContent" role="main" class="container-fluid">
        <div class="row-fluid">
            

            <div id="contentContainer" class="span8">
                <div class="hero-unit fade in hidden-phone">
                    <h1>I'm Luke (LP)</h1>
                    <p>This is a site about me, my work, and what I'm up to!</p>
                </div>

                <!-- Start of Home Page Content-->
                
                <div id="socialMedia">
                    <h3>Social Media: </h3>
                    <div class="soialMedia-inner well">
                        <a href="http://github.com/lpaulger" title="github.com/lpaulger"><img src="assets/img/icons/github.png" ></a>
                        <a href="http://twitter.com/lmpaulger" title="twitter.com/lmpaulger"><img src="assets/img/icons/twitter-bird3-webtreatsetc.png"></a>
                        <a href="http://facebook.com/lpaulger" title="facebook.com/lpaulger"><img src="assets/img/icons/facebook-logo-webtreatsetc.png"></a>
                        <a href="http://www.linkedin.com/profile/view?id=77694687" title="linkedin.com/profile"><img src="assets/img/icons/linkedin-logo-webtreatsetc.png"></a>
                        <div class="clear-both"></div>
                    </div>
                </div>



                <div id="content" class="">


                </div>

                <!-- Hobbies and History -->

                <div id="subContent" class="row-fluid">

                </div>

                <!-- End Homepage Content -->

            </div>
            <aside id="contentListContainer" class="span4">
                <header>
                    <h2>Events</h2>
                </header>
                <div class="contentList-inner">
                    <div id="contentList" class="accordion">

                    </div>
                    <div id="bottom_fade"></div>
                <div>
                
                <!--Body content-->
            </aside>
        </div>
    </div>
    <footer>

    </footer>


    <!-- JavaScript at the bottom for fast page loading -->

    <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>
    <script src="js/libs/bootstrap.min.js"></script>
    <script src="js/libs/underscore.js"></script>
    <script src="js/libs/backbone.js"></script>
    <script src="js/libs/jquery.tmpl.min.js"></script>
    <script src="js/libs/jquery.timeago.js"></script>
    <!-- scripts concatenated and minified via build script -->
    <script src="js/libs/plugins.js"></script>

    <!-- mobile detect -->
    <script type="text/javascript">
        var isMobile = false;
        if (screen.width <= 699) {
            $('body').addClass('mobile');
            isMobile = true;
        }
    </script>

    <!-- Models -->
    <script src="js/app/models/commit.js"></script>
    <script src="js/app/models/user.js"></script>
    <script src="js/app/models/repository.js"></script>

    <script src="js/app/models/post.js"></script>

    <script>
        var Users = new UserCollection;
        Users.reset(<?php echo json_encode($users); ?>);
        var Posts = new PostCollection;
        Posts.reset(<?php echo json_encode($posts); ?>);
        var Repos = new RepoCollection;
        Repos.reset(<?php echo json_encode($projects); ?>);
        var Commits = new CommitCollection;
    </script>

    <!-- Application -->
    <script src="js/contactForm.js"></script>
    <script src="js/app/router.js"></script>
    <script src="js/index.js"></script>
    
    
    
    <script>
        $(document).ready(function(){
            $('.dropdown-toggle').dropdown();
            $(".alert").alert();
            $('.carousel').carousel();
            new App(jQuery).start();
        });
    </script>
    <!-- end scripts -->

    <!-- Asynchronous Google Analytics snippet. Change UA-XXXXX-X to be your site's ID.
         mathiasbynens.be/notes/async-analytics-snippet -->
    <script>
        var _gaq=[['_setAccount','UA-23249160-1'],['_trackPageview']];
        _gaq.push(['_trackPageview', location.pathname + location.search + location.hash]);
        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
</body>
</html>