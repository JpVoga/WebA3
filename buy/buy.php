<?php
    include_once("../libs/consts.php");
    include_once("../libs/database.php");


    session_start();

    if (empty($_GET["product"]))
    {
        header("Location:../home/home.php");
        die();
    }

    $productId = ((int) $_GET["product"]);
    $buyError = "";
    $buyProductInputError = "";

    if (!empty($_SESSION["buyProductInputError"]))
    {
        $buyProductInputError = $_SESSION["buyProductInputError"];
        unset($_SESSION["buyProductInputError"]);
    }

    try
    {
        $database = createDatabaseConnection();
        $productResult = $database->query("SELECT * FROM products WHERE id = {$productId}");

        if ($productResult->num_rows < 1)
        {
            $buyError = "Produto não encontrado.";
            goto endOfInitialPhpBlock;
        }

        $product = $productResult->fetch_assoc();
        $productPriceFormated = number_format($product["price"], 2);
    }
    catch (mysqli_sql_exception $e)
    {
        $buyError = "Falha ao conectar ao servidor.";
        goto endOfInitialPhpBlock;
    }

    endOfInitialPhpBlock: ;
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?php
            if (empty($product)) echo "Erro de compra";
            else echo "A3: {$product["name"]}";
        ?></title>

        <link rel="stylesheet" href="../style/style.css?<?= getCacheTime() ?>">
    </head>

    <body>
        <?php include("../header/header.php"); ?>

        <div id="buyProductArea" class="postPageHeader"><?php
            if (!empty($buyError))
            {
                echo "<span id=\"buyErrorDisplay\" class=\"errorDisplay\">{$buyError}</span>";
            }
            elseif (!empty($product))
            {
                echo "
                    <div id=\"buyProductInfoDisplayArea\">
                        <header id=\"buyProductNameDisplay\">{$product["name"]}</header>

                        <div id=\"buyProductImageArea\">
                            <img id=\"buyProductImage\" src=\"../{$product["imagePath"]}\">
                        </div>

                        <div id=\"buyProductPriceDisplay\">R\${$productPriceFormated}</div>

                        <p id=\"buyProductDescription\">{$product["description"]}</p>
                    </div>

                    <div id=\"buyProductInputArea\">
                        <form id=\"buyProductForm\" action=\"../buyRedirect/buyRedirect.php\" method=\"post\">
                            <div id=\"paymentInputArea\">
                                <header id=\"paymentMethodInputAreaHeader\">Informações de pagamento:</header>

                                <div id=\"paymentMethodInputArea\">
                                    <label for=\"paymentMethodSelect\">Método de pagamento: </label>
                                    <select id=\"paymentMethodSelect\" name=\"paymentMethod\">
                                        <option value=\"pix\" selected>Pix</option>
                                        <option value=\"debtCard\">Cartão de débito</option>
                                        <option value=\"creditCard\"> Cartão de crédito</option>
                                    </select>
                                </div>

                                <div id=\"paymentKeyInputArea\">
                                    <label id=\"paymentKeyInputLabel\" for=\"paymentKeyInput\"></label>
                                    <input id=\"paymentKeyInput\" name=\"paymentKey\" type=\"text\">
                                    <span id=\"paymentKeyErrorDisplay\" class=\"errorDisplay\"></span>
                                </div>
                            </div>

                            <div id=\"deliveryAddressInputArea\">
                                <header id=\"deliveryAddressInputAreaHeader\">Local de entrega:</header>

                                <div id=\"countryInputArea\">
                                    <label for=\"countryInput\">País: </label>
                                    <input id=\"countryInput\" name=\"country\" type=\"text\">
                                </div>

                                <div id=\"stateInputArea\">
                                    <label for=\"stateInput\">Estado: </label>
                                    <input id=\"stateInput\" name=\"state\" type=\"text\">
                                </div>

                                <div id=\"cityInputArea\">
                                    <label for=\"cityInput\">Cidade: </label>
                                    <input id=\"cityInput\" name=\"city\" type=\"text\">
                                </div>

                                <div id=\"roadInputArea\">
                                    <label for=\"roadInput\">Rua: </label>
                                    <input id=\"roadInput\" name=\"road\" type=\"text\">
                                </div>

                                <div id=\"houseInputArea\">
                                    <label for=\"houseInput\">Casa: </label>
                                    <input id=\"houseInput\" name=\"house\" type=\"text\">
                                </div>
                            </div>

                            <input name=\"product\" type=\"hidden\" value=\"{$productId}\">

                            <input class=\"yellowInteractable\" type=\"submit\" value=\"COMPRAR!\">

                            <span id=\"buyProductInputErrorDisplay\" class=\"errorDisplay\">
                                {$buyProductInputError}
                            </span>
                        </form>
                    </div>
                ";
            }
        ?></div>

        <?php include("../footer/footer.php"); ?>

        <script><?php
            echo "const MIN_CARD_NUMBER_DIGITS = " . MIN_CARD_NUMBER_DIGITS . ";";
            echo "const MAX_CARD_NUMBER_DIGITS = " . MAX_CARD_NUMBER_DIGITS . ";";
        ?></script>
        <script src="buy.js?<?= getCacheTime() ?>"></script>
    </body>
</html>