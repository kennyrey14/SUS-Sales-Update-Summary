
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
            width: 70%;
            margin-left : 20%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .logo img {
             max-width: 120px;
            margin-bottom: 15px;
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



    if ($result->num_rows > 0) {
        echo "<h2>Welcome " . htmlspecialchars($username) . "!</h2>";
    } else {
        header("Location: http://localhost/myNewWebsite-1/index.php");
        exit(); // Always exit after a header redirect
    }
    

    // header("Location: http://localhost/myNewWebsite-1/index.php");
    // exit();
}

?>

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
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
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

</body>
</html>