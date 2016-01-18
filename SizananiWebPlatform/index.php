<?php
// A simple web site in Cloud9 that runs through Apache
// Press the 'Run' button on the top to start the web server,
// then click the URL that is emitted to the Output tab of the console

require 'connect.php';
require 'validate.php';
require 'functions.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');

function generalExceptionHandler($e) {
	$info = json_decode($e->getMessage(),true);
    $data = array('status' => 'error');
    isset($info['imgException'])?$data['imgException'] = $info['imgException']:null;
    isset($info['success'])?$data['success'] = $info['success']:null;
    isset($info['message'])?$data['message'] = $info['message']:null;
	isset($info['Params'])?$data['Params'] = $info['Params']:null;

//giving a 200 status code when the call is for an image
    if($data['imgException']){
        http_response_code(200);
    }else{
        http_response_code(400); 
    }
    header("Content-Type: application/json");
    echo json_encode($data);
}
set_exception_handler('generalExceptionHandler');



if(isset($_POST['method'])) {
    switch($_POST['method']) {
        case "user_INS":
            $validate 	= new validate();
            $data		= $validate->user();
            $function 	= new functions();
            $data		= $function->user_INS($mysqli);
            break;
        case "login_SEL":
            $validate 	= new validate();
            $data		= $validate->login();
            $function 	= new functions();
            $data		= $function->login_SEL($mysqli);
            break;
        case "service_SEL":
            $validate 	= new validate();
            $data		= $validate->services_sel();
            $function 	= new functions();
            $data		= $function->service_SEL($mysqli);
            break; 
        case "service_INS":
            $validate 	= new validate();
            $data		= $validate->services_ins();
            $function 	= new functions();
            $data		= $function->service_INS($mysqli);
            break;
        case "category_SEL":
            $validate 	= new validate();
            $data		= $validate->category_sel();
            $function 	= new functions();
            $data		= $function->category_SEL($mysqli);
            break;
         case "category_INS":
            $validate 	= new validate();
            $data		= $validate->category_ins();
            $function 	= new functions();
            $data		= $function->category_INS($mysqli);
            break;
        default:
            break;
    }
}else {
    http_response_code(200);

    $data['status'] = "error";
    $data['success'] = false;
    $data['message'] = "No method specified";
}



// let's send the data back
header("Content-Type: application/json");
echo json_encode($data);

?>