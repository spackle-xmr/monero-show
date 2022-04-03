<?php

use MoneroIntegrations\MoneroPhp\daemonRPC;
use MoneroIntegrations\MoneroPhp\walletRPC;

// Make sure to display all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Setup daemonRPC
require_once('src/jsonRPCClient.php');
require_once('src/daemonRPC.php');
$daemonRPC = new daemonRPC('172.17.0.1', 18089, false);

//Setup walletRPC
require_once('src/walletRPC.php');
$walletRPC = new walletRPC('172.17.0.1', 18083, false);

//Get form data
$address=$_POST["address"];
$viewkey=$_POST["viewkey"];

if ((strlen($address) > 100) || (strlen($viewkey) > 70)){
    exit("Input has invalid address/viewkey.");
}



$generate_from_keys = $walletRPC->generate_from_keys('monero_wallet', '', $address, $viewkey); // Create wallet
$get_height = $walletRPC->get_height();
$getblockcount = $daemonRPC->getblockcount();

//while($get_height != $getblockcount){
//    usleep(250*1000);
//    $get_height = $walletRPC->get_height();
//    $getblockcount = $daemonRPC->getblockcount();
//}


//store wallet and close
$walletRPC->store();
$close_wallet = $walletRPC->close_wallet();

//Send File to Client
$file = 'monero_wallet';

if (file_exists($file)) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="monero_wallet"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    readfile($file);
    //delete file after sending
    unlink($file);
}
    
exit;


?>
