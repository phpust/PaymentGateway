<?php

namespace Gateways\Connectors\Abstracts;


use Gateways\Connectors\Classes\Options;
use Gateways\Gateway\Exceptions\StorageIsNotValid;
use Gateways\Gateway\Interfaces\iStorage;

abstract class ConnectorAbstract extends Options
{

    private $result;

    private $connector = null;

    /**
     * ConnectorAbstract constructor.
     * @param null $options
     * @param null $persistent instance of storage class
     *
     * @throws StorageIsNotValid
     * @throws \Gateways\Connectors\Exceptions\EmptyStringException
     * @throws \Gateways\Connectors\Exceptions\NotValidInputException
     * @throws \Gateways\Connectors\Exceptions\NotValidStringException
     */
    function __construct($options = null, $persistent = null)
    {
        if( ! $persistent instanceof iStorage ){
            throw new StorageIsNotValid();
        }

        if ($options === null)
            return;

        foreach( $options as $key => $option ){
            $this->set($key, $option);
        }

    }


    /**
     * returns last recieved data after send request to end-point from connector
     *
     * @return array | null
     */
    function receive(){
        return $this->result;
    }


    /**
     * check if connector is connected or not
     *
     * @return boolean
     */
    function isConnected(){
        return is_null($this->connector) ? false : true;
    }

    /**
     * @param mixed $result
     * @return mixed
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * get connector instance
     * @return null
     */
    protected function getConnector()
    {
        return $this->connector;
    }

    /**
     * set connector instance
     *
     * @param null $connector
     */
    protected function setConnector($connector)
    {
        $this->connector = $connector;
    }

    /**
     * Close connection
     * @return void
     */
    function disconnect(){
        $this->connector = null ;
    }
}
