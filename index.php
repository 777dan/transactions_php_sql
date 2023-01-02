<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "products_23_2";
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "CREATE DATABASE $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
    $conn = new mysqli($servername, $username, $password, $dbname);
} else {
    echo "Error creating database: " . $conn->error;
}
$conn->query("CREATE TABLE products (product_name VARCHAR(255), product_price INT, product_sales INT);");
mysqli_query ($conn, "START TRANSACTION" );

$sql1 = "INSERT INTO products (product_name, product_price, product_sales)
 VALUES ('Стул', 100, 14);";
$sql2 = "INSERT INTO products (product_name, product_price, product_sales)
 VALUES ('Стол', 400, 17);";

if (mysqli_query ( $conn, $sql1 ) && mysqli_query ( $conn, $sql2 )) {
	mysqli_query ( $conn, "COMMIT" );
	echo "COMMIT";
} else {
	mysqli_query($conn, "ROLLBACK");
	echo"ROLLBACK";
}
try {
    echo "<p>Вывод суммы товаров:</p>";
    $result2 = $conn->query("SELECT SUM(product_price) AS totalSum FROM products;");
    while ($row2 = $result2->fetch_assoc()) {
        print "<p>" . $row2["totalSum"] . "</p>";
    }


    $result1 = $conn->query("SELECT * FROM products WHERE product_sales=(SELECT MAX(product_sales) FROM products);");
    print "<br><p>Вывод максимальных продаж:</p><br><table><tr><th>Наименование товара</th><th>Цена</th><th>Продажи</th></tr>";
    while ($row1 = $result1->fetch_assoc()) {
        print "<tr><td>" . $row1["product_name"] . "</td><td>" . $row1["product_price"] . "</td><td>" . $row1["product_sales"] . "</td></tr>";
    }
    echo "</table><br>";


    $result3 = $conn->query("SELECT * FROM products ORDER BY product_name ASC;");
    print "<br><p>Рассортировать в алфавитном порядке по наименованию товара:</p><br><table><tr><th>Наименование товара</th><th>Цена</th><th>Продажи</th></tr>";
    while ($row3 = $result3->fetch_assoc()) {
        print "<tr><td>" . $row3["product_name"] . "</td><td>" . $row3["product_price"] . "</td><td>" . $row3["product_sales"] . "</td></tr>";
    }
    echo "</table><br>";

    $result4 = $conn->query("SELECT * FROM products WHERE product_sales > 10 AND product_price BETWEEN 100 AND 400;");
    print "<br><p>Вывод товаров, продажи которых больше определенного значения, а цена находится в определенном диапазоне:</p><br><table><tr><th>Наименование товара</th><th>Цена</th><th>Продажи</th></tr>";
    while ($row4 = $result4->fetch_assoc()) {
        print "<tr><td>" . $row4["product_name"] . "</td><td>" . $row4["product_price"] . "</td><td>" . $row4["product_sales"] . "</td></tr>";
    }
    echo "</table><br>";

    mysqli_close($conn);
} catch (Exception $e) {
    $e->getMessage();
}

mysqli_close ( $conn );
// DROP DATABASE products_23_2