USE webA3;

	(SELECT input AS "name", moment
	FROM searches
	WHERE userId = 1) -- Selecting searches
UNION ALL
	(SELECT products.keyword as "name", sales.moment as "moment"
	FROM products, sales
	WHERE
		sales.productId = products.id AND
		userId = 1) -- Selecting products
ORDER BY moment DESC;