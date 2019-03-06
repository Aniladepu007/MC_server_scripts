<?php
require_once 'DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {
      if( isset($_POST['Username']) and isset($_POST['Password']) ){
            $db = new DbOperations();

            if( $db->docLogin( $_POST['Username'], $_POST['Password']) ) {
                  $user = $db->getDocByUsername($_POST['Username']);
                  $response['error'] = false;
                  $response['Username'] = $user['Username'];
                  $response['email'] = $user['email'];
                  $response['Full Name'] = $user['Full Name'];
                  $response['Password'] = $user['Password'];
                  $response['specialization'] = $user['specialization'];
                  $response['shift_type'] = $user['shift_type'];
                  $response['Mob_no'] = $user['Mob_no'];
                  $response['DOB'] = $user['DOB'];
//                  $response['lati'] = $user['lati'];
//                  $response['longi'] = $user['longi'];
            }else {
                  $response['error'] = true;
                  $response['message'] = 'Invalid Username or Password!';
            }

      } else {
            $response['error'] = true;
            $response['message'] = 'Required fields are missing!';
      }
}


echo json_encode($response);
