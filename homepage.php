<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "test";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'])) {
        $username = htmlspecialchars($_POST["username"]);
        $password = htmlspecialchars($_POST["password"]);

        // $username = $conn->real_escape_string($username); // Prevent SQL Injection
        $sql = "SELECT * FROM managers_info WHERE empCode = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<h2>Welcome " . htmlspecialchars($username) . "!</h2>";
        } else {
            session_start();
            $_SESSION['error_message'] = "Invalid username or password";
            header("Location: index.php");
            exit();
        }

    }

    $selected_action = isset($_POST['action']) ? $_POST['action'] : "data";

}

$limit = 50;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// SQL Query based on selection

switch ($selected_action) {
    case 'data':
        $sqlForm = "SELECT * FROM sales_update LIMIT $limit OFFSET $offset";
        break;
    case 'district':
        $sqlForm = "SELECT * FROM sales_update WHERE CustomerNo ='WATSON-40962' LIMIT $limit OFFSET $offset";
        break;
    case 'territory':
        $sqlForm = "SELECT * FROM sales_update WHERE TerritoryCode IS NOT NULL LIMIT $limit OFFSET $offset";
        break;
    case 'details':
        $sqlForm = "SELECT * FROM sales_update WHERE CustomerNo IS NOT NULL LIMIT $limit OFFSET $offset";
        break;
    default:
        $sqlForm = "SELECT * FROM sales_update LIMIT $limit OFFSET $offset";
}

$query = $conn->query($sqlForm);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Sales Update</title>
</head>

<body>
    <div class="logo">
        <img src="natrapharm_logo.png" alt="Natrapharm Logo">
    </div>

    <div class="menu">
        <form method="POST">
            <label for="action">Select an Option:</label>
            <select name="action" id="action">
                <option value="" disabled selected>Select an Option</option>
                <option value="data" <?php if ($selected_action == "data")
                    echo "selected"; ?>>Data</option>
                <option value="district" <?php if ($selected_action == "district")
                    echo "selected"; ?>>District Summary
                </option>
                <option value="territory" <?php if ($selected_action == "territory")
                    echo "selected"; ?>>Territory Summary
                </option>
                <option value="details" <?php if ($selected_action == "details")
                    echo "selected"; ?>>Details</option>
            </select>
            <input type="submit" value="Submit">
        </form>
    </div>

    <div class="table-container">
        <table>
            <tr>
                <th>Month</th>
                <th>Year</th>
                <th>CustomerNo</th>
                <th>CustomerName</th>
                <th>TerritoryCode</th>
                <th>TerritoryDescription</th>
                <th>DistrictCode</th>
                <th>DistrictDescription</th>
                <th>ProductCode</th>
                <th>ProductDescription</th>
                <th>Price</th>
                <th>DivisionCode</th>
                <th>DivisionDescription</th>
                <th>Quantity</th>
                <th>AmountIncludingVAT</th>
                <th>InvoiceNo</th>
                <th>CustomerPostingGrpCode</th>
                <th>FamilyCode</th>
            </tr>
            <?php
            if ($query->num_rows > 0) {
                while ($row = $query->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["Month"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Year"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["CustomerNo"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["CustomerName"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["TerritoryCode"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["TerritoryDescription"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["DistrictCode"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["DistrictDescription"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["ProductCode"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["ProductDescription"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Price"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["DivisionCode"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["DivisionDescription"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Quantity"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["AmountIncludingVAT"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["InvoiceNo"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["CustomerPostingGrpCode"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["FamilyCode"]) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='18'>No sales data found</td></tr>";
            }
            ?>
        </table>
    </div>

    <button style="background: red; color: white;">Download</button>

    <?php $conn->close(); ?>
</body>

</html>