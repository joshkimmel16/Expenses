<html>
<head>
	<title>Thank You Page</title>
	<link type=css/text rel=stylesheet href=ThankYou.css />
</head>
<body>
	<h1>Thank You!</h1>
	<img src=DH.jpeg />
</body>
</html>

<?php
ini_set('display_errors', 1);
if (isset($_POST["Submit"]))
    {
        $XMLWrong = $_POST["XML"];
        $XML = htmlentities($XMLWrong);

        //extract each expense from XML
        $pattern = htmlentities("<expense>(.+?)</expense>");
        preg_match_all("#".$pattern."#", $XML, $Expenses);
    
        //generate new HTML for each expense
        for ($i=0; $i<count($Expenses[1]); $i++)
        {   
            echo $Expenses[1][$i];
            
            $patternB = htmlentities("<bucket>(.+)</bucket>");
            $patternA = htmlentities("<amount>(.+)</amount>");
            $patternD = htmlentities("<desc>(.+)</desc>");
            $patternW = htmlentities("<date>(.+)</date>");

            preg_match("#".$patternB."#", $Expenses[1][$i], $Bucket);
            preg_match("#".$patternA."#", $Expenses[1][$i], $Amount);
            preg_match("#".$patternD."#", $Expenses[1][$i], $Desc);
            preg_match("#".$patternW."#", $Expenses[1][$i], $Date);
            
            //insert row into mysql DB!
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $dbname = "SandBox";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
            
            $sql = "INSERT INTO Expense (Bucket, Amount, Description, Date)
            VALUES ('".$Bucket[1]."', ".$Amount[1].", '".$Desc[1]."', '".$Date[1]."')";

            if ($conn->query($sql) === TRUE) {
                echo "Expenses Saved!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        }
    }
?>