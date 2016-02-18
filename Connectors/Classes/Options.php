<?php

namespace Gateways\Connectors\Classes;

use Gateways\Connectors\Exceptions\AccessNonObjectException;
use Gateways\Connectors\Exceptions\AccessNonValidKeyException;
use Gateways\Connectors\Exceptions\EmptyStringException;
use Gateways\Connectors\Exceptions\NotValidInputException;
use Gateways\Connectors\Exceptions\NotValidStringException;
use Gateways\Connectors\Interfaces\iOption;

class Options implements iOption
{

    protected $options;

    /**
     * Checks whether the options is empty (contains no options).
     *
     * @return boolean TRUE if the collection is empty, FALSE otherwise.
     */
    public function isEmpty()
    {
        if( count( $this->options ) == 0 )
            return true;

        return false;
    }

    /**
     * Removes the option from options.
     * '*' |'Key' |['Key1','Key2']
     *
     * @param array | string $keys The key(s) of the option(s) to remove.
     * @return bool .
     *
     * @throws EmptyStringException
     * @throws AccessNonValidKeyException
     * @throws EmptyStringException
     * @throws NotValidStringException
     */
    public function remove( $keys='*' )
    {
        if(empty($keys))
            throw new EmptyStringException();

        if($keys == '*')
            return ( $this->options = [] ) ? true : true ;

        // fix string key send to remove
        if(!is_array($keys))
            $keys = [ $keys ];

        // check for Existing keys
        $this->containsKey($keys);

        foreach($keys as $key)
            unset( $this->options [ $key ] );

        return true;

    }


    /**
     * Checks whether element(s) is contained in the options.
     *
     * @param array | string $keys
     * @return bool TRUE if the options contains the elements, FALSE otherwise.
     *
     * @throws EmptyStringException
     * @throws NotValidStringException
     */
    public function contains($keys)
    {
        if( empty( $keys ) )
            throw new EmptyStringException();

        if( !is_string( $keys ) )
            throw new NotValidStringException();

        foreach($keys as $key){
            if(!$this->validInput($key))
                throw new NotValidStringException($key);

            if( ! array_key_exists($key, $this->options) )
                return false;
        }

        return true;
    }

    /**
     * Checks whether options contains an element with the specified key.
     *
     * @param array | string $keys The key(s) to check for.
     *
     * @throws AccessNonValidKeyException
     * @throws EmptyStringException
     * @throws NotValidInputException
     * @throws NotValidStringException
     * @internal param string $key The key(s) to check for.
     */
    protected function containsKey($keys)
    {
        if( empty( $keys ) )
            throw new EmptyStringException();

        if( !is_array($keys)){
            if(!is_string( $keys ) ){
                throw new NotValidStringException();
            }
            throw new NotValidInputException();
        }

        foreach($keys as $key){
            if(!$this->validInput($key))
                throw new NotValidStringException($key);

            if( ! array_key_exists($key, $this->options) )
                throw new AccessNonValidKeyException($key);
        }

    }

    /**
     * Gets the value at the specified key.
     * Example :
     * '*' |'Key' |['Key1','Key2']
     *
     * @param string | array $key The key of option to retrieve.
     * @param string | array | null $excepts keys that must not return
     * @return array|string
     * @throws AccessNonObjectException
     * @throws EmptyStringException
     * @throws NotValidStringException
     */
    public function get($key='*', $excepts = null)
    {
        if( empty( $key ) )
            throw new EmptyStringException();

        if(!is_null($excepts) && !is_array($excepts))
            $excepts = [ $excepts ];

        if($key == '*' || is_array($key))
            return $this->getValue($key, $excepts);

        if( !is_string( $key ) )
            throw new NotValidStringException();

        if(!$this->validInput($key))
            throw new NotValidStringException($key);

        if( !isset( $this->options [ $key ] ) )
            throw new AccessNonObjectException();

        // if key exist in excepts array
        if(!is_null($excepts))
            foreach( $excepts as  $except )
                if($key == $except)
                    return false;

        return  $this->options [ $key ];
    }

    /**
     * Gets all keys of options.
     *
     * @return array The keys of options.
     */
    public function getKeys()
    {
        return array_keys( $this->options );
    }

    /**
     * Gets all values of selected keys the options.
     * input example :
     * '' | '*' | 'Key' | ['Key1', 'Key2']
     *
     * @param null | string $keys
     * @param string | array | null $excepts keys that must not return
     * @return array The values of all options.
     *
     * @throws AccessNonObjectException
     * @throws AccessNonValidKeyException
     * @throws EmptyStringException
     * @throws NotValidStringException
     */
    protected function getValue( $keys = '*', $excepts = null )
    {
        if( $keys == '*' )
            return $this->options;

        $this->containsKey($keys);

        if( is_string($keys) )
            return [ $keys => $this->get($keys) ];

        $response = [];
        foreach($keys as $key){
            $response [ $key ] = $this->get($key);
        }


        if(!is_null($excepts))
            foreach( $excepts as  $except )
                if(array_key_exists($except,$response))
                    unset($response [ $except ] );

        return $response;
    }

    /**
     * Sets an option in options at the specified key.
     *
     * @param string $key The key of option to set.
     * @param mixed $value value of option to set.
     *
     * @throws EmptyStringException
     * @throws NotValidInputException
     * @throws NotValidStringException
     */
    public function set($key, $value)
    {

        if( empty( $key ) )
            throw new EmptyStringException();

        if( !is_string( $key ) )
            throw new NotValidStringException();

        if( !is_string( $value ) && !is_array( $value ) )
            throw new NotValidInputException();

        if(!$this->validInput($key))
           throw new NotValidStringException($key);

        $this->options [ $key ] = $value;

    }


    /**
     * check for valid alpha numeric input
     *
     * @param string $key
     * @return bool
     */
    protected function validInput($key)
    {
        if(ctype_alnum($key))
            return true;

        return false;
    }

}

