<?php

namespace Gateways\Gateway\Interfaces;


interface iStorage
{

    /**
     * load data from storage
     *
     * @param integer $id
     * @return mixed
     */
    public function load($id);

    /**
     * receive inputs and save it to storage .
     *
     * @param $inputs
     * @return integer $id saved data identifier
     */
    public function save($inputs);

}