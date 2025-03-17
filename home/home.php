<?php
    include_once("../libs/consts.php");
    include_once("../libs/database.php");
    include_once("../libs/util.php");


    session_start();

    $productsFeedError = "";
    $userId = (empty($_SESSION["user"])? null:$_SESSION["user"]["id"]);
    $productIdPool = array();

    try
    {
        $database = createDatabaseConnection();

        $userInterestsResult = selectUserInterests($database, $userId);
        $userInterestsIndex = 0;

        $productsTableResult = $database->query("SELECT * FROM products ORDER BY RAND()");
        $productsTableIndex = 0;

        while (count($productIdPool) < HOME_PAGE_RECOMENDATIONS)
        {
            $randNum = (((float) rand(0, 100)) / 100.0);
            if (($randNum <= RANDOM_RECOMENDATION_CHANCE) || ($userInterestsIndex >= $userInterestsResult->num_rows))
            {
                while ($productsTableIndex < $productsTableResult->num_rows)
                {
                    $productsTableResult->data_seek($productsTableIndex);
                    $productId = $productsTableResult->fetch_assoc()["id"];
                    $productsTableIndex++;

                    if (!in_array($productId, $productIdPool))
                    {
                        array_push($productIdPool, $productId);
                        break;
                    }
                }
            }
            else
            {
                $userInterestName = $userInterestsResult->fetch_assoc()["name"];
                $userInterestsIndex++;
                $similarProductsResult = $database->query(
                    getSelectSimilarProductsQuery($userInterestName) . "ORDER BY RAND()"
                );

                while ($productData = $similarProductsResult->fetch_assoc())
                {
                    $productId = $productData["id"];
                    if (!in_array($productId, $productIdPool))
                    {
                        array_push($productIdPool, $productId);
                        break;
                    }
                }
            }

            if (
                ($userInterestsIndex >= $userInterestsResult->num_rows) &&
                ($productsTableIndex >= $productsTableResult->num_rows))
            {
                break; // Break if all the products have been taken
            }
        }

        $products = array();
        foreach ($productIdPool as $key => $value)
        {
            array_push($products, $database->query("SELECT * FROM products WHERE id = {$value}")->fetch_assoc());
        }
    }
    catch (Throwable $th)
    {
        $productsFeedError = "Falha ao conectar ao servidor.";
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>A3 Ecommerce</title>

        <link rel="stylesheet" type="text/css" href="../style/style.css?<?php echo getCacheTime();?>">
        <style><?php
            if (!empty($productsFeedError)) echo "#productsFeedArea {display: block;}";
        ?></style>
    </head>

    <body>
        <?php include("../header/header.php"); ?>

        <div id="productsFeedArea" class="postPageHeader"><?php
            if (!empty($productsFeedError))
            {
                echo "<span class=\"errorDisplay\">{$productsFeedError}</span>";
            }
            else
            {
                foreach ($products as $product)
                {
                    $buyProductPageUrl = getBuyPageUrlForProduct($product["id"]);
                    $filteredProductName = htmlentities($product["name"]);
                    $formatedProductPrice = number_format($product["price"], 2);
                    $filteredProductDescription = htmlentities($product["description"]);

                    echo "
                        <div class=\"productRecomendationArea productArea\">
                            <a
                                class=\"buyProductLink noDecorationLink\"
                                href=\"{$buyProductPageUrl}\"
                                target=\"_self\"
                            >
                                <div class=\"productRecomendationImageArea\">
                                    <img class=\"productRecomendationImage\" src=\"../{$product["imagePath"]}\">
                                </div>

                                <span class=\"productRecomendationName\">{$filteredProductName}: </span>
                                <span class=\"productRecomendationPrice\">R\${$formatedProductPrice}</span>

                                <p>{$filteredProductDescription}</p>
                            </a>
                        </div>
                    ";
                }
            }
        ?></div>

        <?php include("../footer/footer.php"); ?>
    </body>
</html>