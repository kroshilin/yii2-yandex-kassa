<?php
/**
 * Created by PhpStorm.
 * User: krosh
 * Date: 25.04.2016
 * Time: 21:17
 */

namespace kroshilin\yakassa;

interface OrderInterface
{
    /**
     * Should return unique orderId
     * Used to generate payment form
     * @return mixed
     */
    public function getId();

    /**
     * Should return total order cost
     * Used to generate payment form
     * @return integer
     */
    public function getTotalPrice();
}