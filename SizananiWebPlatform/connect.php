<?php

	  $db_host = '0.0.0.0';
	  $db_user = 'jazzytabs';
	  $db_pass = '';
	  $db_name = 'freshAppdb';
	
	
	// Open a connect to the database.
	// Make sure this is called on every page that needs to use the database.
        $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name, 3306);
        
        if ( mysqli_connect_errno() ) {
            printf("Connection failed: %s ", mysqli_connect_error());
            exit();
        }
        
        
        

?>
