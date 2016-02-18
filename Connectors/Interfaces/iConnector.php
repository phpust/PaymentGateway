<?php

namespace Gateways\Connectors\Interfaces;

interface iConnector
{

    /**
     * create instance of current communicator and set it to $connector
     *
     * @return boolean
     */
    function prepareConnector();


    /**
     * sending recieved expression from selected connector
     *
     * @param $methodName
     * @param $expression
     * @return array|bool
     */
    function send($methodName, $expression);


    /**
     * returns last recieved data after send request to end-point from connector
     *
     * @return array | null
     */
    function receive();

    /**
     * check if connector is connected or not
     *
     * @return boolean
     */
    function isConnected();


    /**
     * Close connection
     * @return void
     */
    function disconnect();
}