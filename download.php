<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set district code to a fixed value
$divisionCode = "ADULT";

// Get current month name
$currentMonth = date('F');
$months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
$filteredMonths = array_slice($months, 0, array_search($currentMonth, $months) + 1);

// Set headers for CSV download
header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename=\"sales_pivot_METB.csv\"");

// Open output stream
$output = fopen('php://output', 'w');

// Add District Code row
fputcsv($output, ["DivisionCode", $divisionCode]);

// Write column headers
$header = array_merge(
    ["Row Labels", "ProductDescription"],
    array_map(fn($m) => "$m Sum of Quantity", $filteredMonths),
    array_map(fn($m) => "$m Sum of AmountIncludingVAT", $filteredMonths),
    ["2025 Sum of Quantity", "2025 Sum of AmountIncludingVAT", "Total Sum of Quantity", "Total Sum of AmountIncludingVAT"]
);
fputcsv($output, $header);

if (isset($_POSTT['Download'])) {
    $frYear = $_POST['frYear'];
    $toYear = $_POST['toYear'];
    $frMonth = $_POST['frYear'];
    $toMonth = $_POST['frYear'];
}

// Query to fetch data in pivot format
$sql = "SELECT ProductDescription, Year, Month, 
               SUM(Quantity) AS SumOfQuantity, 
               SUM(AmountIncludingVAT) AS SumOfAmount 
        FROM sales_updates
        WHERE DivisionCode = '$divisionCode' 
            AND Year >= '$frYear' 
            AND Year <= '$toYear' 
            AND Month IN ('" . implode("','", $filteredMonths) . "')
        GROUP BY ProductDescription, Year, Month 
        ORDER BY ProductDescription, Year, 
            FIELD(Month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";

$result = $conn->query($sql);

// Store data in an associative array for pivot formatting
$data = [];
$totalQuantity = 0;
$totalAmount = 0;
$activeMonths = []; // Store only the months that have data

while ($row = $result->fetch_assoc()) {
    $product = $row['ProductDescription'];
    $month = $row['Month'];
    $quantity = $row['SumOfQuantity'];
    $amount = $row['SumOfAmount'];

    if (!isset($data[$product])) {
        $data[$product] = ["yearly_quantity" => 0, "yearly_amount" => 0];
    }

    // Store only months that have data
    if ($quantity > 0 || $amount > 0) {
        $data[$product][$month] = ["quantity" => $quantity, "amount" => $amount];
        $activeMonths[$month] = true; // Mark month as active
    }

    // Update yearly totals
    $data[$product]['yearly_quantity'] += $quantity;
    $data[$product]['yearly_amount'] += $amount;

    // Update grand totals
    $totalQuantity += $quantity;
    $totalAmount += $amount;
}

// Keep only active months
$filteredMonths = array_keys($activeMonths);

// Write formatted rows with only active months
foreach ($data as $product => $values) {
    $row = [$product];
    foreach ($filteredMonths as $month) {
        if (isset($values[$month])) {
            $row[] = $values[$month]['quantity'];
            $row[] = $values[$month]['amount'];
        } else {
            $row[] = 0;
            $row[] = 0;
        }
    }
    // Add yearly totals and grand totals
    $row[] = $values['yearly_quantity'];
    $row[] = $values['yearly_amount'];
    $row[] = $totalQuantity;
    $row[] = $totalAmount;

    fputcsv($output, $row);
}

// Close file stream and database connection
fclose($output);
$conn->close();
?>