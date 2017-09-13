<?php

function get_shipping($total = 0) {
// Set the base handling charges:
    $shipping = 3;
// Rate is based upon the total:
    if ($total < 10) {
        $rate = .25;
    } elseif ($total < 20) {
        $rate = .20;
    } elseif ($total < 50) {
        $rate = .18;
    } elseif ($total < 100) {
        $rate = .16;
    } else {
        $rate = .15;
    }
// Calculate the shipping total:
    $shipping = $shipping + ($total * $rate);
// Return the shipping total:
    return $shipping;
} // End of get_shipping() function.


?>