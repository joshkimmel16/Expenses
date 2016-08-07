<html>
<head>
    <title>Budget Totals</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" href="ExpenseSummary.css" />
</head>
<body>
    <h1>Monthly Expenses</h1>
    <p>See below for a monthly summary of expenses by budget bucket.</p>
    <?php
        // Enter username and password
        $username = "root";
        $password = "root";

        // Create database connection using PHP Data Object (PDO)
        $db = new PDO("mysql:host=localhost;dbname=SandBox", $username, $password);

        // Create the query - here we grab everything from the table
        $stmt = $db->query('SELECT * FROM Expense_Type');

        //return array of budget totals for each bucket
        $arr = array();
        $budgetSum = 0;

        while($rows = $stmt->fetch())
        {
            //add buckets/budgets to array
            $arr[$rows['Bucket']] = $rows['Budget'];
            $budgetSum = $budgetSum + $rows['Budget'];
        }
    
        
        // Create the query - here we grab everything from the table
        $stmt1 = $db->query('SELECT Bucket as Bucket, SUM(Amount) as Total, YEAR(Date) as Year, MONTH(Date) as Month from Expense GROUP BY YEAR(Date), MONTH(Date), Bucket ORDER BY Year desc, Month desc;');
    
        //holders for various html data
        $currentTable = ""; //title of the current table
        $currentHead = ""; //header html for the current table
        $currentBody = ""; //body html for the current table
        $currentSum = 0; //total expenses for the current table
    
        while($rows = $stmt1->fetch())
        {
            //call a function to map enum month to english month
            $month = "";
            
            switch ($rows['Month'])
            {
                case 1:
                    $month = "January";
                    break;
                case 2:
                    $month = "February";
                    break;
                case 3:
                    $month = "March";
                    break;
                case 4:
                    $month = "April";
                    break;
                case 5:
                    $month = "May";
                    break;
                case 6:
                    $month = "June";
                    break;
                case 7:
                    $month = "July";
                    break;
                case 8:
                    $month = "August";
                    break;
                case 9:
                    $month = "September";
                    break;
                case 10:
                    $month = "October";
                    break;
                case 11:
                    $month = "November";
                    break;
                default:
                    $month = "December";
                    break;
            }
            
            $year = $rows['Year'];

            $temp = $month.", ".$year;
            
            $bud = "";
            if ($rows['Bucket'] == 'SoCal Gas' || $rows['Bucket'] == 'Car Gas')
            {
                if ($rows['Bucket'] == 'SoCal Gas')
                    $bud = "SoCal_Gas";
                else
                    $bud = "Car_Gas";
            }
            else
            {
                $bud = $rows['Bucket'];
            }
            
            $budget = $arr[$bud];

            //is this a new year/month combo?
            if ($temp == $currentTable)
            {
                //not a new combo, so add the current row to our head/body strings
                $headString = "<th>".$rows['Bucket']."</th>";

                //check "Total" against budgeted amount to determine css class
                $class = "";
                if ($rows['Total'] < $budget)
                {
                    $class = "Under";
                }
                else if ($rows['Total'] > $budget)
                {
                    $class = "Over";
                }
                else
                {
                    $class = "Even";                   
                }

                $bodyString = "<td class=\"".$class."\">$".$rows['Total']."</td>";

                $currentSum = $currentSum + $rows['Total'];
                $currentHead = $currentHead.$headString;
                $currentBody = $currentBody.$bodyString;
            }
            else
            {
                //reset our year/month tracker
                $currentTable = $temp;

                //finish up and write old table, unless this is the first table
                if ($currentHead != "")
                {
                    //compare current sum to projected monthly total to determine css class for total
                    $class = "";
                    if ($currentSum < $budgetSum)
                    {
                        $class = "Under";
                    }
                    else if ($currentSum > $budgetSum)
                    {
                        $class = "Over";
                    }
                    else
                    {
                        $class = "Even";                   
                    }

                    $html = "<table class=\"table\"><thead><tr>".$currentHead."</tr></thead><tbody><tr>".$currentBody."</tr><tr><td>Total</td><td class=\"".$class."\">$".$currentSum."</td></tr></tbody></table>";

                    echo $html;
                }
                
                //write table title "english month, year"
                $html = "<h2>".$currentTable."</h2>";
                echo $html;

                //start the new table
                $headString = "<th>".$rows['Bucket']."</th>";
                $bodyString = "<td>$".$rows['Total']."</td>";

                //reset head and body strings
                $currentHead = $headString;
                $currentBody = $bodyString;
                $currentSum = $rows['Total'];
            }
        }
    
        $class = "";
        if ($currentSum < $budgetSum)
        {
            $class = "Under";
        }
        else if ($currentSum > $budgetSum)
        {
            $class = "Over";
        }
        else
        {
            $class = "Even";                   
        }

        $html = "<table class=\"table\"><thead><tr>".$currentHead."</tr></thead><tbody><tr>".$currentBody."</tr><tr><td>Total</td><td class=\"".$class."\">$".$currentSum."</td></tr></tbody></table>";

        echo $html;
        
        // Close connection to database
        $db = NULL;
    ?>
</body>
</html>