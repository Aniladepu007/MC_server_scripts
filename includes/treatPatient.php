<?php
require_once 'DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {
      if( isset($_POST['Pid']) and isset($_POST['password']) ){
            $db = new DbOperations();

            if($db->patientLogin( $_POST['Pid'], $_POST['password'] )) {
                  $user = $db->getPatientbyPid($_POST['Pid']);
                  $response['error'] = false;
                  $response['Pid'] = $user['Pid'];
                  $response['email'] = $user['email'];
                  $response['Password'] = $user['Password'];
                  $response['Name'] = $user['Name'];
                  $response['Sex'] = $user['Sex'];
                  $response['DOB'] = $user['DOB'];
                  $response['Mob_no'] = $user['Mob_no'];
                  $response['Hostel'] = $user['Hostel'];
                  $response['Roomno'] = $user['Roomno'];
                  $response['Bllodgrp'] = $user['Bloodgrp'];
                  $response['weight'] = $user['weight'];
                  $response['lati'] = $user['lati'];
                  $response['longi'] = $user['longi'];
            }else {
                  $response['error'] = true;
                  $response['message'] = 'Invalid username or password!';
            }

      }else {
            $response['error'] = true;
            $response['message'] = 'Required fields are missing!';
      }
}


echo json_encode($response);
