<?php
require_once 'DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST') {
      if( isset($_POST['Pid']) and isset($_POST['Username']) and isset($_POST['treatment_date']) and isset($_POST['slot']) ){
            $db = new DbOperations();

            $user = $db->fetch_card_view(isset($_POST['Pid']), isset($_POST['Username']), isset($_POST['treatment_date']), isset($_POST['slot']));
            while($fetch_row = $user->fetch_assoc()) {
                  $response[] = array(
                                    //'PatientID' => $fetch_row['Pid'],
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
            else {
                  $response['error'] = true;
                  $response['message'] = 'Invalid username or password!';
            }

      } else {
            $response['error'] = true;
            $response['message'] = 'Required fields are missing!';
      }
}


echo json_encode($response);
