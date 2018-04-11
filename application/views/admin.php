
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Mac Textbook Exchange</title>

	<link rel="stylesheet" href="http://www.mactextbooks.ca/bootflat/css/bootflat.min.css">
	<link rel="stylesheet" href="http://www.mactextbooks.ca/bootflat/css/bootflat.css">
	
	<meta name="description" content="Buy and Sell used textbooks from other McMaster students!">

	<!-- Facebook Data -->
	<meta property="og:title" content="McMaster Textbook Exchange">
	<meta property="og:description" content="Buy and Sell used textbooks from other McMaster students!">
	<meta property="og:type" content="Used Textbook search engine"> 
	<meta property="og:url" content="http://www.mactextbooks.ca/">
	<meta property="og:image" content=""> <!-- Literal image string -->
	<meta property="og:site_name" content="MacTextbooks">

	<!-- Twitter -->
	<meta name="twitter:card" content="summary">
	<meta name="twitter:url" content="http://www.mactextbooks.ca/">
	<meta name="twitter:title" content="McMaster Textbook Exchange">
	<meta name="twitter:description" content="Buy and Sell used textbooks from other McMaster students!">
	<meta name="twitter:image" content=""> <!-- Literal image string -->
</head>
<body>

<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	  <div class="container">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <a class="navbar-brand" href="http://www.mactextbooks.ca/">Mac Textbook Exchange</a>
	    </div>
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav navbar-left">
	        <li><a href="http://www.mactextbooks.ca/post">Post my Textbook</a></li>
	        <li><a href="http://www.mactextbooks.ca/">New Search</a></li>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div>
</div>
    		<div class="container" id="body">
    			<div class="row">
    				<div class="col-md-6">
						<h1>Admin Panel</h1>
					</div>
					<div class="col-md-6">
						<button class="btn btn-primary pull-right" style="margin-top:25px">Logout</h1>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<h4>Delete Listing</h4>
						<label for="listingid">Listing ID</label>
						<input type="text" class="form-control" placeholder="Enter listing ID to delete" id="listingid" name="listingid">
						<button type="submit" class="btn btn-danger" style="margin-top:10px;" onclick="javascript:alert('Listing has been deleted.')" >Delete Listing</button>
						<hr>
						<h4>Activate Listing</h4>
						<label for="listingid">Listing ID</label>
						<input type="text" class="form-control" placeholder="Enter listing ID to activate" id="listingid" name="listingid">
						<button type="submit" class="btn btn-primary" style="margin-top:10px;" onclick="javascript:alert('Listing has been activated.')">Activate Listing</button>
						<hr>
						<h4>Clear All Listings</h4>
						<p>Press the button below to remove all listings for the next term.</p>
						<button type="submit" class="btn btn-danger" style="margin-top:0px;" onclick="javascript:confirm('Are you sure you want to delete all listings?')" >Clear Listings Database</button>
					</div>
					<div class="col-md-6">
						<h4>Site Stats</h4>
						<p><b>Number of Listings:</b> 120</p>
						<p><b>Number of Active Listings:</b> 112</p>
						<p><b>Unique Visitors:</b> 180</p>
						<hr style="margin-top:42px">
						<h4>Upload New Courses</h4>
						<p>Keep current database of courses and add new ones. <em>csv files only.</em></p>
						<input type="file" id="courses" accept=".csv">
						<button type="submit" class="btn btn-primary" style="margin-top:10px;">Upload New Courses</button>
						<hr>
						<h4>Overwrite Courses</h4>
						<p>Overwrite all current courses with a new list of courses. <em>csv files only.</em></p>
						<input type="file" id="courses" accept=".csv">
						<button type="submit" class="btn btn-primary" style="margin-top:10px;">Upload New Courses</button>
					</div>
				</div>
			</div>

    	<!-- Bootstrap -->
    <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<!-- Bootflat's JS files.-->
	<script src="http://www.mactextbooks.ca/bootflat/js/icheck.min.js"></script>
	<script src="http://www.mactextbooks.ca/bootflat/js/jquery.fs.selecter.min.js"></script>
	<script src="http://www.mactextbooks.ca/bootflat/js/jquery.fs.stepper.min.js"></script>
	
</body>
</html>