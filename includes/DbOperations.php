<?php

class DbOperations {
	private $con;
	function __construct() {
		require_once dirname(__FILE__).'/DbConnect.php';
		$db = new DbConnect();
		//calling the method 'connect' of DbConnect class
		$this->con = $db->connect();
	}

	/*CRUD -> C -> CREATE*/
	public function createUser($Pid,$email,$pass,$check_pass,$Name,$sex,$DOB,$Mob_no,$Hostel,$Room_no,$Bloodgrp,$Weight,$lati,$longi) {
		//checking if patient is already registered
		if($this->isPatientExists($Pid,$email)) {
			return 0; //exists
		}else {
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

				$stmt = $this-> con-> prepare("INSERT INTO `Pat_pro` (`Pid`,`email`, `Password`, `ConfirmPass`,`Name`, `Sex`,
					 `DOB`, `Mob_no`, `Hostel`, `Roomno`, `Bloodgrp`, `weight`,`lati`,`longi`) VALUES (?, ?, ?, ?, ?, ?, ?, ?,
						 ?, ?, ?, ?, ?, ?);");
				//binding parameters
				$stmt->bind_param("ssssssssssssss",$Pid,$email,$Password,$encrypted1,$Name,$sex,$DOB,$Mob_no,$Hostel,$Room_no,$Bloodgrp,$Weight,$lati,$longi);

				if($stmt->execute()){
					return 1;
					//student created successfully
				}
				else{
					return 2;
					//failed to create student
				}
			}
		}
	}

	private function isPatientExists($Pid) {
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
		$stmt = $this->con->prepare("SELECT * from Pat_pro where Pid = ?");
		$stmt->bind_param("s",$Pid);
		$stmt->execute();
		return $stmt->get_result()->fetch_assoc();
	}
}
