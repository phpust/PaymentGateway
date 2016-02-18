<?php

namespace Gateways\Connectors\Interfaces;


interface iOption
{

    /**
     * Checks whether an element is contained in the options.
     *
     * @param mixed $element The element to search for.
     *
     * @return boolean TRUE if the options contains the element, FALSE otherwise.
     */
    public function contains($element);

    /**
     * Checks whether the options is empty (contains no options).
     *
     * @return boolean TRUE if the collection is empty, FALSE otherwise.
     */
    public function isEmpty();

    /**
     * Removes the option from options.
     *
     * @param string $key The kex of the option to remove.
     *
     * @return boolean.
     */
    public function remove($key);

    /**
     * Gets the value at the specified key.
     *
     * @param string $key The key of option to retrieve.
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Gets all keys of options.
     *
     * @return array The keys of options.
     */
    public function getKeys();

    /**
     * Sets an option in options at the specified key.
     *
     * @param string $key    The key of option to set.
     * @param mixed $value   value of option to set.
     *
     * @return void
     */
    public function set($key, $value);

}