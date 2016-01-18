<?php
    class functions {
    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password){

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }
    
    # =============================== #
    # ======= insert function======== #
    # =============================== #
    
    public function user_INS($mysqli) {
	    $resultArray = array();

		if (!($stmt = $mysqli->prepare("CALL user_INS(?,?,?,?,?,?)"))) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error
			);
			throw new Exception(json_encode($data));
		}
		
		$password = $_POST['User_Password'];
		$hash = $this->hashSSHA($password);
      
		$User_Name = $_POST['User_Name'];
		$User_Surname = $_POST['User_Surname'];
		$User_Email = $_POST['User_Email'];
		$User_Encrypted_password = $hash["encrypted"]; // encrypted password;
		$salt = $hash["salt"]; // salt
		$Type_ID = (int)$_POST['Type_ID'];
		

		if (!$stmt->bind_param("ssssss", $User_Name,$User_Surname,$User_Email,$User_Encrypted_password,$Type_ID,$salt)) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error
			);
			throw new Exception(json_encode($data));
		}

		if (!$stmt->execute()) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Execute failed: (" . $stmt->errno . ") " . $stmt->error
			);
			throw new Exception(json_encode($data));
		}


		do {

		    $user_ID = NULL;
		    $user_Name = NULL;
		    $user_Surname = NULL;
		    $user_Email = NULL;
		    $user_Encrypted_password = NULL;
		    $Salt = NULL;
		    $type_ID = NULL;
		    $user_CreatedDateTime = NULL;

		    if (!$stmt->bind_result($user_ID,$user_Name,$user_Surname,$user_Email,$user_Encrypted_password,$Salt,$type_ID,$user_CreatedDateTime)) {
		        $data = array(
					"success"	=> false,
					"message"	=> "Binding results failed: (" . $stmt->errno . ") " . $stmt->error
				);
				throw new Exception(json_encode($data));
		    }

		    while ($stmt->fetch()) {
		        $user = array(
					'User_ID'	=> $user_ID,
					'User_Name'	=>  $user_Name,
					'User_Surname'	=>  $user_Surname,
					'User_Email'	=>  $user_Email,
					'Type_ID'	=>  $type_ID,
					'User_CreatedDateTime' => $user_CreatedDateTime
				);
				array_push( $resultArray, $user);
		    }
		    return $data = array(
			    "success"	=>true,
			    "message" 	=>"User inserted",
			    "user"		=>$user
		    );
		} while ($stmt->more_results() && $stmt->next_result());
    }
    
    # =============================== #
    # ======= login function========= #
    # =============================== #
    
    public function login_SEL($mysqli) {
	    $resultArray = array();

		if (!($stmt = $mysqli->prepare("CALL login_SEL(?)"))) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error
			);
			throw new Exception(json_encode($data));
		}
		$User_Email = $_POST['User_Email'];
		

		if (!$stmt->bind_param("s",$User_Email)) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error
			);
			throw new Exception(json_encode($data));
		}

		if (!$stmt->execute()) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Execute failed: (" . $stmt->errno . ") " . $stmt->error
			);
			throw new Exception(json_encode($data));
		}


		do {

		   $user_ID = NULL;
		    $user_Name = NULL;
		    $user_Surname = NULL;
		    $user_Email = NULL;
		    $user_Encrypted_password = NULL;
		    $Salt = NULL;
		    $type_ID = NULL;
		    $user_CreatedDateTime = NULL;

		    if (!$stmt->bind_result($user_ID,$user_Name,$user_Surname,$user_Email,$user_Encrypted_password,$Salt,$type_ID,$user_CreatedDateTime)) {
		        $data = array(
					"success"	=> false,
					"message"	=> "Binding results failed: (" . $stmt->errno . ") " . $stmt->error
				);
				throw new Exception(json_encode($data));
		    }

		    while ($stmt->fetch()) {
		        $user = array(
					'User_ID'	=> $user_ID,
					'User_Name'	=>  $user_Name,
					'User_Surname'	=>  $user_Surname,
					'User_Email'	=>  $user_Email,
					'Type_ID'	=>  $type_ID,
					'User_CreatedDateTime' => $user_CreatedDateTime
				);
				array_push( $resultArray, $user);
				
				$hash=$this->checkhashSSHA($Salt, $_POST['Password']);
				if($user_Encrypted_password == $hash){
					return $data = array(
					"success"	=>true,
			    	"message" 	=>"Login successfull",
			    	"user"		=>$resultArray
		    		);
				}else{
					return $data = array(
			    	"success"	=>false,
			    	"message" 	=>"Incorrect username or password"
		    		);
				}
				
		    }
		    	if($user==null){
					return $data = array(
			    	"success"	=>false,
			    	"message" 	=>"User not registered"
		    		);
				}
		    
		} while ($stmt->more_results() && $stmt->next_result());
		
		
    }
    
    # =============================== #
    # ======= service ins function=== #
    # =============================== #
   
    
    public function service_INS($mysqli) {
	    $resultArray = array();

		if (!($stmt = $mysqli->prepare("CALL service_INS(?,?,?,?,?)"))) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error
			);
			throw new Exception(json_encode($data));
		}
	
      	$firstname		= $_POST['firstname'];
      	$surname		= $_POST['surname'];
		$description	= $_POST['description'];
		$location		= $_POST['location'];
		$type			= $_POST['type'];

		if (!$stmt->bind_param("sssss",$firstname,$surname, $description,$location,$type)) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error
			);
			throw new Exception(json_encode($data));
		}

		if (!$stmt->execute()) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Execute failed: (" . $stmt->errno . ") " . $stmt->error
			);
			throw new Exception(json_encode($data));
		}


		do {

		    $service_ID = NULL;
		    $firstname	= NULL;
		    $surname	= NULL;
		    $description = NULL;
		    $location = NULL;
		    $type = NULL;
		    if (!$stmt->bind_result($service_ID,$firstname,$surname,$description,$location,$type)) {
		        $data = array(
					"success"	=> false,
					"message"	=> "Binding results failed: (" . $stmt->errno . ") " . $stmt->error
				);
				throw new Exception(json_encode($data));
		    }

		    while ($stmt->fetch()) {
		        $user = array(
					'service_ID'	=> $service_ID,
					'firstname'	=>  $firstname,
					'surname'	=>  $surname,
					'description'	=>  $description,
					'location'	=>  $location,
					'type'		=> $type
				);
				array_push( $resultArray, $user);
		    }
		    return $data = array(
			    "success"	=>true,
			    "message" 	=>"service_done",
			   	"user"		=>$resultArray
		    );
		} while ($stmt->more_results() && $stmt->next_result());
    }
    
    # =============================== #
	# ======== category ins ========= #
	# =============================== #
    
    public function category_INS($mysqli) {
	    $resultArray = array();

		if (!($stmt = $mysqli->prepare("CALL category_INS(?,?,?,?)"))) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error
			);
			throw new Exception(json_encode($data));
		}
	
      	$cat_types			= $_POST['cat_types'];
      	$cat_names			= $_POST['cat_names'];
		$cat_locations		= $_POST['cat_locations'];
		$cat_description	= $_POST['cat_description'];
		

		if (!$stmt->bind_param("ssss",$cat_types,$cat_names, $cat_locations,$cat_description)) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error
			);
			throw new Exception(json_encode($data));
		}

		if (!$stmt->execute()) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Execute failed: (" . $stmt->errno . ") " . $stmt->error
			);
			throw new Exception(json_encode($data));
		}


		do {

		    $category_ID 		= NULL;
		    $cat_types			= NULL;
		    $cat_names			= NULL;
		    $cat_locations 		= NULL;
		    $cat_description 	= NULL;
		    if (!$stmt->bind_result($category_ID,$cat_types,$cat_names, $cat_locations,$cat_description)) {
		        $data = array(
					"success"	=> false,
					"message"	=> "Binding results failed: (" . $stmt->errno . ") " . $stmt->error
				);
				throw new Exception(json_encode($data));
		    }

		    while ($stmt->fetch()) {
		        $user = array(
					'category_ID'		=>  $category_ID,
					'cat_types'			=>  $cat_types,
					'cat_names'			=>  $cat_names,
					'cat_locations'		=>  $cat_locations,
					'cat_description'	=>  $cat_description
				);
				array_push( $resultArray, $user);
		    }
		    return $data = array(
			    "success"	=>true,
			    "message" 	=>"service_done",
			   	"user"		=>$resultArray
		    );
		} while ($stmt->more_results() && $stmt->next_result());
    }
    
    # =============================== #
	# ======== category sel ========= #
	# =============================== #
	
	public function category_SEL($mysqli) {
	    $resultArray = array();

		if (!($stmt = $mysqli->prepare("CALL category_SEL()"))) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error
			);
			throw new Exception(json_encode($data));
		}

		if (!$stmt->execute()) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Execute failed: (" . $stmt->errno . ") " . $stmt->error
			);
			throw new Exception(json_encode($data));
		}
		do {

		    $category_ID = NULL;
		    $cat_type = NULL;
		    $cat_name = NULL;
		    $cat_location = NULL;
		    $description = NULL;
		
		    if (!$stmt->bind_result($category_ID,$cat_type,$cat_name,$cat_location,$description)) {
		        $data = array(
					"success"	=> false,
					"message"	=> "Binding results failed: (" . $stmt->errno . ") " . $stmt->error
				);
				throw new Exception(json_encode($data));
		    }

		    while ($stmt->fetch()) {
		 //   $sql = "SELECT count(type)FROM freshAppdb.tbl_Services WHERE TYPE =2";
			// if($mysqli->query($sql) === TRUE){
			// 	echo "Success#########";
			// }else{
			// 	echo "Never ####";
			// }
		        $user = array(
					'category_ID'	=>  $category_ID,
					'cat_type'	=>  $cat_type,
					'cat_name'	=>  $cat_name,
					'cat_location'	=>  $cat_location,
					'description' => $description
					// 'count' =>	
					
				);
				array_push( $resultArray, $user);
		    }
		    return $data = array(
			    "success"	=>true,
			    "message" 	=>"Category SEl",
			    "user"		=>$resultArray
		    );
		} while ($stmt->more_results() && $stmt->next_result());
    }
    
    # =============================== #
    # ======= service select ======== #
    # =============================== #
    
    public function service_SEL($mysqli) {
	    $resultArray = array();

		if (!($stmt = $mysqli->prepare("CALL service_SEL(?)"))) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error
			);
			throw new Exception(json_encode($data));
		}
		
		$type = $_POST['type'];
		if (!$stmt->bind_param("s", $type)) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error
			);
			throw new Exception(json_encode($data));
		}

		if (!$stmt->execute()) {
		    $data = array(
				"success"	=> false,
				"message"	=> "Execute failed: (" . $stmt->errno . ") " . $stmt->error
			);
			throw new Exception(json_encode($data));
		}
		do {
		    $service_ID = NULL;
		    $firstname = NULL;
		    $surname = NULL;
		    $description = NULL;
		    $location = NULL;
		    $type = NULL;
		    if (!$stmt->bind_result($service_ID,$firstname,$surname,$description,$location,$type)) {
		        $data = array(
					"success"	=> false,
					"message"	=> "Binding results failed: (" . $stmt->errno . ") " . $stmt->error
				);
				throw new Exception(json_encode($data));
		    }
		    while ($stmt->fetch()) {
		        $user = array(
					'service_ID'	=> $service_ID,
					'firstname'	=>  $firstname,
					'surname'	=>  $surname,
					'description'	=>  $description,
					'location'	=>  $location,
					'type' => $type
				);
				array_push( $resultArray, $user);
		    }
		    return $data = array(
			    "success"	=>true,
			    "message" 	=>"Service Selected",
			    "user"		=>$resultArray
		    );
		} while ($stmt->more_results() && $stmt->next_result());
    }
    
    }
?>