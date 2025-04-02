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

// Query to fetch data in pivot format
$sql = "SELECT ProductDescription, Year, Month, 
               SUM(Quantity) AS SumOfQuantity, 
               SUM(AmountIncludingVAT) AS SumOfAmount 
        FROM sales_updates
        WHERE DivisionCode = '$divisionCode' AND Year = 2025 AND Month IN ('" . implode("','", $filteredMonths) . "')
        GROUP BY ProductDescription, Year, Month 
        ORDER BY ProductDescription, Year, FIELD(Month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";

$result = $conn->query($sql);

// Store data in an associative array for pivot formatting
$data = [];
$totalQuantity = 0;
$totalAmount = 0;

$totalQuantity = 0;
$totalAmount = 0;

while ($row = $result->fetch_assoc()) {
    $product = $row['ProductDescription'];
    $month = $row['Month'];
    $quantity = $row['SumOfQuantity'];
    $amount = $row['SumOfAmount'];

    if (!isset($data[$product])) {
        $data[$product] = array_fill_keys($filteredMonths, ["quantity" => 0, "amount" => 0]);
        $data[$product]['yearly_quantity'] = 0;
        $data[$product]['yearly_amount'] = 0;
    }
    $data[$product][$month] = ["quantity" => $quantity, "amount" => $amount];
    $data[$product]['yearly_quantity'] += $quantity;
    $data[$product]['yearly_amount'] += $amount;

    $totalQuantity += $quantity;
    $totalAmount += $amount;
}

// Write formatted rows
foreach ($data as $product => $values) {
    $row = [$product];
    foreach ($filteredMonths as $month) {
        $row[] = $values[$month]['quantity'];
        $row[] = $values[$month]['amount'];
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