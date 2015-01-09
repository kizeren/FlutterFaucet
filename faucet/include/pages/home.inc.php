<?php

// Make sure we are called from index.php
if (!defined('SECURITY')) die('Hacking attempt');

// Include markdown library
use \Michelf\Markdown;

// Display the payout amount
$smarty->assign("PAYOUT", $config['payout']);

// Log the user
if(isset($_POST['userAddress']) && $_POST['userAddress'] !== '') {
    $faucetusers->logUser();
    $faucetusers->logAddress();
//    unset($_POST['userAddress']);
}

// Log Refers

if(isset($_POST['uAddress']) && isset($_POST['rAddress']) && $_POST['uAddress'] !== '' && $_POST['rAddress'] !== ''){
    $faucetusers->logRefers();
}


if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  // Fetch active news to display
  $aNews = $news->getAllActive();
  if (is_array($aNews)) {
    foreach ($aNews as $key => $aData) {
      // Transform Markdown content to HTML
      $aNews[$key]['content'] = Markdown::defaultTransform($aData['content']);
    }
  }

  $smarty->assign("HIDEAUTHOR", $setting->getValue('hide_news_author'));
  $smarty->assign("NEWS", $aNews);
} else {
  $debug->append('Using cached page', 3);
}
// Load news entries for Desktop site and unauthenticated users
$smarty->assign("CONTENT", "default.tpl");


if (!$smarty->isCached('master.tpl', $smarty_cache_key)) {
  $debug->append('No cached version available, fetching from backend', 3);
  if ($bitcoin->can_connect() === true){
    $dBalance = $bitcoin->getbalance();
    $aGetInfo = $bitcoin->getinfo();
    if (is_array($aGetInfo) && array_key_exists('newmint', $aGetInfo)) {
      $dNewmint = $aGetInfo['newmint'];
    } else {
      $dNewmint = -1;
    }
  } else {
    $aGetInfo = array('errors' => 'Unable to connect');
    $dBalance = 0;
    $dNewmint = -1;
    $_SESSION['POPUP'][] = array('CONTENT' => 'Unable to connect to wallet RPC service: ' . $bitcoin->can_connect(), 'TYPE' => 
'errormsg');
  }

  // Cold wallet balance
  if (! $dColdCoins = $setting->getValue('wallet_cold_coins')) $dColdCoins = 0;
  $smarty->assign("BALANCE", $dBalance);
  $smarty->assign("COLDCOINS", $dColdCoins);
  $smarty->assign("NEWMINT", $dNewmint);
  $smarty->assign("COININFO", $aGetInfo);

  // Tempalte specifics
} else {
  $debug->append('Using cached page', 3);
}

$result = $mysqli->query("SELECT COUNT(*) FROM `message`");
$row = $result->fetch_row();
$iTotal = $row[0];

$smarty->assign("TOTALSEEN", $iTotal);


