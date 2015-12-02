<?php
//    site: www.wowza.io
//    author: Carlos Camacho
//    email: carloscamachoucv@gmail.com
//    created: November 2015

include('config.php');

?>
<!--
	http://www.startutorial.com/articles/view/php-crud-tutorial-part-3
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
    		<div class="row">
    			<h3>Wowza.io CRUD Grid</h3>
    		</div>
			<div class="row">
				<p>
					<a href="create.php" class="btn btn-success">Create</a>
					<a href="destroy.php" class="btn btn-danger">Remove all</a>

				</p>
				
				<table class="table table-striped table-bordered">
		              <thead>
		                <tr>
		                  <th>Id</th>
		                  <th>Application name</th>
		                  <th>Player URL</th>
		                  <th>Streamer URL</th>
		                  <th>Secured</th>
		                  <th>Action</th>
		                </tr>
		              </thead>
		              <tbody>
		              <?php 
					   include 'database.php';
					   $pdo = Database::connect();
					   $pdo->exec("CREATE TABLE IF NOT EXISTS streams (
					        id INTEGER PRIMARY KEY, 
                    		name TEXT,
                    		isSecured BOOLEAN)"
					   );
 						

					   $sql = 'SELECT * FROM streams ORDER BY id DESC';
	 				   foreach ($pdo->query($sql) as $row) {
						   		echo '<tr>';
							   	echo '<td>'. $row['id'] . '</td>';
							   	echo '<td>'. $row['name'] . '</td>';
							   	$secured = 0;
							   	if ($row['isSecured']){$secured =1;}
							   	echo '<td><a href="player/index.php?appName='.$row['name'].'&secured='.$secured.'">View link </a>   </td>';
							   	echo '<td><a href="webstreamer/encoder.html?appName='.$row['name'] .'&serverName='.STREAMING_SERVER_IP.'&serverPort='.STREAMING_SERVER_DELIVERY_PORT.'">Stream link </a></td>';


							   	echo '<td>'. $secured . '</td>';
							   	echo '<td width=250>';
							   	echo '<a class="btn" href="read.php?id='.$row['id'].'">Read</a>';
							   	echo '&nbsp;';
							   	echo '<a class="btn btn-success" href="update.php?id='.$row['id'].'">Update</a>';
							   	echo '&nbsp;';
							   	echo '<a class="btn btn-danger" href="delete.php?id='.$row['id'].'">Delete</a>';
							   	echo '</td>';
							   	echo '</tr>';
					   }
					   Database::disconnect();
					  ?>
				      </tbody>
	            </table>
    	</div>
    </div> <!-- /container -->
  </body>
</html>
