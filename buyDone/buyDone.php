<?php
    include_once("../libs/consts.php");
    include_once("../libs/database.php");
    include_once("../libs/util.php");


    function getBuyDoneMessage($product)
    {
        if (empty($product)) return "Produto comprado com sucesso!";
        else return "Produto \"{$product["name"]}\" comprado com sucesso!";
    }


    session_start();


    $product = null;

    try
    {
        $database = createDatabaseConnection();
        $productId = $_GET["product"];
        $productResult = $database->query("SELECT * FROM products WHERE id = {$productId}");
        $product = (($productResult->num_rows == 1)? ($productResult->fetch_assoc()):null);
    }
    catch (Throwable $th) {$product = null;}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?= getBuyDoneMessage($product); ?></title>

        <link rel="stylesheet" href="../style/style.css?<?= getCacheTime() ?>">
    </head>

    <body>
        <?php include("../header/header.php"); ?>

        <div id="buyDoneContentArea" class="postPageHeader">
            <div id="buyDoneMessage">
                <header id="buyDoneMessageHeader"><?= getBuyDoneMessage($product) ?></header>
                <span id="buyDoneMessageAdditionalText">O produto será entregue no endereço especificado.</span>
            </div>
        </div>

        <?php include("../footer/footer.php"); ?>
    </body>
</html>