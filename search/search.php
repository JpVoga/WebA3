<?php
    include_once("../libs/consts.php");
    include_once("../libs/database.php");
    include_once("../libs/util.php");


    function echoSearchResult(array $productData)
    {
        $filteredProductName = htmlentities($productData["name"]);
        $filteredProductDescription = htmlentities($productData["description"]);
        $filteredProductImagePath = "../{$productData["imagePath"]}";
        $filteredProductPrice = number_format($productData["price"], 2);
        $buyProductPageUrl = getBuyPageUrlForProduct($productData["id"]);

        echo "
            <div class=\"searchResultArea productArea\">
                <a class=\"buyProductLink noDecorationLink\" href=\"{$buyProductPageUrl}\" target=\"_self\">
                    <header class=\"searchResultHeader\">
                        {$filteredProductName}
                    </header>

                    <div class=\"searchResultImageArea\">
                        <img class=\"searchResultImage\" src=\"{$filteredProductImagePath}\">
                    </div>

                    <div class=\"searchResultDescriptionArea\">
                        <span class=\"searchResultPrice\">R\${$filteredProductPrice}</span>
                        <p class=\"searchResultProductDescription\">{$filteredProductDescription}</p>
                    </div>
                </a>
            </div>
        ";
    }

    function getUrlForSearchPage(int $pageNum)
    {
        $hypertextProtocolText = empty($_SERVER["HTTPS"])? "http":"https";
        $searchPageUrl = "{$hypertextProtocolText}://{$_SERVER['HTTP_HOST']}/WebA3/search/search.php";

        $targetPageGetValues = $_GET;
        $targetPageGetValues["page"] = (string)$pageNum;

        return $searchPageUrl . "?" . http_build_query($targetPageGetValues);
    }


    session_start();

    $search = ((empty($_GET["search"]))? "":(string)$_GET["search"]);
    $page = ((empty($_GET["page"]))? 1:(int)$_GET["page"]);
    $user = ((empty($_SESSION["user"]))? null:(array)$_SESSION["user"]);
    $searchError = "";

    if (empty($search))
    {
        header("Location:../home/home.php");
        die();
    }

    try
    {
        $database = createDatabaseConnection();
        $searchFilteredQuotes = filterQuotesFromString($search);

        if (empty($user))
        {
            $database->query(
                "INSERT INTO searches(input, userId) VALUE(\"{$searchFilteredQuotes}\", NULL)"
            );
        }
        else
        {
            $database->query(
                "INSERT INTO searches(input, userId) VALUE(\"{$searchFilteredQuotes}\", {$user['id']})"
            );
        }

        $productsResult = selectSimilarProducts($database, $search);

        if ($productsResult->num_rows < 1)
        {
            $searchError = "Nenhum resultado para \"" . htmlentities($search) . "\".";
            goto endOfInitialPhpBlock;
        }
    }
    catch (mysqli_sql_exception $e)
    {
        $searchError = "Falha ao conectar ao servidor.";
        goto endOfInitialPhpBlock;
    }

    endOfInitialPhpBlock: ;
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>A3: <?= htmlentities($search) ?></title>

        <link rel="stylesheet" href="../style/style.css?<?= getCacheTime() ?>">
    </head>

    <body>
        <?php include("../header/header.php"); ?>

        <div id="searchResultsArea" class="postPageHeader"><?php
            if (!empty($searchError))
            {
                echo "<label id=\"searchErrorDisplay\" class=\"errorDisplay\">{$searchError}</label>";
            }
            elseif (!empty($productsResult))
            {
                $productsResult->data_seek(($page - 1) * MAX_SEARCH_RESULTS_IN_PAGE);

                for ($i=0; $i < MAX_SEARCH_RESULTS_IN_PAGE; $i++)
                {
                    $product = $productsResult->fetch_assoc();
                    if (!$product) break;
                    else echoSearchResult($product);
                }
            }
        ?></div>

        <?php // Echoing navigation area into the DOM if there are too many results for one search page
            if (!empty($productsResult))
            {
                if ($productsResult->num_rows > MAX_SEARCH_RESULTS_IN_PAGE)
                {
                    $productsResult->data_seek(0);
                    $pageCount = ((int) (ceil(((float) $productsResult->num_rows) / MAX_SEARCH_RESULTS_IN_PAGE)));

                    echo "<nav id=\"searchResultPageNavigationArea\"><ol id=\"searchResultPageLinkList\">";

                    for ($i=1; $i <= $pageCount; $i++)
                    {
                        $targetUrl = getUrlForSearchPage($i);

                        echo "
                            <li class=\"searchResultPageLinkListItem yellowInteractable\">
                                <a
                                    class=\"searchResultPageLink noDecorationLink\"
                                    href=\"{$targetUrl}\"
                                    target=\"_self\"
                                >
                                    {$i}
                                </a>
                            </li>
                        ";
                    }

                    echo "</ol></nav>";
                }
            }
        ?>

        <?php include("../footer/footer.php"); ?>

        <script><?php
            if (!empty($searchFilteredQuotes)) echo "searchInput.value = \"{$searchFilteredQuotes}\";";
        ?></script>
    </body>
</html>