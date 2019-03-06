<?php

require_once 'DbOperations.php';

$response = array();



if($_SERVER['REQUEST_METHOD']=='POST') {
		if( isset($_POST['Pid']) and isset($_POST['email']) and isset($_POST['Password']) and isset($_POST['ConfirmPass']) and isset($_POST['Name']) and isset($_POST['Sex']) and
	            isset($_POST['DOB']) and isset($_POST['Mob_no']) and isset($_POST['Hostel']) and isset($_POST['Roomno']) and
			isset($_POST['Bloodgrp']) and	isset($_POST['weight'] ) and isset($_POST['lati']) and isset($_POST['longi'])
	      ){
                  //operate data further
			$db = new DbOperations();
                  $result = $db->createUser(
                              $_POST['Pid'], $_POST['email'], $_POST['Password'], $_POST['ConfirmPass'], $_POST['Name'], $_POST['Sex'], $_POST['DOB'],
                              $_POST['Mob_no'], $_POST['Hostel'], $_POST['Roomno'], $_POST['Bloodgrp'], $_POST['weight'], $_POST['lati'],
                              $_POST['longi']
                              );

            		if($result == 1) {
					$response['error'] = false;
					$response['message'] = "User registered successfully";
				}
				elseif($result ==2) {
					$response['error'] = true;
					$response['message'] = "Some error occurred please try again";
					//mysqli_connect_error();
				}elseif($result == 0) {
                              $response['error'] = true;
					$response['message'] = "User already exists! please goto LOGIN!";
                        }elseif($result == 3) {
                              $response['error'] = true;
					$response['message'] = "Passwords can't be empty";
                        }elseif($result == 4) {
                              $response['error'] = true;
					$response['message'] = "Confirm Password don't match!";
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
