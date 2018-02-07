<?php

$dbConn = new \PDO("mysql:dbname=example;host=127.0.0.1", 'root', '');

/* Query the database for the aggregated unit sales per item in inventory. */
$query = 'SELECT items.id, items.name, items.unit_price, item_counts.total_qty ' .
         'FROM items ' .
           'LEFT JOIN ( ' .
             'SELECT order_items.item_id, sum(order_items.quantity) total_qty ' .
        	 'FROM order_items GROUP BY order_items.item_id ' .
           ') item_counts ON items.id = item_counts.item_id';

($statement = $dbConn->prepare($query))->execute();

/* Fetch rows returned by our query and transform into a JSON response. */
$response = ["items" => []];
foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
    array_push($response['items'], [
        'id' => $row['id'],
        'name' => $row['name'],
        'unit_price' => 0.0 + $row['unit_price'], /* Cast to floating pt number. */
        'qty_sold' => $row['total_qty'],
        'gross_total' => $row['unit_price'] * $row['total_qty']
    ]);
}

/* Return JSON response to the requesting client. */
header("Content-Type: application/json;charset=utf-8");
/* In real production code, would throw error if json_encode returns false. */
echo json_encode($response);
