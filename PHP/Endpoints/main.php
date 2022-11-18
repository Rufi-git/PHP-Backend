<?php
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST, DELETE');

header('Access-Control-Allow-Headers: X-Requested-With');   

$servername = "fdb29.awardspace.net";
$username = "4200154_templatezor";
$password = "salam123";
$dbname = "4200154_templatezor";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
     // The request is using the POST method
    $sql = "SELECT SKU, Name as name, Price as price, type, type_value as typeValue  FROM Product";
    $result = $conn->query($sql);
    $rows = array();
    while($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }

    print json_encode($rows, JSON_NUMERIC_CHECK);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $sql = "INSERT INTO Product (SKU, Name, Price, type, type_value) VALUES (NULL,`{$_POST['Name']}`, `{$_POST['Price']}`, `{$_POST['type']}`, `{$_POST['type_value']}`)";
    $datas = json_decode(file_get_contents('php://input'), JSON_NUMERIC_CHECK);
    //print($datas);
    $name = $datas["Name"];
    $price = $datas["Price"];
    $type = $datas["type"];
    $type_value = $datas["type_value"];
    $sql = "INSERT INTO `Product` (`SKU`, `Name`, `Price`, `type`, `type_value`) VALUES (NULL, '{$name}', '{$price}', '{$type}', '{$type_value}');";
    
    if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $skus = implode("','", json_decode($_GET['sku'])); 
    $sql = "DELETE FROM Product WHERE `Product`.`SKU` IN ('".$skus."')";
    if ($conn->query($sql) === TRUE) {
      echo "record deleted successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>
