<?php
require_once 'DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {
      if( isset($_POST['Username']) and isset($_POST['email']) and isset($_POST['FullName']) and isset($_POST['Password']) and isset($_POST['ConfirmPass'])
      and isset($_POST['specialization']) and isset($_POST['shift_type']) and isset($_POST['Mob_no']) and isset($_POST['Sex']) and isset($_POST['DOB'])
      ) {
            $db = new DbOperations();
            $result = $db->createDoc(
                        $_POST['Username'], $_POST['email'], $_POST['FullName'], $_POST['Password'], $_POST['ConfirmPass'],
                        $_POST['specialization'], $_POST['shift_type'], $_POST['Mob_no'], $_POST['Sex'], $_POST['DOB']
                      );

            if($result == 0) {
                  $response['error'] = true;
                  $response['message'] = "Email already registered! goto LOGIN";
            }
            elseif($result == 1) {
                  $response['error'] = false;
                  $response['message'] = "Doctor registered successfully";
            }
            elseif($result == 2) {
                  $response['error'] = true;
                  $response['message'] = "Some error occurred please try again";
            }
            elseif($result == 3) {
                  $response['error'] = true;
                  $response['message'] = "Passwords can't be empty";
            }
            elseif($result == 4) {
                  $response['error'] = true;
                  $response['message'] = "Confirm Password didn't match!";
            }
            elseif($result == 5) {
                  $response['error'] = true;
                  $response['message'] = "MedicalID already taken. Please choose another!";
            }
            elseif($result == 6) {
                  $response['error'] = true;
                  $response['message'] = "Email can't be empty!";
            }
            elseif($result == 7) {
                  $response['error'] = true;
                  $response['message'] = "Username can't be empty!";
            }
            elseif($result == 8) {
                  $response['error'] = true;
                  $response['message'] = "shift_type can't be empty!";
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
