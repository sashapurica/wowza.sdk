<?php 
/**
 * Copyright by wowza.io at http://www.wowza.io
 *
 * This file is part of wowza.io SDK
 * 
 * Some open source application is free software: you can redistribute 
 * it and/or modify it under the terms of the GNU General Public 
 * License as published by the Free Software Foundation, either 
 * version 3 of the License, or (at your option) any later version.
 * 
 * Some open source application is distributed in the hope that it will 
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @license GPL-3.0+ <http://spdx.org/licenses/GPL-3.0+>
 */

	require("database.php");
	require(dirname(dirname(dirname(__FILE__))).'/libs/wowza.php');
    require("config.php");

	$wowzaServerIP = STREAMING_SERVER_IP;
	$wowzaServerPort = STREAMING_SERVER_PORT;

	$wow = new Wowza($wowzaServerIP.":".$wowzaServerPort);

	if ( !empty($_POST)) {
		// keep track validation errors
		$nameError = null;

		// keep track post values
	  	$name = $_POST['name'];

		// validate input
		$valid = true;
		if (empty($name)) {
			if (true){
			$nameError = 'Please enter an Application name';
			$valid = false;
		}
		}

		if (isset($_POST['isSecured'])){
			$isSecured = true;
		}else{
			$isSecured = false;		
		}

		//cREATE THE wowza app
		if ($isSecured) {
			$output = $wow->createSecuredApplication($name);
		}else{
			$wow->createNonSecuredApplication($name);
		}


		// insert data
		if ($valid) {
			$pdo = Database::connect();
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO streams (name,isSecured) values(?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($name,$isSecured));
			Database::disconnect();
			header("Location: index.php");
		}
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
		    			<h3>Create a stream</h3>
		    		</div>
    		
	    			<form class="form-horizontal" action="create.php" method="post">

					  <div class="control-group <?php echo !empty($nameError)?'error':'';?>">
					    <label class="control-label">Name</label>
					    <div class="controls">
					      	<input name="name" type="text"  placeholder="Name" value="<?php echo !empty($name)?$name:'';?>">
					      	<?php if (!empty($nameError)): ?>
					      		<span class="help-inline"><?php echo $nameError;?></span>
					      	<?php endif; ?>
					    </div>
					  </div>

					  <div class="control-group <?php echo !empty($isSecuredError)?'error':'';?>">
					    <label class="control-label">Is secured?</label>
					    <div class="controls">
					      	<input name="isSecured" type="checkbox" placeholder="Is secureeed" value="<?php echo !empty($isSecured)?$isSecured:'';?>" checked>
					      	<?php if (!empty($isSecuredError)): ?>
					      		<span class="help-inline"><?php echo $isSecuredError;?></span>
					      	<?php endif;?>
					    </div>
					  </div>


					  <div class="form-actions">
						  <button type="submit" class="btn btn-success">Create</button>
						  <a class="btn" href="index.php">Back</a>
						</div>
					</form>
				</div>
				
    </div> <!-- /container -->
  </body>
</html>
