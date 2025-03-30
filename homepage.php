<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .table-container {
            max-height: 400px;
            overflow: auto;
            border: 1px solid #ddd;
            margin-top: 10px;
        }

        table {
            width: 70%;
            margin-left: 20%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            border: 1px solid black;
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .logo img {
            max-width: 120px;
            margin-bottom: 15px;
        }

        .container {
            display: flex;
            /* Use Flexbox layout */
            justify-content: center;
            /* Align horizontally */
            align-items: center;
            /* Align vertically */
            height: 100vh;
            /* Take up full viewport height */
        }

        button {
            width: 80px;
            height: 35px;
            margin-left: 50%;
        }

        select {
            padding: 5px;
            font-size: 14px;
            text-align: center;
        }
        }
    </style>
</head>

<body>
    <div class="logo">
        <img src="natrapharm_logo.png" alt="Natrapharm Logo">
    </div>

    <?php

    $host = "localhost";  // XAMPP default host
    $user = "root";       // Default MySQL user
    $pass = "";           // No password by default
    $dbname = "test"; // Change this to your actual DB name
    
    $conn = new mysqli($host, $user, $pass, $dbname);

    // Step 2: Check Connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Step 3: Fetch Sales Data
    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars($_POST["username"]);
        $password = htmlspecialchars($_POST["password"]);


        $sql = "SELECT * FROM 2025_sales_update WHERE ProductCode = '$username'";
        $result = $conn->query($sql);

        // $action = $_POST['action'];
        // if ($action == 'data') {
        //     $sql = "SELECT * FROM 2025_sales_update";
        // } else if ($action == "district") {
        //     $sql = "SELECT * FROM 2025_sales_update";
        // } else if ($action == "territory") {
        //     $sql = "SELECT * FROM 2025_sales_update";
        // } else {
        //     $sql = "SELECT * FROM 2025_sales_update";
        // }
        $sqlForm = "SELECT * FROM 2025_sales_update";
        $query = $conn->query($sqlForm);


        session_start();
        if ($result->num_rows > 0) {
            echo "<h2>Welcome " . htmlspecialchars($username) . "!</h2>";
        } else {
            $_SESSION['error_message'] = 'Your username or password is incorrect';
            header("Location: http://localhost/myNewWebsite-1/index.php");
            exit();
        }
    }

    ?>
    <div class="table-container">
        <div class="menu">
            <label for="actions">Choose an action:</label>
            <!-- <form method="POST" action=""> -->
            <select name="action" id="actions">
                <option value="data">Data</option>
                <option value="district">District Summary</option>
                <option value="territory">Territory Summary</option>
                <option value="details">Details</option>
            </select>
            <input type="submit" value="View">
            <!-- </form> -->

        </div>
        <table>
            <tr>
                <th>Invoice Date</th>
                <th>Month</th>
                <th>Year</th>
                <th>Customer Name</th>
                <th>Product Code</th>
                <th>Product Description</th>
                <th>Quantity</th>
                <th>Amount (VAT Included)</th>
                <th>Invoice No</th>
            </tr>

            <?php
            // Step 4: Display Data in Table
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["InvcDate"] . "</td>";
                    echo "<td>" . $row["Month"] . "</td>";
                    echo "<td>" . $row["Year"] . "</td>";
                    echo "<td>" . $row["CustomerName"] . "</td>";
                    echo "<td>" . $row["ProductCode"] . "</td>";
                    echo "<td>" . $row["ProductDescription"] . "</td>";
                    echo "<td>" . $row["Quantity"] . "</td>";
                    echo "<td>" . $row["AmountIncludingVAT"] . "</td>";
                    echo "<td>" . $row["InvoiceNo"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No sales data found</td></tr>";
            }

            // Step 5: Close Connection
            $conn->close();
            ?>
        </table>
    </div>
    <button> Download </button>
</body>

</html>