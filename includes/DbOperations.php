<?php

class DbOperations {
	private $con;
	function __construct() {
		require_once dirname(__FILE__).'/DbConnect.php';
		$db = new DbConnect();
		//calling the method 'connect' of DbConnect class
		$this->con = $db->connect();
	}

/******************************** PAT ************************************/
	public function createUser($Pid,$email,$pass,$check_pass,$Name,$sex,$DOB,$Mob_no,$Hostel,$Room_no,$Bloodgrp,$Weight,$lati,$longi) {
		//checking if patient is already registered
		if(empty($Pid)) {
			return 7; //email can't be empty
		}
		else if(empty($email)) {
			return 6; //username can't be empty
		}
		else {
			if($this->isPatientExists($email)) {
				return 0; //exists
			}else {
				if($this->isPidTaken($Pid)) {
					return 5;
				}
				else {
					if(empty($pass) || empty($check_pass)) {
						return 3;	//can't be empty
					}
					elseif($pass != $check_pass) {
						return 4;	//passwords don't match
					}
					else {
						//encrypting password
						$Password = md5($pass);
						$encrypted1 = md5($check_pass);
						//generating an API key
						//$apikey = $key->generateApikey();

						$stmt = $this->con-> prepare("INSERT INTO `Pat_pro` (`Pid`,`email`, `Password`, `ConfirmPass`,`Name`, `Sex`,
							 `DOB`, `Mob_no`, `Hostel`, `Roomno`, `Bloodgrp`, `weight`,`lati`,`longi`) VALUES (?, ?, ?, ?, ?, ?, ?, ?,
								 ?, ?, ?, ?, ?, ?);");
						//binding parameters
						$stmt->bind_param("ssssssssssssss",$Pid,$email,$Password,$encrypted1,$Name,$sex,$DOB,$Mob_no,$Hostel,$Room_no,$Bloodgrp,$Weight,$lati,$longi);

						if($stmt->execute()){
							return 1;
							//patient registered successfully
						}
						else{
							return 2;
							//failed to register patient
						}
					}
				}

			}
		}
	}

	private function isPatientExists($email) {
		$stmt = $this->con->prepare("SELECT email from Pat_pro where email = ?");
		$stmt->bind_param("s",$email);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
	}

	private function isPidTaken($Pid) {
		$stmt = $this->con->prepare("SELECT Pid from Pat_pro where Pid = ?");
		$stmt->bind_param("s",$Pid);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
	}

	public function patientLogin($Pid, $pass) {
		$Password = md5($pass);
		$stmt = $this->con->prepare("SELECT Pid FROM Pat_pro where Pid = ? and Password = ?");
		$stmt->bind_param("ss",$Pid,$Password);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
	}

	public function getPatientbyPid($Pid) {
		$stmt = $this->con->prepare("SELECT * from Treatment_details where Pid = ?");
		$stmt->bind_param("s",$Pid);
		$stmt->execute();
		return $stmt->get_result();
	}

	/******************************** E.O.PAT ************************************/

