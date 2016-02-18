<?php

namespace Gateways\Gateway\Zarinpal;


use Gateways\Connectors\SoapClient;
use Gateways\Gateway\Abstracts\GatewayAbstract;
use Gateways\Gateway\Exceptions\WrongInformationException;
use Gateways\Gateway\Interfaces\iStorage;

class Zarinpal extends GatewayAbstract
{

    /**
     * Zarinpal constructor.
     *
     * @param null $options
     * @param iStorage|null $persistent
     */
    function __construct($options = null, iStorage $persistent = null)
    {
        $this->setStoragePersistent($persistent);
        $this->setConnector(new SoapClient($options, $persistent ));
    }

    /**
     * receive all option needed for creating connector object
     *
     * @param $callBackPrefix
     * @param $id
     * @param $amount
     * @param array $options
     * @return mixed
     * @throws UnknownException
     * @throws \Gateways\Connectors\Exceptions\AccessNonObjectException
     * @throws \Gateways\Connectors\Exceptions\EmptyStringException
     * @throws \Gateways\Connectors\Exceptions\NotValidStringException
     * @throws ArchivedAccessException
     * @throws FailedTransactionException
     * @throws LowerThanMinimumAmountException
     * @throws NoTransactionFoundException
     * @throws RequestNotFoundException
     * @throws TwiceVerificationException
     * @throws UserSilverLevelException
     * @throws WrongAmountException
     * @throws WrongInformationException
     * @throws WrongServiceCredentialException
     */
    function request($callBackPrefix, $id, $amount, $options = [])
    {
        // TODO: fix Iranian Toman to Iranian Rial.

        // TODO: Create id by iStorage instance.
        $metaData = $this->getStoragePersistent()->save( [ 'id'=>$id , 'amount'=>$amount, 'options'=>$options ] );

        // TODO: generate proper option to send.
        $request = [
            'CallbackURL'   => $this->getQuery($callBackPrefix, $metaData),
            'MerchantID'    => $this->getConnector()->get('MerchantID'),
            'Amount'        => $amount,
            'Description'   => isset($options['Description']) ? $options['Description']  : 'Default description ',
        ];
        isset($options['Email']) ? $request['Email'] = $options['Email']  : false ;
        isset($options['Mobile']) ? $request['Mobile'] = $options['Mobile']  : false ;

        // TODO: send request by connector.
        $result = $this->send('PaymentRequest',$request);

        // TODO: Check for errors.
        if(! $this->zarinpalHandler($result) ){
            throw new UnknownException();
        }

        // TODO: Initialize data received from web services to proper variables.
        $this->setLastResponse($result);

    }

    /**
     * verify transaction . communicate to gateway webservice and check data for validating
     *
     * @param $inputs
     * @return mixed
     * @throws ArchivedAccessException
     * @throws FailedTransactionException
     * @throws InputNotExistException
     * @throws LowerThanMinimumAmountException
     * @throws NoTransactionFoundException
     * @throws RequestNotFoundException
     * @throws TwiceVerificationException
     * @throws UnknownException
     * @throws UserSilverLevelException
     * @throws WrongAmountException
     * @throws WrongInformationException
     * @throws WrongServiceCredentialException
     */
    function verify($inputs)
    {
        // TODO: check for existing valid data.
        if( ! isset($inputs['Authority']) )
            throw new InputNotExistException('Authority');

        if( ! isset($inputs['Status']) )
            throw new InputNotExistException('Status');

        if( ! isset($inputs['Amount']) )
            throw new InputNotExistException('Amount');
        // TODO: Implement verify() method.

        if( $inputs['Status'] == 'NOK')
            return false;

        $request = array(
                'MerchantID'	 => $this->getConnector()->get('MerchantID'),
                'Authority' 	 => $inputs['Authority'],
                'Amount'	     => $inputs['Amount']
            );
        // TODO: send request by connector.
        $result = $this->send('PaymentVerification',$request);

        // TODO: Check for errors.
        if(! $this->zarinpalHandler($result) ){
            throw new UnknownException();
        }

        // TODO: Initialize data received from web services to proper variables.
        $this->setLastResponse($result);

        return true;
    }

    /**
     * generate forwarding url by
     *
     * @return string forwarding url
     */
    function getForwardUrl()
    {
        if( $this->zarinpalHandler( $this->getLastResponse() ) )
            return $this->getConnector()->get('forwardUrl').$this->getLastResponse()->Authority;
        return false;
    }

    /**
     * get all needy data for form generation
     *
     * @return string forwarding url
     */
    function getParams()
    {
        return [];
    }

    /**
     * sending recieved expression from selected connector
     *
     * @param $methodName
     * @param $expression
     * @return array|bool
     */
    private function send($methodName, $expression)
    {
        $this->getConnector()->send($methodName, $expression);
        return $this->getConnector()->receive();
    }

    /**
     * throw errors for failed status
     *
     * @param $result
     * @return array|bool
     * @throws ArchivedAccessException
     * @throws FailedTransactionException
     * @throws LowerThanMinimumAmountException
     * @throws NoTransactionFoundException
     * @throws RequestNotFoundException
     * @throws TwiceVerificationException
     * @throws UserSilverLevelException
     * @throws WrongAmountException
     * @throws WrongInformationException
     * @throws WrongServiceCredentialException
     */
    private function zarinpalHandler($result)
    {
        switch($result->Status){
            case '-1':
                throw new WrongInformationException();
                break;
            case '-2':
                throw new WrongServiceCredentialException();
                break;
            case '-3':
                throw new LowerThanMinimumAmountException();
                break;
            case '-4':
                throw new UserSilverLevelException();
                break;
            case '-11':
                throw new RequestNotFoundException();
                break;
            case '-21':
                throw new NoTransactionFoundException();
                break;
            case '-22':
                throw new FailedTransactionException();
                break;
            case '-33':
                throw new WrongAmountException();
                break;
            case '-54':
                throw new ArchivedAccessException();
                break;
            case '100':
                return true;
                break;
            case '101':
                throw new TwiceVerificationException();
                break;
        }

        return false;
    }
}