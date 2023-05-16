<?php 
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'sql202.epizy.com');
define('DB_USERNAME', 'epiz_33293177');
define('DB_PASSWORD', '70TW12CjCrT');
define('DB_NAME', 'epiz_33293177_w599');
 
/* Attempt to connect to MySQL database */
try{
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("Nisam se uspio povezati na " . $e->getMessage());
}
?>