<?php
include '../includes/dbConnection.php';


$dbConn = getDatabaseConnection('toolRental');

function insertCheckBoxes($record){
    echo "<input type='checkbox' name='cart[]' value=''>";
}

function getToolInfo($records){
    global $dbConn;
    $toolInfo = array();
    
    foreach($records as $record){
    
        $toolInfo[] = array("name"=>$record['name'],"price"=>$record['price_total'],"stat"=>$record['status'], "id"=>$record['tool_id'], "desc"=>$record['descripion']);
    }
    return $toolInfo;
}

function drawTable($records){
    $count = 0;
    $info = getToolInfo($records);
    
    
    echo"<div id='pop' class='popup'>
            <form stlye='margin:0 auto;text-align:center'>
                <input type='submit' value='close' onlick='closePopup()'>
            </form>
            <h3 style='text-align:center'>Product Info </h3>
            <span id='name' style='text-align:center'></span><br/>
            <span id='price' style='text-align:center'></span><br/>
            <span id='stat' style='text-align:center'></span><br/>
            <span id='toolId' style='text-align:center'></span><br/>
            <span id='desc1' style='text-align:center'></span><br/>
        </div>";
        
    echo "<div id='bg' class='blackBackground'></div>";
    
    echo "<form method='get' action='cart.php'><table style='text-align:center;margin:0 auto;border:1px solid black'>";
    echo "<tr style='border:1px solid black'>";
    echo "<td style='border:1px solid black'>Add to cart</td>";
    echo "<td style='border:1px solid black'>Tool Name</td>";
    echo "<td style='border:1px solid black'>Price</td>";
    echo "<td style='border:1px solid black'>Availability</td>";
    echo "<td style='border:1px solid black'>More info</td>";
    echo "</tr>";
foreach($records as $record){
    echo "<tr style='border:1px solid black'>";
        
        echo "<td style='border:1px solid black'><input type='checkbox' name='cart[]' value=".$record['tool_id']."></td>";
        echo "<td style='border:1px solid black'>".$record['name']."</td>";
        echo "<td style='border:1px solid black'>$".$record['price_total']."</td>";
        echo "<td style='border:1px solid black'>".$record['status']."</td>";
        echo "<td style='border:1px solid black'><button type='button' onclick='var info=".json_encode($info[$count]).";displayPopup(info);'>More Info</button></td>";

    echo "</tr>";
    $count++;
} 
    echo "</table>";
}

function getDeviceTypes(){
    global $dbConn;
    $sql = "SELECT DISTINCT(deviceType) 
            FROM device 
            ORDER BY deviceType" ;
            
      $statement= $dbConn->prepare($sql); 
      $statement->execute();
      $records = $statement->fetchALL(PDO::FETCH_ASSOC);  
      
      //print_r($records);
      
      foreach($records as $record) {
          echo "<option value='" . $record['deviceType'] . "'>" . $record['deviceType'] . "</option>";
      }
            
            
}

function displayDevices() {
    global $dbConn;
    $sql = "SELECT * 
            FROM tools 
            WHERE 1 " ;  //Getting all records 
            
            if (isset($_GET['submit'])){
            //form has been submitted

                $namedParameters = array();
                
                
                
                 
                if (!empty($_GET['name'])){
                    //deviceName has some value
                    
                    // Following sql works but it doesn't prevent SQL INJECTION
                   //  $sql = $sql . " AND deviceName LIKE  '%" . $_GET['deviceName'] . "%'";
                   $sql = $sql . " AND name LIKE  :name "; //using Named Parameters to prevent SQL Injection
                   
                   $namedParameters[':name'] = "%" . $_GET['name'] . "%";
                   
                }
                /*
                
                if(isset($_GET['available'])){
                    $sql = $sql . " AND status = :status";
                    $namedParameters[':status'] = "available";
                }
                */
                
               if(isset($_GET['orderBY']))
                {
                    if($_GET['orderBY']==name)
                    {
                        $sql .= " ORDER BY name";
                        if(isset($_GET['sortBy'])){
                            $sql .= " DESC";
                        }
                    }
                    if($_GET['orderBY']==price_total)
                    {
                        $sql .= " ORDER BY price_total";
                        if(isset($_GET['sortBy'])){
                            $sql .= " DESC";
                        }
                    }
                     if($_GET['orderBY']==status)
                    {
                        $sql .= " ORDER BY status";
                        if(isset($_GET['sortBy'])){
                            $sql .= " DESC";
                        }
                    }
                }
                /*
                if(isset($_GET['price']))
                {
                   $sql = $sql . " ORDER BY price_total";
                }
                
                if(isset($_GET['status']))
                {
                    $sql = $sql . " ORDER BY status DESC";
                }*/
                
            
            }
            
      $statement= $dbConn->prepare($sql); 
      $statement->execute($namedParameters); //Always pass the named parameters, if any
      $records = $statement->fetchALL(PDO::FETCH_ASSOC);  
      
      //print_r($records);
     /*
      foreach($records as $record) {
          echo "<table border='1px'><tr><td id='frst'>";
          echo "<input type='checkbox' name='cart[]'   value =" . $record['tool_id'] . ">"."</td>";
          
          echo "<td id='second'>".$record['name'] . "</td><td id='third'>". $record['price_total'] .  "</td><td id='fourth'>". $record['status'] . "<br/> "."</td>";
          echo "</tr><table>";
      }*/
      drawTable($records);
      
   
    
}


?>

<!DOCTYPE html>
<html>
    <head>
        <title>Tool Rental</title>
         <link type="text/css" rel="stylesheet" href="css/project1.css">
         <script src="Project1.js"></script>
    </head>
    <body>
         <h1 id="title"> Tool Rental </h1>
         
         <form>
            Search For Tool Name <input type="text" name="name"/>
            Order by 
            <input type="checkbox" name="orderBY" value="name">Name
            <input type="checkbox" name="orderBY" value="price_total">Price
            <input type="checkbox" name="orderBY" value="status">Availability
           <input type="checkbox" name="sortBy" value="desc"/> Sort Descending 
            <input type="submit" name="submit">

         </form>
         
         <form id="cartBtn" action="cart.php">
             <input type="submit" value="View Cart" name="viewCart">
         </form>

         <br /><hr><br />
          <?=displayDevices();
            $cart = $_GET['cart'];
            print_r($cart);?>  
       <form method="get" action="cart.php">
          
           <br />
          <input type="submit" value="Continue" name="checkout">
        </form>  
        

    </body>
</html>