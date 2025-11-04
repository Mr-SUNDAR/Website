<?php
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

$printer_ip = "192.168.1.100";
$printer_port = 9100;
$token_file = "token_number.txt";

// Ensure token file exists
if(!file_exists($token_file)) file_put_contents($token_file, "0");

// Function to print a single token
function printToken($tokenNumber, $foodType, $date) {
    global $printer_ip, $printer_port;
    try {
        $connector = new NetworkPrintConnector($printer_ip, $printer_port);
        $printer = new Printer($connector);

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setEmphasis(true);
        $printer->text("--------------------------------\n");
        $printer->text("  ACHARIYA CANTEEN \n");
        $printer->text("--------------------------------\n");
        $printer->setEmphasis(false);
        $printer->text("\n");

        $printer->setTextSize(2,2);
        $printer->text("TOKEN #: $tokenNumber\n\n");
        $printer->setTextSize(1,1);
        $printer->setEmphasis(true);
        $printer->text("TYPE: $foodType\n");
        $printer->setEmphasis(false);
        $printer->text("--------------------------------\n");
        $printer->text("DATE: $date\n"); // Use selected date
        $printer->text("--------------------------------\n\n");

        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("Thank you! Enjoy your meal 😋\n\n\n");
        $printer->cut();
        $printer->close();

    } catch (Exception $e) {
        echo "<h3>Printing error: " . $e->getMessage() . "</h3>";
    }
}

// Handle single or bulk token
if($_SERVER["REQUEST_METHOD"] === "POST") {
    // Reset functionality
    if(isset($_POST['reset'])) {
        file_put_contents($token_file, "0"); // Reset token
        echo "<h3>✅ Token counter has been reset to 0.</h3>";
        echo "<p><a href='food.php'>Back</a></p>";
        exit;
    }

    // Single / bulk token
    if(isset($_POST['food_type'])) {
        $foodType = $_POST['food_type'];
        $bulk = intval($_POST['bulk_tokens'] ?? 1);
        $selectedDate = $_POST['token_date'] ?? date("d-m-Y"); // Use selected date

        $currentToken = intval(file_get_contents($token_file));

        for($i=1; $i<=$bulk; $i++) {
            $currentToken++;
            printToken($currentToken, $foodType, $selectedDate);
        }

        file_put_contents($token_file, $currentToken);
        echo "<h3>✅ $bulk $foodType token(s) printed. Latest token #: $currentToken</h3>";
        echo "<p><a href='food.php'>Back</a></p>";
        exit;
    }
}

$currentToken = intval(file_get_contents($token_file));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Food Token System</title>
    <style>
        body { text-align:center; font-family:Arial, sans-serif; margin-top:50px; background:#f7f7f7; }
        h1 { color:#333; }
        h3 { color:#555; }
        button { font-size:22px; padding:15px 35px; margin:15px; border:none; border-radius:10px; color:white; cursor:pointer; }
        .veg { background:#4CAF50; }
        .nonveg { background:#f44336; }
        .reset { background:#555; }
        input[type=number], input[type=date] { font-size:18px; padding:5px; width:150px; margin-left:10px; }
        .note { font-size:14px; color:#666; margin-top:20px; }
    </style>
</head>
<body>
    <h1>🍴 FOOD TOKEN SYSTEM</h1>
    <h3>Current Token Number: <?= $currentToken ?></h3>

    <form method="POST">
        <h2>Select Date for Tokens</h2>
        <input type="date" name="token_date" value="<?= date('Y-m-d') ?>" />

        <h2>Single Token</h2>
        <button class="veg" type="submit" name="food_type" value="VEGETARIAN">VEGETARIAN</button>
        <button class="nonveg" type="submit" name="food_type" value="NON-VEGETARIAN">NON-VEGETARIAN</button>

        <h2>Bulk Token</h2>
        <input type="number" name="bulk_tokens" value="1" min="1" />
        <button class="veg" type="submit" name="food_type" value="VEGETARIAN">Print Bulk Vegetarian</button>
        <button class="nonveg" type="submit" name="food_type" value="NON-VEGETARIAN">Print Bulk Non-Vegetarian</button>

        <h2>Reset Token Counter</h2>
        <button type="submit" name="reset" class="reset">RESET COUNTER</button>
    </form>

    <p class="note">Token numbers auto-increment. Works with thermal printer networked at <?= $printer_ip ?>.</p>
</body>
</html>
