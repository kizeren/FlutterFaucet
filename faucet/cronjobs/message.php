
#!/usr/bin/php
<?php

/*

Copyright:: 2014, Grant Brown

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
$fmessage = "Broadcast Message!";

// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');




$host = 'localhost';
$dbuser = 'localuser';
$dbpass = 'password';
$database = 'water';


$db = mysqli_connect("$host", "$dbuser", "$dbpass", "$database");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}






if ($result = mysqli_query($db, "SELECT * FROM message")) {
    printf("Select returned %d rows.\n", mysqli_num_rows($result));


    /* free result set */
    mysqli_free_result($result);
}  


/* If we have to retrieve large amount of data we use MYSQLI_USE_RESULT */
if ($result = mysqli_query($db, "SELECT * FROM message", MYSQLI_USE_RESULT)) {

    /* Note, that we can't execute any functions which interact with the
       server until result set was closed. All calls will return an
       'out of sync' error */
    if (!mysqli_query($db, "SET @a:='this will not work'")) {
        printf("Error: %s\n", mysqli_error($db));
    }
    mysqli_free_result($result);
}


$query = "SELECT * FROM message";
$result = $db->query($query);

while($row = $result->fetch_array())
{
$rows[] = $row;
}

foreach($rows as $row)
{
$message = $bitcoin->smsgsend($faucetaddy, $row['address'], $fmessage);

//echo $row['address'];
}

$result->close();



?>
