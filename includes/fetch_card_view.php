<?php
require_once 'DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {
      if( isset($_POST['Pid']) and isset($_POST['pid']) ) {
            $db = new DbOperations();

            //$user = $db->fetch_card_view(isset($_POST['Pid']), isset($_POST['Username']), isset($_POST['treatment_date']), isset($_POST['slot']));
            $user = $db->getPatientbyPid($_POST['Pid']);
            //$response['error'] = false;
            //$response['message'] = 'LoggedIn Successfully!';
            $response[] = array('error' => false, 'message' => 'Query Successful !');

            while($fetch_row = $user->fetch_assoc()) {
                  $response[] = array(
                                    'PatientID' => $fetch_row['Pid'],
                                    'Hospital' => $fetch_row['Hospital_name'],
                                    'DocID' => $fetch_row['Username'],
                                    'Date' => $fetch_row['treatment_date'],
                                    'Slot'=> $fetch_row['slot'],
                                    'Symptoms' => $fetch_row['symptoms'],
                                    'Diagnosis' => $fetch_row['diagnosis'],
                                    'Prescription' => $fetch_row['prescription'],
                                    'Remarks' => $fetch_row['remarks'],
                              );
            }
      } else {
            $response[] = array('error' => true, 'message' => 'Failed to fetch data!');
            //$response['error'] = true;
            //$response['message'] = 'Required fields are missing!';
      }
}
else {
      $response[] = array('error' => true, 'message' => 'Invalid request!');
}


echo json_encode($response);
