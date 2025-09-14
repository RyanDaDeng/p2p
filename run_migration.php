<?php

$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');

// Check and add response_time to users table
$columns = $db->query("PRAGMA table_info(users)")->fetchAll(PDO::FETCH_ASSOC);
$hasResponseTime = false;
foreach ($columns as $col) {
    if ($col['name'] === 'response_time') {
        $hasResponseTime = true;
        break;
    }
}

if (!$hasResponseTime) {
    $db->exec("ALTER TABLE users ADD COLUMN response_time INTEGER");
    echo "Added response_time to users table\n";
} else {
    echo "response_time already exists in users table\n";
}

// Check and add fields to orders table
$columns = $db->query("PRAGMA table_info(orders)")->fetchAll(PDO::FETCH_ASSOC);
$hasVendorResponse = false;
$hasResponseMinutes = false;
foreach ($columns as $col) {
    if ($col['name'] === 'vendor_first_response_at') {
        $hasVendorResponse = true;
    }
    if ($col['name'] === 'response_time_minutes') {
        $hasResponseMinutes = true;
    }
}

if (!$hasVendorResponse) {
    $db->exec("ALTER TABLE orders ADD COLUMN vendor_first_response_at DATETIME");
    echo "Added vendor_first_response_at to orders table\n";
} else {
    echo "vendor_first_response_at already exists in orders table\n";
}

if (!$hasResponseMinutes) {
    $db->exec("ALTER TABLE orders ADD COLUMN response_time_minutes INTEGER");
    echo "Added response_time_minutes to orders table\n";
} else {
    echo "response_time_minutes already exists in orders table\n";
}

echo "Migration completed!\n";