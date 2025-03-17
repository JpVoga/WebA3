<?php
    function getBuyPageUrlForProduct(int $productId)
    {
        return (
            (empty($_SERVER["HTTPS"])? "http":"https") .
            "://" .
            $_SERVER["HTTP_HOST"] .
            "/WebA3/buy/buy.php?" .
            http_build_query(array("product" => $productId))
        );
    }

    function validateCardNumber(string $cardNumber)
    {
        return (
            (strlen($cardNumber) >= MIN_CARD_NUMBER_DIGITS) &&
            (strlen($cardNumber) <= MAX_CARD_NUMBER_DIGITS) &&
            (ctype_digit($cardNumber))
        );
    }
?>