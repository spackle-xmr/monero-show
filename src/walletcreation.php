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



//Option 2

// grab the requested file's name
$file_name = $_GET['file'];

// make sure it's a file before doing anything!
if(is_file($file_name)) {

	/*
		Do any processing you'd like here:
		1.  Increment a counter
		2.  Do something with the DB
		3.  Check user permissions
		4.  Anything you want!
	*/

	// required for IE
	if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off');	}

	// get the file mime type using the file extension
	switch(strtolower(substr(strrchr($file_name, '.'), 1))) {
		case 'pdf': $mime = 'application/pdf'; break;
		case 'zip': $mime = 'application/zip'; break;
		case 'jpeg':
		case 'jpg': $mime = 'image/jpg'; break;
		default: $mime = 'application/force-download';
	}
	header('Pragma: public'); 	// required
	header('Expires: 0');		// no cache
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($file_name)).' GMT');
	header('Cache-Control: private',false);
	header('Content-Type: '.$mime);
	header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: '.filesize($file_name));	// provide file size
	header('Connection: close');
	readfile($file_name);		// push it out
	exit();
}

echo $address;

?>
