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

$daemonRPC = new daemonRPC('127.0.0.1', 18081); // Change to match your daemon (monerod) IP address and port; 18081 is the default port for mainnet, 28081 for testnet, 38081 for stagenet
// $daemonRPC = new daemonRPC(['host' => '127.0.0.1', 'port' => 28081]) // Passing parameters in as array; parameters can be in any order and all are optional.

//Setup walletRPC
require_once('src/walletRPC.php');

$walletRPC = new walletRPC('127.0.0.1', 18083); // Change to match your wallet (monero-wallet-rpc) IP address and port; 18083 is the customary port for mainnet, 28083 for testnet, 38083 for stagenet
// $daemonRPC = new walletRPC(['host' => '127.0.0.1', 'port' => 28081]) // Passing parameters in as array; parameters can be in any order and all are optional.

if (!empty($_POST['address']) && !empty($_POST['viewkey'])) {
	//$address = trim(htmlspecialchars($_POST['address']));
	//$viewkey = trim(htmlspecialchars($_POST['viewkey'])); 
    $address = preg_replace("/[^a-zA-Z0-9]/", "", $_POST['address']);
    $viewkey = preg_replace("/[^a-zA-Z0-9]/", "", $_POST['viewkey']);
}

if ((strlen($address) > 100) || (strlen($viewkey) > 70)){
    exit("Input has invalid address/viewkey.");
}

$generate_from_keys = $walletRPC->generate_from_keys('monero_wallet', '','$address','$viewkey'); // Creates a new wallet named monero_wallet with no passphrase.
$get_height = $walletRPC->get_height();
$getblockcount = $daemonRPC->getblockcount();

while($get_height != $getblockcount){
    usleep(250*1000);
    $get_height = $walletRPC->get_height();
    $getblockcount = $daemonRPC->getblockcount();
}

//use store and confirm wallet save

$walletRPC->store();


$close_wallet = $walletRPC->close_wallet();

//Syncronized Wallet File Is Now Prepared



//Send File to Client


//Option 1

$file = "monero_wallet";

// Quick check to verify that the file exists
if (!file_exists($file)) die("File not found");

// Force the download
header("Content-Disposition: attachment; filename="" . basename($file) . """);
header("Content-Length: " . filesize($file));
header("Content-Type: application/octet-stream;");
readfile($file);


echo $address;

?>
