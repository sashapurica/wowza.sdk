<?php 
//    site: www.wowza.io
//    author: Carlos Camacho
//    email: carloscamachoucv@gmail.com
//    created: November 2015


	require 'database.php';
	require(dirname(dirname(dirname(__FILE__))).'/libs/wowza.php');

	$wowzaServerIP = "127.0.0.1";
	$wow = new Wowza($wowzaServerIP.":8087");

	$id = 0;
	
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	
	if ( !empty($_POST)) {
		// keep track post values
		$id = $_POST['id'];
		
		// delete data
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "SELECT name FROM streams where id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));
		$data = $q->fetch(PDO::FETCH_ASSOC);		

		$sql = "DELETE FROM streams  WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($id));

		$wow->deleteApplication($data['name']);

		Database::disconnect();
		header("Location: index.php");

	} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
    
    			<div class="span10 offset1">
    				<div class="row">
		    			<h3>Delete an application</h3>
		    		</div>
		    		
	    			<form class="form-horizontal" action="delete.php" method="post">
	    			  <input type="hidden" name="id" value="<?php echo $id;?>"/>
					  <p class="alert alert-error">Are you sure to delete?</p>
					  <div class="form-actions">
						  <button type="submit" class="btn btn-danger">Yes</button>
						  <a class="btn" href="index.php">No</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>