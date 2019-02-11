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
	public function createUser($Pid,$email,$pass,$Name,$sex,$DOB,$Mob_no,$Hostel,$Room_no,$Bloodgrp,$Weight,$lati,$longi) {
		//checking if patient is already registered
		if($this->isPatientExists($Pid,$email)) {
			return 0; //exists
		}else {
			//encrypting password
			$Password = md5($pass);
			//generating an API key
			//$apikey = $key->generateApikey();

			$stmt = $this-> con-> prepare("INSERT INTO `Pat_pro` (`Pid`,`email`, `Password`, `Name`, `Sex`,
				 `DOB`, `Mob_no`, `Hostel`, `Roomno`, `Bloodgrp`, `weight`,`lati`,`longi`) VALUES (?, ?, ?, ?, ?, ?, ?,
					 ?, ?, ?, ?, ?, ?);");
			//binding parameters
			$stmt->bind_param("sssssssssssss",$Pid,$email,$Password,$Name,$sex,$DOB,$Mob_no,$Hostel,$Room_no,$Bloodgrp,$Weight,$lati,$longi);

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

	private function isPatientExists($Pid,$email) {
		$stmt = $this->con->prepare("SELECT Pid,email from Pat_pro where Pid = ? or email = ?");
		$stmt->bind_param("ss",$Pid,$email);
		$stmt->execute();
		$stmt->store_result();
		return $stmt->num_rows > 0;
	}

	public function patientLogin($Pid, $pass) {
		$Password = md5($pass);
		$stmt = $this->con->prepare("SELECT Pid FROM Pat_pro where Pid = ? Password = ?");
		$stmt->bind_param("ss",$Pid,$Password);
		$stmt->execute();
		$tmt->store_result();
		return $stmt->num_rows > 0;
	}

	public function getPatientbyPid($Pid) {
		$stmt = $this->con->prepare("SELECT * from Pat_pro where Pid = ?");
		$stmt->bind_param("s",$Pid);
		$stmt->execute();
		return $stmt->get_result()->fetch_assoc();
	}
}
