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


if ($selected_action) {
    switch ($selected_action) {
        case 'data':
            $sqlForm = "SELECT * FROM sales_update LIMIT ? OFFSET ?";
            break;
        case 'district':
            $sqlForm = "SELECT * FROM sales_update WHERE TerritoryCode = 'watsons' LIMIT ? OFFSET ?";
            break;
        case 'territory':
            $sqlForm = "SELECT * FROM sales_update WHERE TerritoryCode IS NOT NULL LIMIT ? OFFSET ?";
            break;
        case 'details':
            $sqlForm = "SELECT * FROM sales_update WHERE CustomerNo IS NOT NULL LIMIT ? OFFSET ?";
            break;
        default:
            $sqlForm = "SELECT * FROM sales_update LIMIT ? OFFSET ?";
            break;
    }

    // Use prepared statements for security
    $stmt = $conn->prepare($sqlForm);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();

    if ($selected_action == 'data') {
        $query = false;
    } else {
        $query = $stmt->get_result();
    }


} else {
    $query = false; // No query executed
}

// $query = $conn->query($sqlForm);

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
        <form method="POST" style="display: inline-block; margin-right: 10px;">
            <select name="action" id="action">
                <option value="selected" disabled selected>Summary</option>
                <option value="district" <?php if ($selected_action == "district")
                    echo "selected"; ?>>District Summary
                </option>
                <option value="territory" <?php if ($selected_action == "territory")
                    echo "selected"; ?>>Territory Summary
                </option>
                <option value="details" <?php if ($selected_action == "details")
                    echo "selected"; ?>>Details</option>
            </select>

            <h3 style="display: inline-block; margin-left: 20px;">From</h3>

            <!-- Year Dropdown -->
            <select name="year" id="year">
                <?php
                $currentYear = date("Y");
                for ($i = 0; $i < 3; $i++) {
                    $year = $currentYear - $i;
                    echo "<option value='$year'>$year</option>";
                }
                ?>
            </select>

            <!-- Month Dropdown -->
            <select name="month" id="month">
                <?php
                $months = [
                    1 => "January",
                    2 => "February",
                    3 => "March",
                    4 => "April",
                    5 => "May",
                    6 => "June",
                    7 => "July",
                    8 => "August",
                    9 => "September",
                    10 => "October",
                    11 => "November",
                    12 => "December"
                ];
                foreach ($months as $num => $name) {
                    echo "<option value='$num'>$name</option>";
                }
                ?>
            </select>

            <h3 style="display: inline-block; margin-left: 20px;">To</h3>

            <!-- Year Dropdown -->
            <select name="year" id="year">
                <?php
                $currentYear = date("Y");
                for ($i = 0; $i < 3; $i++) {
                    $year = $currentYear - $i;
                    echo "<option value='$year'>$year</option>";
                }
                ?>
            </select>

            <!-- Month Dropdown -->
            <select name="month" id="month">
                <?php
                $months = [
                    1 => "January",
                    2 => "February",
                    3 => "March",
                    4 => "April",
                    5 => "May",
                    6 => "June",
                    7 => "July",
                    8 => "August",
                    9 => "September",
                    10 => "October",
                    11 => "November",
                    12 => "December"
                ];
                foreach ($months as $num => $name) {
                    echo "<option value='$num'>$name</option>";
                }
                ?>
            </select>
            <input type="submit" value="Submit" style="background:red; height: 30px; color: white;">
        </form>
    </div>



    <div class="table-container">
        <table>
            <tr>
                <th>Source</th>
                <th>InvDate</th>
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
                <th>DivisionCode</th>
                <th>DivisionDescription</th>
                <th>RegionCode</th>
                <th>RegionDescription</th>
                <th>Quantity</th>
                <th>AmountIncludingVAT</th>
                <th>InvoiceNo</th>
                <th>FamilyCode</th>
            </tr>
            <?php

            // $csvFile = "SALES UPDATE.csv";
            
            // if (($handle = fopen($csvFile, "r")) !== FALSE) {
            //     fgetcsv($handle); // Skip the header row
            
            //     // Use prepared statements for security
            //     $stmt = $conn->prepare("INSERT INTO sales_update (
            //         Source, InvcDate, Month, Year, CustomerNo, CustomerName, 
            //         TerritoryCode, TerritoryDescription, DistrictCode, DistrictDescription, 
            //         ProductCode, ProductDescription, DivisionCode, DivisionDescription, 
            //         RegionCode, RegionDescription, Quantity, AmountIncludingVAT, 
            //         InvoiceNo, FamilyCode
            //     ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            //     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            //         $stmt->bind_param(
            //             "ssssssssssssssssssss",  // Correct format string
            //             $data[0],  // Source
            //             $data[1],  // InvcDate
            //             $data[2],  // Month
            //             $data[3],  // Year
            //             $data[4],  // CustomerNo
            //             $data[5],  // CustomerName
            //             $data[6],  // TerritoryCode
            //             $data[7],  // TerritoryDescription
            //             $data[8],  // DistrictCode
            //             $data[9],  // DistrictDescription
            //             $data[10], // ProductCode
            //             $data[11], // ProductDescription
            //             $data[12], // DivisionCode
            //             $data[13], // DivisionDescription
            //             $data[14], // RegionCode
            //             $data[15], // RegionDescription
            //             $data[16], // Quantity
            //             $data[17], // AmountIncludingVAT
            //             $data[18], // InvoiceNo
            //             $data[19]  // FamilyCode
            //         );
            //         $stmt->execute();
            //     }
            
            //     fclose($handle);
            //     echo "CSV data imported successfully!<br>";
            // }
            
            // Fetch sales data
            if ($selected_action != "data") {
                $query = $conn->query("SELECT * FROM sales_update");

                if ($query->num_rows > 0) {
                    while ($row = $query->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["Source"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["InvcDate"]) . "</td>";
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
                        echo "<td>" . htmlspecialchars($row["DivisionCode"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["DivisionDescription"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["RegionCode"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["RegionDescription"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["Quantity"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["AmountIncludingVAT"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["InvoiceNo"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["FamilyCode"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='18'>No sales data found</td></tr>";
                }
            }
            ?>
        </table>
    </div>

    <a href="download.php" class="btn btn-success">Download
        Excel</a>


    <?php $conn->close(); ?>
</body>

</html>