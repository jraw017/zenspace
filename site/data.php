<?php
session_start();
define('IN_FILE', TRUE);
include('./inc/php/functions.php');
include('./inc/php/dbconfig.php');
require_once('./inc/php/facebook.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>ZenSpace » View Data</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="./inc/css/bootstrap.css" rel="stylesheet">
    <link href="./inc/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="./inc/css/style.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="./inc/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="./inc/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="./inc/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="./inc/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="./inc/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="./inc/ico/favicon.ico">
    
    <!-- Le jQuery -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
  </head>

  <body>
		
      <div class="container">
      <div class="masthead">
        <h3 class="muted"><a href="./landing.php"><img src="inc/img/ZenSpace_logo.png" alt="ZenSpace" width="150" height="150" border="0"></a></h3>
      </div> 
        
        <div class="navbar">
          <div class="navbar-inner">
            <div class="container">
            	<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                </button>
                <div class="nav-collapse collapse">
              		<ul class="nav">
               			<li class="">
                        	<a href="./landing.php">ZenSpace</a>
                        </li>
                		<li class="">
                        	<a href="./submit.php">Submit Zen</a>
                        </li>
                		<li class="active">
                        	<a href="./data.php">View Zen</a>
                        </li>
               		 	<li class="">
                        	<a href="./community.php">Community</a>
                        </li>
              		</ul>
                </div>
            </div>
          </div>
        </div><!-- /.navbar -->
      </div>
      
      <div class="container">
      
      <div class="well">
      
      <?php if($_SESSION['logged_in'] == "Yessir"): ?>
     	<div class="span12">
        	<p><img class="img-polaroid" src="<?php echo "https://graph.facebook.com/".$_SESSION['fb_id']."/picture"; ?>" width="50" height="50" border="none"> Welcome, <a href="http://www.facebook.com/<?php echo $_SESSION['fb_id']; ?>"><?php echo $_SESSION['name']; ?></a> (<a href="?act=logout">logout</a>)</p>
      	</div>
       <?php endif; ?>
  
      <!-- Jumbotron -->
      <div class="jumbotron">
        <h2>Zen Garden</h2>
        <p>Here is a visual representation of the zen collected from participants.</p>
      </div>
      <hr>
      <!-- Example row of columns -->
      <div class="row-fluid">
        <div class="span12">
        	<p>Data</p>
        </div>
      </div>
      
      </div> <!-- </container> -->

      <div class="footer">
      	<a href="http://www.spaceappschallenge.org" target="_blank"><img src="inc/img/nasa_logo.png" style="border-width:0" alt="NASA"></a>
        <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/deed.en_GB" target="_blank">
        <img alt="Creative Commons Licence" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" /></a>
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./inc/js/bootstrap.js"></script>
    <script src="./inc/js/textext.js"></script>
    <script type="text/javascript">
    $('#emotions')
        .textext({
            plugins : 'tags autocomplete'
        })
        .bind('getSuggestions', function(e, data)
        {
            var list = [
                    'Basic',
                    'Closure',
                    'Cobol',
                    'Delphi',
                    'Erlang',
                    'Fortran',
                    'Go',
                    'Groovy',
                    'Haskel',
                    'Java',
                    'JavaScript',
                    'OCAML',
                    'PHP',
                    'Perl',
                    'Python',
                    'Ruby',
                    'Scala'
                ],
                textext = $(e.target).textext()[0],
                query = (data ? data.query : '') || ''
                ;

            $(this).trigger(
                'setSuggestions',
                { result : textext.itemManager().filter(list, query) }
            );
        })
        ;
	</script>
  </body>
</html>