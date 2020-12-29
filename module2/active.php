<?php
    include 'stonk.php';
    include '/home/arko/module2_things/info.php';

    session_start();
    if(isset($_SESSION['currentUser'])){
        
    }
    else {
        header("Location: logout.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="utf-8"/>
    <title>File Sharing</title>
    <style>
        body{
            font-family:serif;
            font-size:15px;
            background-color : rgb(40, 41, 41);
            color: lightgray;
        }
        .fileList{
            position: absolute;
            display: inline-block;
        }
        button{
            font-family:serif;
            font-size:15px;
        }
        input{
            font-family:serif;
            font-size: 15px;
        }
        #uploadfile_input{
            font-family:serif;
            font-size:15px;
        }
        .b1{
            position:absolute;
            left:300px;
            width:100px;
            text-align: center;
        }
        .b2{
            position:absolute;
            left:380px;
            width:100px;
            text-align: center;
        }
        th, td {
            border : 1px solid lightgray;
            padding : 7px;
        }
    </style>
</head>

<body>
    <div>
        <h1>Welcome <?php echo htmlentities($_SESSION['currentUser']);?>!</h1>
        <h2>All Files<a href='https://www.arl.wustl.edu/~todd/cse330/info.html'>:</a></h2>
        <?php
        //This code block here generates the list of files and the options to view it/delete it
            $currentDir = sprintf("/home/arko/module2_things/%s",$_SESSION['currentUser']);
            $files = scandir($currentDir);
            foreach ($files as $key => $value) {
                if(!($value == "." || $value == "..")){
                    echo "<div class='fileList'>";
                    printf("%s",htmlentities(basename($value)));
                    printf("<a class='b1' href='fileview.php?f=%s' target='_blank'><button>View File</button></a><a class='b2' href='delete.php?f=%s'><button>Delete File</button></a>",htmlentities(basename($value)), htmlentities(basename($value)));
                    echo "</div><br><br>";
                }
            }
        ?>
    </div>
    <br>
    <div>
    <!-- Form for submitting a chosen file to be uploaded to the server -->
        <form enctype="multipart/form-data" action='upload.php' method ='POST'> <!-- The following form was used from https://classes.engineering.wustl.edu/cse330/index.php?title=PHP -->
        <p>
            <input type='hidden' name='MAX_FILE_SIZE' value='20000000'/>
            <label for='uploadfile_input'>Choose a file to upload:</label> <input name='uploadedfile' type='file' id='uploadfile_input' />
        </p>
        <p><input type='submit' value="Upload File"/></p>
        </form> <!-- end citation -->
    </div>

    <div>
    <!-- Form for adding/removing stocks to the stock tracker table -->
        <form id='stonks' method='post'>
            <label>Insert stock ticker symbol here&nbsp;<input type='text' name='stonk'/></label>
            <input type='submit' name='stockEffect' value='Add Stock'/> <input type='submit' name='stockEffect' value='Remove Stock'/>
        </form>
        <br><table>
        <tr><th>Company</th><th>Ticker</th><th>Price</th></tr>
        <?php
           if(isset($_POST['stonk'])) {
                $ticker = (string)$_POST['stonk'];
                $username =(string) $_SESSION['currentUser'];
                if (!$ticker == ""){
                    $stockInfo = callStock($ticker);
                    if ($stockInfo === "INVALID"){ //Makes sure the stock is valid
                        echo 'Invalid Stock';
                    } else {
                        if($_POST['stockEffect'] == 'Add Stock'){
                            addStock($ticker, $username);
                            echo '';
                        } else if ($_POST['stockEffect'] == 'Remove Stock'){
                            removeStock($ticker, $username);
                        }
                    }
                } else {
                    echo 'Invalid Stock';
                }
            }
            //The following code block populates the table with the stock name, ticker symbol, and price using stonk.php
            $filePath = sprintf('/home/arko/module2_things/%s_stonks.txt', (string)$_SESSION['currentUser']);
            if(!file_get_contents($filePath) == ""){
                    $strStock = file_get_contents($filePath);
                    $stocksArray = (array)explode(',',$strStock);       //Creates an array from the stocks listed in the user's stonks.txt file
                    foreach ($stocksArray as $key => $value) {
                        $stocksArray[$key] = (string)trim($value);
                    }
                    array_pop($stocksArray);                            //Removes the last entry, which is just ""
                    foreach ($stocksArray as $key => $value) {
                        $currStockInfo = (array)callStock($value);
                        printf('<tr><td>%s</td><td>%s</td><td>%s</td></tr>', htmlentities($currStockInfo[0]), htmlentities($value), htmlentities($currStockInfo[1]));
                    }
                }
        ?>
        </table>
    </div>

    <br><a href="logout.php"><button>Logout</button></a>
</body>
</html>