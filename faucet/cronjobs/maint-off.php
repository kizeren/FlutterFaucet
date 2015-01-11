<?php
// Change to working directory
chdir(dirname(__FILE__));

// Include all settings and classes
require_once('shared.inc.php');





//Check for maintnence mode and set the field to zero.
$maint = $mysqli->query("SELECT * FROM maintenance");
while ($row = $maint->fetch_array()) {
    if ($row[0] == 1) {
        $mysqli->query("UPDATE maintenance SET maint = 0");
    }
}


?>

