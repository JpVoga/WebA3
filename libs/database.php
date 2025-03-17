<?php
    include_once("../libs/consts.php");

    function createDatabaseConnection(): mysqli
    {
        $database = new mysqli(DATABASE_IP, DATABASE_USER_NAME, DATABASE_PASSWORD, DATABASE_NAME, DATABSE_PORT);
        $database->query("SET SQL_SAFE_UPDATES = TRUE");
        return $database;
    }

    function filterQuotesFromString($str): string
    {
        if (gettype($str) != "string") return "";
        else return str_replace("'", "\\'", str_replace('"', '\\"', $str));
    }

    function getSelectSimilarProductsQuery(string $search): string
    {
        $searchFilteredForQuery = filterQuotesFromString($search);
        return
            "SELECT * FROM products
            WHERE
                name LIKE \"%{$searchFilteredForQuery}%\" OR
                description LIKE \"%{$searchFilteredForQuery}%\" OR
                imagePath LIKE \"%{$searchFilteredForQuery}%\" OR
                keyword LIKE \"%{$searchFilteredForQuery}%\"";
    }

    function selectSimilarProducts(mysqli $database, string $search)
    {
        return $database->query(getSelectSimilarProductsQuery($search));
    }

    function getSelectUserInterestsQuery(int|null $userId): string
    {
        $userIdQueryCondition = ((is_null($userId))? "TRUE":"userId = {$userId}");

        return "
                (SELECT input AS \"name\", moment
                FROM searches
                WHERE {$userIdQueryCondition}) -- Selecting searches
            UNION ALL
                (SELECT products.keyword as \"name\", sales.moment as \"moment\"
                FROM products, sales
                WHERE
                    sales.productId = products.id AND
                    {$userIdQueryCondition}) -- Selecting products
                ORDER BY moment DESC;
        ";
    }

    function selectUserInterests(mysqli $database, int|null $userId)
    {
        return $database->query(getSelectUserInterestsQuery($userId));
    }
?>