<?php
    class validate {
    # ================================ #
	# ==== user validation method ==== #
	# ================================ #
    public function user() {
	    !isset($_POST['User_Name']) ? $this->missingParams['User_Name']=null:null;
		!isset($_POST['User_Surname']) ? $this->missingParams['User_Surname']=null:null;
		!isset($_POST['User_Email']) ? $this->missingParams['User_Email']=null:null;
		!isset($_POST['User_Password']) ? $this->missingParams['User_Password']=null:null;
		!isset($_POST['Type_ID']) ? $this->missingParams['Type_ID']=null:null;
		if($this->missingParams){
			$data = array(
				"success"	=> false,
				"message"	=>"Missing Parameter(s)",
				"Params"	=>$this->missingParams
			);
			throw new Exception(json_encode($data));
		}else{
			$data = array("success"	=> true);
		}
        return $data;
    }
    
    # ================================ #
    # ===== login user =============== #
    # ================================ #
    public function login(){
    	!isset($_POST['User_Email']) ? $this->missingParams['User_Email']=null:null;
    	if($this->missingParams){
    		$data = array(
    			"success"	=> false,
    			"message"	=> "Missing Pametter(s)",
    			"Params"	=> $this->missingParams
    			);
    			throw new Exception(json_encode($data));
    	}else{
    		$data = array("success"	=> true);
    	}
    	return $data;
    }
    
    # ================================ #
    # ===== insert service =========== #
    # ================================ #
    public function services_ins(){
        !isset($_POST['firstname']) ? $this->missingParams['firstname']=null:null;
        !isset($_POST['surname']) ? $this->missingParams['surname']=null:null;
    	!isset($_POST['description']) ? $this->missingParams['description']=null:null;
    	!isset($_POST['location']) ? $this->missingParams['location']=null:null;
    	!isset($_POST['type']) ? $this->missingParams['type']=null:null;
    	if($this->missingParams){
    		$data = array(
    			"success"	=> false,
    			"message"	=> "Missing Pametter(s)",
    			"Params"	=> $this->missingParams
    			);
    			throw new Exception(json_encode($data));
    	}else{
    		$data = array("success"	=> true);
    	}
    	return $data;
    }
    
    # ================================ #
    # ===== select service =========== #
    # ================================ #
    public function services_sel(){
    	!isset($_POST['type']) ? $this->missingParams['type']=null:null;
    	if($this->missingParams){
    		$data = array(
    			"success"	=> false,
    			"message"	=> "Missing Pametter(s)",
    			"Params"	=> $this->missingParams
    			);
    			throw new Exception(json_encode($data));
    	}else{
    		$data = array("success"	=> true);
    	}
    	return $data;
    }
    
    
    # ================================ #
    # ===== select category ========== #
    # ================================ #
    
    

    public function category_sel(){
    	if($this->missingParams){
    		$data = array(
    			"success"	=> false,
    			"message"	=> "Missing Pametter(s)",
    			"Params"	=> $this->missingParams
    			);
    			throw new Exception(json_encode($data));
    	}else{
    		$data = array("success"	=> true);
    	}
    	return $data;
    }
    
    # ================================ #
    # ===== insert category ========== #
    # ================================ #

    public function category_ins(){
    	!isset($_POST['cat_types']) ? $this->missingParams['cat_types']=null:null;
    	!isset($_POST['cat_names']) ? $this->missingParams['cat_names']=null:null;
    	!isset($_POST['cat_locations']) ? $this->missingParams['cat_locations']=null:null;
    	!isset($_POST['description']) ? $this->missingParams['description']=null:null;
    	if($this->missingParams){
    		$data = array(
    			"success"	=> false,
    			"message"	=> "Missing Pametter(s)",
    			"Params"	=> $this->missingParams
    			);
    			throw new Exception(json_encode($data));
    	}else{
    		$data = array("success"	=> true);
    	}
    	return $data;
    }
    
    }
?>