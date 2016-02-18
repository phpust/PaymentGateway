<?php

namespace Gateways\Gateway\Interfaces;


interface iGateway
{

    /**
     *
     * @param $element
     * @return $token string
     */
    public function request($element);

    public function verify($element);

}