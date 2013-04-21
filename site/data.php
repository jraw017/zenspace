<?php
session_start();
define('IN_FILE', TRUE);
include('./inc/php/functions.php');
include('./inc/php/dbconfig.php');
require_once('./inc/php/facebook.php');

// get tags from db
$get_tags = mysql_query("SELECT tag_value FROM zen_tags");
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
        	<div id="wordcloud" style="margin-left: auto; margin-right: auto; text-align: center;"></div>
            <div id="genderpie" style="margin-left: auto; margin-right: auto; text-align: center;"></div>
            <div id="tagpie" style="margin-left: auto; margin-right: auto; text-align: center;"></div>
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
    <script src="./inc/js/d3.v3.min.js"></script>
    <script src="./inc/js/d3.layout.cloud.js"></script>
    <script>
  var fill = d3.scale.category20();

  d3.layout.cloud().size([900, 400])
      .words([<?php while($tags = mysql_fetch_array($get_tags, MYSQL_ASSOC)){ echo "\"" . $tags['tag_value'] . "\","; } ?>].map(function(d) {
        return {text: d, size: 30 + Math.random() * 90};
      }))
      .rotate(function() { return ~~(Math.random() * 2) * 90; })
      .font("Impact")
      .fontSize(function(d) { return d.size; })
      .on("end", draw)
      .start();

  function draw(words) {
    d3.select("#wordcloud").append("svg")
        .attr("width", 900)
        .attr("height", 400)
      .append("g")
        .attr("transform", "translate(500,200)")
      .selectAll("text")
        .data(words)
      .enter().append("text")
        .style("font-size", function(d) { return d.size + "px"; })
        .style("font-family", "Impact")
        .style("fill", function(d, i) { return fill(i); })
        .attr("text-anchor", "middle")
        .attr("transform", function(d) {
          return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
        })
        .text(function(d) { return d.text; });
  }
		</script>
        <script>

var width = 960,
    height = 500,
    radius = Math.min(width, height) / 2;

var color = d3.scale.ordinal()
    .range(["#ec1c24", "#0066b2"]);

var arc = d3.svg.arc()
    .outerRadius(radius - 10)
    .innerRadius(0);

var pie = d3.layout.pie()
    .sort(null)
    .value(function(d) { return d.count; });

var svg = d3.select("#genderpie").append("svg")
    .attr("width", width)
    .attr("height", height)
  .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

d3.csv("getGenderData.php", function(error, data) {

  data.forEach(function(d) {
    d.count = +d.count;
  });

  var g = svg.selectAll(".arc")
      .data(pie(data))
    .enter().append("g")
      .attr("class", "arc");

  g.append("path")
      .attr("d", arc)
      .style("fill", function(d) { return color(d.data.gender); });

  g.append("text")
      .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
      .attr("dy", ".35em")
      .style("text-anchor", "middle")
      .text(function(d) { return d.data.gender + " (" + d.data.count + ")"; });

});
</script>
  </body>
</html>