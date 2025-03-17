<?php
    include_once("../libs/consts.php");
    include_once("../libs/database.php");
    include_once("../libs/util.php");


    function buyFailed(string $errorMsg)
    {
        $_SESSION["buyProductInputError"] = $errorMsg;
        if (empty($_POST["product"])) header("Location:../home/home.php"); 
        else header("Location:" . getBuyPageUrlForProduct($_POST["product"]));
        die();
    }


    session_start();

    foreach ($_POST as $key => $value)
    {
        if (empty($value)) buyFailed("Todos os campos devem ser preenchidos.");
    }

    $productId = $_POST["product"];
    $paymentMethod = $_POST["paymentMethod"];
    $paymentKey = $_POST["paymentKey"];
    $country = $_POST["country"];
    $state = $_POST["state"];
    $city = $_POST["city"];
    $road = $_POST["road"];
    $house = $_POST["house"];

    if (str_contains(strtolower($paymentMethod), "card") && (!(validateCardNumber($paymentKey))))
    {
        buyFailed("Número de cartão inválido.");
    }

    try
    {
        $database = createDatabaseConnection();

        $insertAddressQuery = "INSERT INTO addresses(country, state, city, road, house) VALUE(";
        $insertAddressQuery .= "\"" . filterQuotesFromString($country) . "\", ";
        $insertAddressQuery .= "\"" . filterQuotesFromString($state) . "\", ";
        $insertAddressQuery .= "\"" . filterQuotesFromString($city) . "\", ";
        $insertAddressQuery .= "\"" . filterQuotesFromString($road) . "\", ";
        $insertAddressQuery .= "\"" . filterQuotesFromString($house) . "\"";
        $insertAddressQuery .= ")";
        $result = $database->query($insertAddressQuery);
        $addressId = mysqli_insert_id($database); // THIS FUNCTION ONLY WORKS ON AUTO_INCREMENT TABLES!!!!!

        $insertSaleQuery =
            "INSERT INTO sales(paymentKey, paymentMethod, userId, productId, deliveryAddressId) VALUE(";
        $insertSaleQuery .= "\"" . filterQuotesFromString($paymentKey) . "\", ";
        $insertSaleQuery .= "\"" . filterQuotesFromString($paymentMethod) . "\", ";
        if (empty($_SESSION["user"])) $insertSaleQuery .= "NULL, ";
        else $insertSaleQuery .= (string)$_SESSION["user"]["id"] . ", ";
        $insertSaleQuery .= "{$productId}, ";
        $insertSaleQuery .= "{$addressId})";
        $database->query($insertSaleQuery);

        header(("Location:../buyDone/buyDone.php?" . http_build_query(array("product" => $productId))));
    }
    catch (mysqli_sql_exception $e) {buyFailed("Falha ao conectar ao servidor.");}
?>