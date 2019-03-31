<?php
require_once 'DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {

      if( isset($_POST['Pid']) and isset($_POST['password']) ){
            $db = new DbOperations();

            if($db->patientLogin( $_POST['Pid'], $_POST['password'] )) {
                  $user = $db->getPatientbyPid($_POST['Pid']);
                  //$response['error'] = false;
                  //$response['message'] = 'LoggedIn Successfully!';
                  $response[] = array('error' => false, 'message' => 'LoggedIn Successfully!');

                  while($fetch_row = $user->fetch_assoc()) {
                        $response[] = array(
                                          'PatientID' => $fetch_row['Pid'],
                                          //'Hospital' => $fetch_row['Hospital_name'],
                                          'DocID' => $fetch_row['Username'],
                                          'Date' => $fetch_row['treatment_date'],
                                          'Slot'=> $fetch_row['slot'],
                                          //'Symptoms' => $fetch_row['symptoms'],
                                          'Diagnosis' => $fetch_row['diagnosis'],
                                          //'Prescription' => $fetch_row['prescription'],
                                          //'Remarks' => $fetch_row['remarks'],
                                    );
                  }
            }
            else {
                  $response[] = array('error' => true, 'message' => 'Invalid username or password!');
                  //$response['error'] = true;
                  //$response['message'] = 'Invalid username or password!';
            }

      } else {
            $response[] = array('error' => true, 'message' => 'Required fields are missing!');
//            $response['error'] = true;
//            $response['message'] = 'Required fields are missing!';
      }
}


echo json_encode($response);
