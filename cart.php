 <?php
session_start();
include '../includes/dbConnection.php';


$dbConn = getDatabaseConnection('toolRental');

if (!isset($_SESSION['cart'])){
    $_SESSION['cart'] = array();
}
    
$cart = $_GET['cart'];

foreach($cart as $tool){
    $_SESSION['cart'][] = $tool;
    echo $tool;
    
}

if (isset($_GET['clearHistoryForm'])){
    unset($_SESSION['cart']);
    header("Location: index.php");
}

if (isset($_GET['goBack'])){
    header("Location: index.php");
}

function getTool($id){
    global $dbConn;
    
    $sql = "SELECT * FROM tools
    WHERE tool_id ='".$id."'";
    
    $statement= $dbConn->prepare($sql); 
    $statement->execute();
    $records = $statement->fetch(PDO::FETCH_ASSOC);

    return $records;
}

function displayCart(){
    $price = 0;
    $records;
    global $cart;

    echo "<h1 class='center'>Cart</h1>";
    
    echo "<table border = 1>";
    echo "<tr>";
        echo "<td colspan='3'>Cart</td>";
    echo "</tr>";
    echo "<tr>";
        echo "<td>Product ID</td>";
        echo "<td>Product Name</td>";
        echo "<td>Price</td>";
    echo "</tr>";
    foreach($_SESSION['cart'] as $tool){
        $record = getTool($tool);
        $price += $record['price_total'];
        
        echo "<tr>";
            echo "<td>".$record['tool_id']."</td>";
            echo "<td>".$record['name']."</td>";
            echo "<td>$".$record['price_total']."</td>";
        echo "</tr>";
        
    }
    echo "<tr>";
        echo "<td colspan = '2'>Total Price:</td>";
        echo "<td colspan = '1'>$".$price."</td>";
    echo "</tr>";
    echo "</table>";
} 
    

displayCart();


    
    ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Shopping Cart</title>
        <link type="text/css" rel="stylesheet" href="css/project1.css">
    </head>
    <body>       
        <form >     
			<input type="submit" value="Clear Cart" name="clearHistoryForm" />
			    
		</form>
			    
	    <form action="index.php">     
			<input type="submit" value="Continue Shopping" name="goBack" />
		</form>
    </body>
</html>



