<?php

require_once 'DbOperations.php';

$response = array();



if($_SERVER['REQUEST_METHOD']=='POST') {
	if( isset($_POST['Pid']) and isset($_POST['Hospital_name']) and isset($_POST['Username']) and isset($_POST['treatment_date']) and isset($_POST['slot']) and isset($_POST['symptoms']) and
            isset($_POST['diagnosis']) and isset($_POST['prescription']) and isset($_POST['remarks'])
      ){
            //operate data further
		$db = new DbOperations();
            $result = $db->treatNewPatient(
                        $_POST['Pid'], $_POST['Hospital_name'], $_POST['Username'], $_POST['treatment_date'], $_POST['slot'], $_POST['symptoms'], $_POST['diagnosis'],
                        $_POST['prescription'], $_POST['remarks']
                        );

      		if($result == 1) {
				$response['error'] = false;
				$response['message'] = "Submitted successfully";
			}
			elseif($result == 0) {
				$response['error'] = true;
				$response['message'] = "Some error occurred please try again";
                  }
			elseif($result == 2) {
				$response['error'] = true;
				$response['message'] = "Already Submitted";
                  }
	}
	else
		{
			$response['error'] = true;
			$response['message'] = "Required fields are missing";
		}
}
else {
	$response['error'] = true;
	$response['message'] = "Invalid Request";
}

echo json_encode($response);
