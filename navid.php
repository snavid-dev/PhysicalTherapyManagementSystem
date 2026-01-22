<?php
// Database credentials
$host = 'localhost'; // Replace with your database host if different
$username = 'haidari_2'; // Replace with your MySQL username
$password = 'LCyiHjmjMSs32ndd'; // Replace with your MySQL password
$database = 'haidari_2'; // Database name

// Backup file settings
$backupDir = './backups/'; // Directory to store the backup file (must be writable)
$backupFile = $backupDir . 'haidari_2_backup_' . date('Y-m-d_H-i-s') . '.sql';

// Create backups directory if it doesn't exist
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Initialize mysqli connection
$mysqli = new mysqli($host, $username, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set charset to UTF-8
$mysqli->set_charset('utf8');

// Get all tables in the database
$tables = [];
$result = $mysqli->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    $tables[] = $row[0];
}

// Open file for writing
$file = fopen($backupFile, 'w');
if (!$file) {
    die("Error: Cannot open file {$backupFile} for writing.");
}

// Write SQL header
fwrite($file, "SET FOREIGN_KEY_CHECKS=0;\n");
fwrite($file, "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n");
fwrite($file, "SET time_zone='+00:00';\n\n");

// Loop through each table
foreach ($tables as $table) {
    // Get CREATE TABLE statement
    $result = $mysqli->query("SHOW CREATE TABLE `{$table}`");
    $row = $result->fetch_row();
    $createTable = $row[1];
    
    // Write DROP TABLE and CREATE TABLE statements
    fwrite($file, "\n-- Table structure for `{$table}`\n");
    fwrite($file, "DROP TABLE IF EXISTS `{$table}`;\n");
    fwrite($file, "{$createTable};\n\n");

    // Get table data
    $result = $mysqli->query("SELECT * FROM `{$table}`");
    if ($result->num_rows > 0) {
        fwrite($file, "-- Data for `{$table}`\n");
        $columns = $mysqli->query("SHOW COLUMNS FROM `{$table}`");
        $columnNames = [];
        while ($col = $columns->fetch_assoc()) {
            $columnNames[] = "`{$col['Field']}`";
        }
        $columnList = implode(', ', $columnNames);

        // Write INSERT statements
        while ($row = $result->fetch_assoc()) {
            $values = [];
            foreach ($row as $value) {
                if ($value === null) {
                    $values[] = 'NULL';
                } else {
                    $values[] = "'" . $mysqli->real_escape_string($value) . "'";
                }
            }
            $valueList = implode(', ', $values);
            fwrite($file, "INSERT INTO `{$table}` ({$columnList}) VALUES ({$valueList});\n");
        }
        fwrite($file, "\n");
    }
}

// Write SQL footer
fwrite($file, "SET FOREIGN_KEY_CHECKS=1;\n");

// Close file and database connection
fclose($file);
$mysqli->close();

// Output success message and download link
echo "Backup of database '{$database}' created successfully at: {$backupFile}\n";
if (file_exists($backupFile)) {
    echo "<br><a href='{$backupFile}' download>Download {$backupFile}</a>";
} else {
    echo "<br>Error: Backup file was not created.";
}

?>