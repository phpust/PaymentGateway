<?php

namespace Gateways\Connectors;

use Gateways\Connectors\Abstracts\ConnectorAbstract;
use Gateways\Connectors\Interfaces\iConnector;

class SoapClient extends ConnectorAbstract implements iConnector
{
    /**
     * sending recieved expression from selected connector
     *
     * @param $methodName
     * @param $expression
     * @return array|bool
     */
    function send($methodName, $expression)
    {
        $this->prepareConnector();

        return $this->setResult(
            $this->getConnector()->{$methodName}($expression)
        );
    }


    /**
     * create instance of current communicator and set it to $connector
     *
     * @return iConnector instance
     */
    function prepareConnector()
    {
        if ( ! $this->isConnected())
            $this->setConnector( new \SoapClient( $this->get('wsdlLink') ) );

        return $this->getConnector();
    }

    /**
     * remove current connector on class destructor
     */
    function __destruct()
    {
        $this->disconnect();
    }

}