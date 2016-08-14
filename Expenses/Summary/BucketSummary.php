<html>
<head>
	<title>Bucket Summary</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" href="BucketSummary.css" />
</head>
<body>
    <?php
        
        if (isset($_GET['bucket'])) 
        {
            $b = $_GET['bucket'];
            
            $html = "<h1>".$b." Bucket Summary</h1><table class=\"table\"><thead><tr><th>Average Purchase</th><th>Standard Deviation</th><th>Max Purchase</th><th># of Purchases</th></tr></thead><tbody>";
            
            //query sql for expense data given the date
            $username = "root";
            $password = "root";
            $db = new PDO("mysql:host=localhost;dbname=SandBox", $username, $password);


            $stmt = $db->query('SELECT AVG(Amount) as average,STDDEV(Amount) as stddev,MAX(Amount) as max,COUNT(Amount) as count FROM Expense WHERE Bucket=\''.$b.'\';');

            //add each row returned to the html table
            $count = 0;
            while ($rows = $stmt->fetch())
            {   
                $newhtml = "<tr><td>$".$rows['average']."</td><td>$".$rows['stddev']."</td><td>$".$rows['max']."</td><td>".$rows['count']."</td></tr>";

                $html = $html.$newhtml;
                
                $count++;
            }
            
            /*if ($count == 0)
            {
                echo "<tr><td class=\'Bad\'>Invalid Bucket</td><td class=\'Bad\'>Invalid Bucket</td></tr>";
            }*/
            
            $endhtml = "</tbody></table>";
            $html = $html.$endhtml;
            
            echo $html;
        }
    
       ?>
</body>
</html>
            