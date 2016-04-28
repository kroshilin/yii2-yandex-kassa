<?php
/**
 * Created by PhpStorm.
 * User: krosh
 * Date: 25.04.2016
 * Time: 21:17
 */

namespace kroshilin\yakassa;

interface CustomerInterface
{
    /**
     * Should return unique user identificator in your customer table
     * @return integer||string
     */
    public function getCustomerId();

    /**
     * Should return customer email, if exists
     * Would be used to generate payment form
     * @return string
     */
    public function getCustomerEmail();

    /**
     * Should return customer phone
     * Used to generate payment form
     * @return string
     */
    public function getCustomerPhone();
}