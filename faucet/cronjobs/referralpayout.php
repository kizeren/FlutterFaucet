#!/usr/bin/php
<?php

/*

Copyright:: 2015, Shaun Mcbride (kizeren)

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

*/

$faucetaddy = "FAe5JVpn7Cyi2wGq9V9tDtHAdzREWLpvuv";
$fmessage = "Here's your payout!  Don't forget, leave your wallet open for more FLT!";

// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');

// Begin log
$log->logInfo("Starting refferal payout cron...");




// Add referrals address to referral table to be counted.
$uReferral = $oFaucetpayout->getReferrals();

foreach($uReferral as $uData){
$raddress = $uData['raddress'];



$lookup = $mysqli->query("SELECT COUNT(*) FROM  `referrals` WHERE raddress = '$raddress'");
$row = $lookup->fetch_row();
$mamt = $row[0];




if ($mamt == 0) {
                        $result = $mysqli->query("INSERT INTO referrals (raddress) VALUES ('$raddress')");
			echo "Test";
	}

}


//Start counting and payout process.



$lookup = $mysqli->query("SELECT * FROM referrals");
while($row = $lookup->fetch_assoc())
{
$raddress = $row['raddress'];


$count = $mysqli->query("SELECT COUNT(*) FROM refferals WHERE raddress = '$raddress'");
$amt = $count->fetch_row();
$amount = $amt[0];

echo "$raddress: $amount\n\r";


}


?>
