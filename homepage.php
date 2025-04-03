<?php $host = "localhost";
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
            $welcome = "Welcome " . htmlspecialchars($username) . "!";
        } else {
            session_start();
            $_SESSION['error_message'] = "Invalid username or password";
            header("Location: index.php");
            exit();
        }

    }
    $summary = isset($_POST['summary']) ? $_POST['summary'] : "please Select Summary";
    $district = isset($_POST['district']) ? $_POST['district'] : "please select district";
    $frYear = isset($_POST['frYear']) ? $_POST['frYear'] : "please select year";
    $frMonth = isset($_POST['frMonth']) ? $_POST['frMonth'] : "Please Select a month";
    $toYear = isset($_POST['toYear']) ? $_POST['toYear'] : "Please Select a month";
    $toMonth = isset($_POST['toMonth']) ? $_POST['toMonth'] : "Please Select a month";

}

$limit = 20;
$sqlForm = "SELECT * FROM sales_update";
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$offset = ($page - 1) * $limit;

$stmt = $conn->prepare($sqlForm);
// $stmt->bind_param("ii", $limit, $offset);
$stmt->execute();

$result = $stmt->get_result();

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
    <div class="logo" style="display: inline-block; ">
        <img src="natrapharm_logo.png" alt="Natrapharm Logo">
    </div>
    <input type="button" value="Logout" onclick="window.location.href='logout.php';" style="position: absolute; top: 10px; right: 10px; background: red; color: white; padding: 5px 20px; border: none; border-radius: 5px; cursor: pointer;border: 2px solid black; margin: 20px;
">





    <div class="menu"
        style="display: flex;align-content: center;align-items: center;flex-wrap: wrap;flex-direction: column;justify-content: center;">
        <form method="POST" action="download.php">
            <select name="summary" id="summary" style="width:40%; padding: 10px;" onchange="showSelectedSummary()">
                <option value="selected" disabled="" selected="">Summary</option>
                <option value="national">National Summary
                </option>
                <option value="regional">Regional Summary
                </option>
                <option value="district">District Summary
                </option>
                <option value="territory">Territory
                    Summary
                </option>
            </select>

            <select name="district" id="district" style="width:40%; padding: 10px;" onchange="showDistrict()">
                <option value="selected" disabled="" selected="">District</option>
                <option value="LUZNE">LUZNE</option>
                <option value="LUZNW">LUZNW</option>
                <option value="LUZST">LUZST</option>
            </select>

            <h3 style="margin-bottom:0px;">From</h3>

            <!-- Year Dropdown -->
            <select name="frYear" id="frYear" style="display: inline-block; width:40%; padding: 10px;"
                onchange="showFrYear()">
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
            </select>

            <!-- Month Dropdown -->
            <select name="frMonth" id="frMonth" style="width:40%; padding: 10px;" onchange="showFrMonth()">
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
            </select>

            <h3 style="margin-bottom:0px;">To</h3>
            <select name="toYear" id="toYear" style="display: inline-block;width:40%; padding: 10px;"
                onchange="showToYear()">
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
            </select>

            <!-- Month Dropdown -->
            <select name="toMonth" id="toMonth" style="width:40%; padding: 10px;" onchange="showToMonth()">
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
            </select>

            <h6 id="selectedMonth"></h6>
            <h6 id="showDistrict"></h6>
            <h6 id="showFrYear"></h6>
            <h6 id="showFrMonth"></h6>
            <h6 id="showToYear"></h6>
            <h6 id="showToMonth"></h6>

            <?php
            echo "<input type='button' value='Download'style='background:red; height: 30px; width: 100px; color: white; margin-top: 0px; border-radius : 5px'
                onclick=\"window.location.href='download.php?frYear=$frYear&toYear=$toYear&frMonth=$frMonth&toMonth=$toMonth';\">";
            ?>

        </form>

        <!-- <p><?php echo "You selected " . $summary; ?></p>
        <p><?php echo "You selected " . $district; ?></p>
        <p><?php echo "From year " . $frYear; ?></p>
        <p><?php echo "From month " . $frMonth; ?></p>
        <p><?php echo "To year " . $toYear; ?></p>
        <p><?php echo "To month " . $toMonth; ?></p> -->

    </div>
    <?php $conn->close(); ?>

    <script>
        function showSelectedSummary() {
            // Get selected value
            var month = document.getElementById("summary").value;
            // Update the text dynamically
            document.getElementById("selectedMonth").innerText = "Selected Summary: " + month;
        }

        function showDistrict() {
            // Get selected value
            var month = document.getElementById("district").value;
            // Update the text dynamically
            document.getElementById("showDistrict").innerText = "Selected District: " + month;
        }
        function showFrYear() {
            // Get selected value
            var month = document.getElementById("frYear").value;
            // Update the text dynamically
            document.getElementById("showFrYear").innerText = "Selected Year: " + month;
        }
        function showFrMonth() {
            // Get selected value
            var month = document.getElementById("frMonth").value;
            // Update the text dynamically
            document.getElementById("showFrMonth").innerText = "Selected Month: " + month;
        }
        function showToYear() {
            // Get selected value
            var month = document.getElementById("toYear").value;
            // Update the text dynamically
            document.getElementById("showToYear").innerText = "Selected Year: " + month;
        }
        function showToMonth() {
            // Get selected value
            var month = document.getElementById("toMonth").value;
            // Update the text dynamically
            document.getElementById("showToMonth").innerText = "Selected Month: " + month;
        }
    </script>
</body>

</html>