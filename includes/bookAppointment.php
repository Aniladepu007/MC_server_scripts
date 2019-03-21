<?php

require_once 'DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
      if( isset($_POST['Pid']) and isset($_POST['Username']) and
            isset($_POST['Date']) and isset($_POST['Slot'])
      ) {
            $db = new DbOperations();
            $result = $db->bookAppointment(
                        $_POST['Pid'], $_POST['Username'], $_POST['Date'], $_POST['Slot']
                      );
            if($result == 0) {
                  $response['error'] = true;
                  $response['message'] = "Some error occurred please try again";
            }
            elseif($result == 1) {
                  $response['error'] = false;
                  $response['message'] = "Appointment Booked Successfully";
            }
            elseif($result == 2) {
                  $response['error'] = true;
                  $response['message'] = "Date and/or slot can't be empty";
            }
            elseif($result == 3) {
                  $response['error'] = true;
                  $response['message'] = "Slot not Empty -- Already Booked";
            }
      }
      else {
            $response['error'] = true;
            $response['message'] = "Required fields missing";
      }
}
else {
      $response['error'] = true;
      $response['message'] = "Invalid Request";
}

echo json_encode($response);




?>
