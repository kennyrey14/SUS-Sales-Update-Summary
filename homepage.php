<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
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

    // Check Connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch Sales Data
    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = htmlspecialchars($_POST["username"]);
        $password = htmlspecialchars($_POST["password"]);

        $sql = "SELECT * FROM managers_info WHERE empCode = '$username'";
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

        $limit = 50; // Show 50 records per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $sqlForm = "SELECT * FROM sales_update LIMIT $limit OFFSET $offset" ;
        $query = $conn->query($sqlForm);


        session_start();
        if ($result->num_rows > 0) {
            $sql = "SELECT * FROM managers_info WHERE empCode = '$username'";
            $result = $conn->query($sql);
            echo "<h2>Welcome " . htmlspecialchars($username) . "!</h2>";
        } else {
            $_SESSION['error_message'] = 'Your username or password is incorrect';
            header("Location: http://localhost/SUS-Sales-Update-Summary/index.php");
            exit();
        }
    }

    ?>

    <div class="menu">
        <!-- <label for="actions">:</label> -->
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
    <div class="table-container">
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
<!-- 
            <?php
            // Display Data in Table
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["source"] . "</td>";
                    echo "<td>" . $row["Month"] . "</td>";
                    echo "<td>" . $row["Year"] . "</td>";
                    echo "<td>" . $row["CustomerNo"] . "</td>";
                    echo "<td>" . $row["CustomerName"] . "</td>";
                    echo "<td>" . $row["TerritoryCode"] . "</td>";
                    echo "<td>" . $row["TerritoryDescription"] . "</td>";
                    echo "<td>" . $row["DistrictCode"] . "</td>";
                    echo "<td>" . $row["DistrictDescription"] . "</td>";
                    echo "<td>" . $row["ProductCode"] . "</td>";
                    echo "<td>" . $row["ProductDescription"] . "</td>";
                    echo "<td>" . $row["Price"] . "</td>";
                    echo "<td>" . $row["DivisionCode"] . "</td>";
                    echo "<td>" . $row["DivisionDescription"] . "</td>";
                    echo "<td>" . $row["Quantity"] . "</td>";
                    echo "<td>" . $row["AmountIncludingVAT"] . "</td>";
                    echo "<td>" . $row["InvoiceNo"] . "</td>";
                    echo "<td>" . $row["CustomerPostingGrpCode"] . "</td>";
                    echo "<td>" . $row["FamilyCode"] . "</td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No sales data found</td></tr>";
            }

            // Close Connection
            $conn->close();
            ?> -->
        </table>
    </div>
    <button> Download </button>
</body>

</html>