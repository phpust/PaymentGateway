<?php

ini_set('display_errors',true);
require 'vendor/autoload.php';
use \Gateways\Gateway\Zarinpal\Zarinpal;
use \Gateways\Gateway\Classes\Persistent;

if(isset($_REQUEST['data'])){
    $persistent = new Persistent();
    $data = $persistent->load($_REQUEST['extra']);

    $gateway = new Zarinpal(
        [
            'wsdlLink'  => 'https://ir.zarinpal.com/pg/services/WebGate/wsdl',
            'forwardUrl'=> 'https://www.zarinpal.com/pg/StartPay/',
            'MerchantID'=> '5e3fcc96-d416-11e5-9ffc-000c295eb8fc',
        ], $persistent);

    echo $gateway->verify($data+$_REQUEST);die();

}

$gateway = new Zarinpal(
    [
        'wsdlLink'  => 'https://ir.zarinpal.com/pg/services/WebGate/wsdl',
        'forwardUrl'=> 'https://www.zarinpal.com/pg/StartPay/',
        'MerchantID'=> '5e3fcc96-d416-11e5-9ffc-000c295eb8fc',
    ], new Persistent());

$gateway->request('http://37.98.114.210:50780/GatewayManagement/?data=sajad',5,100,['Email'=>'s@gmail.com']);

var_dump($gateway->getForwardUrl());
var_dump($gateway->getParams());