	/********************************** DOC ************************************/
	public function createDoc($username,$email,$fullname,$pass,$check_pass,$specialization,$shiftType,$Mob_no,$sex,$DOB) {
		if(empty($username)) {
			return 7; //email can't be empty
		}
		else if(empty($email)) {
			return 6; //username can't be empty
		}
		else if(empty($shiftType)) {
			return 8;	//shift_type can't be empty
		}
		else {
			//checking if patient is already registered
			if($this->isDocExists($email)) {
				return 0; //exists
			}else {
				if($this->isUserNameTaken($username)) {
					return 5;
				}
				else {
					if(empty($pass) || empty($check_pass)) {
						return 3;	//can't be empty
					}
					elseif($pass != $check_pass) {
						return 4;	//passwords don't match
					}
					else {
						//encrypting password
						$encrypted1 = md5($pass);
						$encrypted2 = md5($check_pass);

						/*$timein,$timeout;
						if($shiftType == "Morning") {
							$timein =
						}*/

						$stmt = $this->con-> prepare("INSERT INTO `Doc_pro` (`Username`,`email`, `FullName`, `Password`, `ConfirmPass`,`specialization`,
							`shift_type`, `Mob_no`, `Sex`, `DOB`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
						//binding parameters
						$stmt->bind_param("ssssssssss",$username,$email,$fullname,$encrypted1,$encrypted2,$specialization,$shiftType,$Mob_no,$sex,$DOB);

						if($stmt->execute()){
							return 1;
							//Doctor registered successfully
						}
						else{
							return 2;
							//failed to register doctor
						}
					}
				}

			}
		}
	}

	private function isDocExists($email) {
		$stmt = $this->con->prepare("SELECT email from Doc_pro where email = ?");
		$stmt->bind_param("s",$email);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
	}

	private function isUserNameTaken($username) {
		$stmt = $this->con->prepare("SELECT Username from Doc_pro where Username = ?");
		$stmt->bind_param("s",$username);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
	}

	public function getDocHistoryByUsername($username) {
		$stmt = $this->con->prepare("SELECT * from Treatment_details where Username = ?");
		$stmt->bind_param("s",$username);
		$stmt->execute();
		return $stmt->get_result();
	}

	public function docLogin($username, $Pass) {
		$Password = md5($Pass);
		$stmt = $this->con->prepare("SELECT Username FROM Doc_pro where Username = ? and Password = ?");
		$stmt->bind_param("ss",$username,$Password);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
	}
	/******************************** E.O.DOC **********************************/

	/***************************** Appointments **********************************/
	public function bookAppointment($pid, $username, $date, $slot) {
		if($this->isAlreadyBooked($username, $date, $slot)) {
			return 3;
		}
		else {
			if(empty($date) || empty($slot)) {
				return 2;	//can't be empty
			}
			else {
				$stmt = $this->con->prepare("INSERT INTO `Appointments` (`Pid`, `Username`, `treatment_date`, `slot`) VALUES (?, ?, ?, ?);");
				$stmt->bind_param("ssss",$pid, $username, $date, $slot);
				if($stmt->execute()) {
					return 1;	//successful
				}
				else {
					return 0;	//unsuccessful
				}
			}
		}
	}

	public function isAlreadyBooked($username, $date, $slot) {
		$stmt = $this->con->prepare("SELECT * from Appointments where Username = ? AND treatment_date = ? AND slot = ?");
		$stmt->bind_param("sss",$username, $date, $slot);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
	}
	/*************************** E.O. Appointments **********************************/

	/*************************** treat new patients **********************************/
	public function treatNewPatient($pid, $hospital_name, $username, $treatment_date, $slot, $symptoms, $diagnosis, $prescription, $remarks) {
		$stmt = $this->con->prepare("INSERT INTO `Treatment_details` (`Pid`, `Hospital_name`, `Username`, `treatment_date`, `slot`, `symptoms`, `diagnosis`, `prescription`, `remarks`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sssssssss",$pid, $hospital_name, $username, $treatment_date, $slot, $symptoms, $diagnosis, $prescription, $remarks);

		if($this->ifSubmittedAlready($pid, $username, $treatment_date, $slot)) {
			return 2;	//already submitted
		}
		else {
			if($stmt->execute()) {
				return 1;	//successful
			}
			else {
				return 0;	//unsuccessful
			}
		}
	}

	private function ifSubmittedAlready($pid, $username, $treatment_date, $slot) {
		$stmt = $this->con->prepare("SELECT * from Treatment_Details where Pid = ? AND Username = ? AND treatment_date = ? AND slot = ?");
		$stmt->bind_param("ssss",$pid, $username, $treatment_date, $slot);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
	}

	/************************ E.O. treat new patients **********************************/







	public function fetch_card_view($pid, $username, $treatment_date, $slot) {
		$stmt = $this->con->prepare("SELECT * from Treatment_Details where Pid = ? AND Username = ? AND treatment_date = ? AND slot = ?");
		$stmt->bind_param("ssss",$pid, $username, $treatment_date, $slot);
		$stmt->execute();
		return $stmt->get_result();
	}
}
