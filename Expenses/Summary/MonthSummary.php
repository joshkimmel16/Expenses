<html>
<head>
	<title>Month Summary</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" href="MonthSummary.css" />
</head>
<body>
	<?php
        
        if (isset($_GET['date'])) 
        {
            $d = $_GET['date'];
            
            //extract the date, testing for valid format
            $pattern = htmlentities("^(\d{2})(\d{4})$");
            preg_match("#".$pattern."#", $d, $dateparts);
            
            //if the date parameter didn't match the regex, it must be invalid
            if (count($dateparts) == 0)
            {
                echo "<h1>Invalid Date Parameter in URL<h1>";
            }
            else
            {   
                $month = $dateparts[1];
                $year = $dateparts[2];
                $englishmonth = "";
                
                //get english version of month from the URL
                switch ($month)
                {
                    case '01':
                        $englishmonth = "January";
                        break;
                    case '02':
                        $englishmonth = "February";
                        break;
                    case '03':
                        $englishmonth = "March";
                        break;
                    case '04':
                        $englishmonth = "April";
                        break;
                    case '05':
                        $englishmonth = "May";
                        break;
                    case '06':
                        $englishmonth = "June";
                        break;
                    case '07':
                        $englishmonth = "July";
                        break;
                    case '08':
                        $englishmonth = "August";
                        break;
                    case '09':
                        $englishmonth = "September";
                        break;
                    case '10':
                        $englishmonth = "October";
                        break;
                    case '11':
                        $englishmonth = "November";
                        break;
                    default:
                        $englishmonth = "December";
                        break;
                }
                
                //start the html
                $html = "<h1>Expenses for ".$englishmonth.", ".$year."</h1><table class=\"table\"><thead><tr><th>Bucket</th><th>Amount</th><th>Description</th><th>Date</th></tr></thead><tbody>";
                $check = false;
                
                //query sql for expense data given the date
                $username = "root";
                $password = "root";
                $db = new PDO("mysql:host=localhost;dbname=SandBox", $username, $password);

                
                $stmt = $db->query('SELECT * FROM Expense WHERE MONTH(Date)='.$month.' AND YEAR(Date)='.$year.';');
                
                //add each row returned to the html table
                while ($rows = $stmt->fetch())
                {   
                    $check = true;
                    
                    $newhtml = "<tr><td>".$rows['Bucket']."</td><td>$".$rows['Amount']."</td><td>".$rows['Description']."</td><td>".$rows['Date']."</td></tr>";
                    
                    $html = $html.$newhtml;
                }
                
                //if no rows were returned by the query, convey this to the user
                if (!$check)
                {
                    echo "<h1>No Expenses Found for the Date Provided!</h1>";
                }
                //write html table to the document
                else
                {
                    $endhtml = "</tbody></table>";
                    $html = $html.$endhtml;
                    
                    echo $html;
                }
            }
        }
        //there was no date parameter in the URL
        else
        {
            echo "<h1>Error Retrieving Date Parameter in URL<h1>";
        }    
    
    ?>
</body>
</html>