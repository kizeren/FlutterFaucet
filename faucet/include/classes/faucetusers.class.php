<?php

// Make sure we are called from index.php
if (!defined('SECURITY'))
	die('Hacking attempt');

class Faucetusers extends Base {

	protected $table = 'users';
	
	/**
	 * Log the information from a user faucet request
	 **/
	public function logUser() {
		$userIP = $this->user->getCurrentIP();
		$userAddress = $_POST['userAddress'];
		if (!$this->bitcoin->validateaddress($userAddress)) {
			$_SESSION['POPUP'][] = array('CONTENT' => "There's been a problem, your address doesn't match the format for our currency. Please try again with another address.", 'TYPE' => 'info');
			return false;
		}
		if ($this->checkUserIP($userIP)) {
			$stmt = $this->mysqli->prepare("INSERT INTO $this->table (user_address, user_ip) VALUES (?,?)");
			$stmt->bind_param('ss',$userAddress,$userIP);
			$stmt->execute();    
		        $_SESSION['POPUP'][] = array('CONTENT' => "Thank you for using our faucet, you can come back in 24 hours for more coin!", 'TYPE' => 'info');
			return true;
		} else {
			$_SESSION['POPUP'][] = array('CONTENT' => "There has already been a request from your location today, please wait 24 hours between submissions.", 'TYPE' => 'info');
			return false;
		}
	}
	public function logAddress() {
             $userAddress = $_POST['userAddress'];
             $userPublicKey = $_POST['userPublicKey'];
               if ($userPublicKey != "") {


                $this->bitcoin->smsgaddkey($userAddress, $userPublicKey);


        }
		if ($this->checkUserMessage($userAddress)) {
                        //pubkey not used yet.
                        $pubkey = '0';
			$stmt = $this->mysqli->prepare("INSERT INTO message (address, pubkey) VALUES (?,?)");
			$stmt->bind_param('ss',$userAddress,$pubkey);
			$stmt->execute();    
			return true;
		} else {
			return false;
		}
	}
	public function logRefers() {
             $uAddress = $_POST['uAddress'];
             $rAddress = $_POST['rAddress'];

             if ($uAddress == $rAddress) {
    
			$_SESSION['POPUP'][] = array('CONTENT' => "Nice try! But your address and the reffering address are the same!!", 'TYPE' => 'info');
	                return;                  
                }




                if ($uAddress != "" && $rAddress !== "") {

                $addy ="FAe5JVpn7Cyi2wGq9V9tDtHAdzREWLpvuv";
                $msga = "Your referral has been added, thanks!  You reffered user: $uAddress.";
                $msgb = "Thanks for letting us know who reffered you!";
                $this->bitcoin->smsgsend($addy, $rAddress, $msga);

        }
			$stmt = $this->mysqli->prepare("INSERT INTO refferals (uaddress, raddress) VALUES (?,?)");
			$stmt->bind_param('ss',$uAddress,$rAddress);
			$stmt->execute();


	}
	
	public function checkUserIP($userIP) {

		$this->debug->append("STA " . __METHOD__, 4);
		$stmt = $this->mysqli->prepare("SELECT COUNT(*) FROM $this->table WHERE user_ip = ?");
		if ($this->checkStmt($stmt)) {
			$stmt->bind_param("s", $userIP);
			$stmt->execute();
			$stmt->bind_result($retval);
			$stmt->fetch();
			$stmt->close();
			if ($retval == 0)
				return true;
		}
		return false;
	}

        public function checkUserMessage($userAddress) {
                $this->debug->append("STA " . __METHOD__, 4);
                $stmt = $this->mysqli->prepare("SELECT COUNT(*) FROM message WHERE address = ?");
                if ($this->checkStmt($stmt)) {
                        $stmt->bind_param("s", $userAddress);
                        $stmt->execute();
                        $stmt->bind_result($retval);
                        $stmt->fetch();
                        $stmt->close();  
                        if ($retval == 0)
                                return true;
                }
                return false;
        }

	
	public function emptyTable() {
		$this->debug->append("STA " . __METHOD__, 4);
		$stmt = $this->mysqli->prepare("TRUNCATE TABLE $this->table")->execute();
	}
}

// Make our class available automatically
$faucetusers = new Faucetusers();
$faucetusers->setMysql($mysqli);
$faucetusers->setDebug($debug);
$faucetusers->setErrorCodes($aErrorCodes);
$faucetusers->setUser($user);
$faucetusers->setBitcoin($bitcoin);
