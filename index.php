<?php
include '../includes/dbConnection.php';


$dbConn = getDatabaseConnection('toolRental');

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
                   $sql = $sql . " AND name LIKE  :name ORDER BY name"; //using Named Parameters to prevent SQL Injection
                   
                   if(isset($_GET['sortBy'])){
                            $sql .= " DESC";
                        }
                   
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
                
               // if(isset($_GET['sortBy'])){
                 //   $sql .= " ORDER BY name DESC";
                //}
            }
            
      $statement= $dbConn->prepare($sql); 
      $statement->execute($namedParameters); //Always pass the named parameters, if any
      $records = $statement->fetchALL(PDO::FETCH_ASSOC);  
      
      //print_r($records);
    
      foreach($records as $record) {
          echo "<table border='1px'><tr><td id='frst'>";
          echo "<input type='checkbox' name='cart[]'   value =" . $record['tool_id'] . ">"."</td>";
          
          echo "<td id='second'>".$record['name'] . "</td><td id='third'>". $record['price_total'] .  "</td><td id='fourth'>". $record['status'] . "<br/> "."</td>";
          echo "</tr><table>";
      }
      
   
    
}


?>

<!DOCTYPE html>
<html>
    <head>
        <title> Tool Rental </title>
        <link rel="stylesheet" href="css/project1.css" type="text/css" />
    </head>
    <body>

         <h1> Tool Rental </h1>
         
         <form>
            Order by 
            <input type="checkbox" name="orderBY" value="name">Name
            <input type="checkbox" name="orderBY" value="price_total">Price
            <input type="checkbox" name="orderBY" value="status">Availability
            <br>
           Search For Tool Name <input type="text" name="name"/>
           <input type="checkbox" name="sortBy" value="desc">Sort Descending
            <br>
            <input type="submit" name="submit">

         </form>

         <br /><hr><br />
         
        <!-- action="displayCart.php" -->
          <?=displayDevices()?>  

           <!--  <input type="submit" value="Continue"> -->
          
         
        
         

    </body>
</html>