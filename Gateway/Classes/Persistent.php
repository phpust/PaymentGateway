<?php

namespace Gateways\Gateway\Classes;


use Gateways\Gateway\Interfaces\iStorage;

class Persistent implements iStorage
{

    /**
     * load data from storage
     *
     * @param integer $id
     * @return mixed
     */
    public function load($id)
    {
        return ['Amount' => 100];
        // TODO: Implement load() method.
    }

    /**
     * receive inputs and save it to storage .

     *
     * @param $inputs
     * @return integer $id saved data identifier
     */
    public function save($inputs)
    {
        // remove empty inputs
        foreach($inputs as $key => $option)
            if(empty($option))
                unset($inputs [ $key ] );

        // TODO: Implement save() method.
        return 13;
    }
}