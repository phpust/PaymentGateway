<?php

namespace Gateways\Gateway\Abstracts;


abstract class GatewayAbstract
{
    private $lastResponse;

    private $storagePersistent;

    private $connector;

    /**
     * receive all option needed for creating connector object
     *
     * @param $callBackPrefix
     * @param $id
     * @param $amount
     * @param array $options
     * @return mixed
     */
    abstract function request($callBackPrefix, $id, $amount, $options = []);

    /**
     * verify transaction . communicate to gateway webservice and check data for validating
     *
     * @param $options
     * @return mixed
     */
    abstract function verify($options);

    /**
     * generate forwarding url by receive data from webservice and user prefix
     *
     * @return string forwarding url
     */
    abstract function getForwardUrl();

    /**
     * get all needy data for form generation
     *
     * @return string forwarding url
     */
    abstract function getParams();

    /**
     * magic function to get data to options
     *
     * supports :
     * [0] => get()
     * [1] => get('key1')
     * [2] => get('key1','key2')
     * [3] => getKey1()
     *
     * @param $name
     * @param $arguments
     * @return $this
     */
    function __call($name, $arguments)
    {
        ## get[PropertyName]
        $key = substr($name, 3);

        ## [set]PropertyName
        switch (substr($name, 0, 3)) {
            case 'get':
                // generate proper data for getter , empty key means get() called
                $data = empty($key) ? (isset($arguments[0]) ? $arguments : null )  : [ $key ] ;

                return $this->get($data);
                break;

            default:
                throw new \BadMethodCallException($name);
                break;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStoragePersistent()
    {
        return $this->storagePersistent;
    }

    /**
     * @param mixed $storagePersistent
     * @return GatewayAbstract
     */
    public function setStoragePersistent($storagePersistent)
    {
        $this->storagePersistent = $storagePersistent;
        return $this;
    }

    /**
     * @param string $callBackPrefix
     * @param array | string $data
     * @return GatewayAbstract
     */
    public function getQuery($callBackPrefix, $data)
    {
        if(empty($data))
            return $callBackPrefix;

        if(!is_array($data))
            $data = [ $data ];

        if ( strpos($callBackPrefix, '?') !== false )
            return $callBackPrefix.'&extra='.implode('&',$data);

        return $callBackPrefix.'?extra='.implode('&',$data);
    }

    /**
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @param mixed $lastResponse
     */
    public function setLastResponse($lastResponse)
    {
        $this->lastResponse = $lastResponse;
    }

    /**
     * @return mixed
     */
    public function getConnector()
    {
        return $this->connector;
    }

    /**
     * @param mixed $connector
     */
    public function setConnector($connector)
    {
        $this->connector = $connector;
    }
}