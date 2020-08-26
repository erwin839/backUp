<?php

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database_name = "building";

// Get connection object and set the charset
$conn = mysqli_connect($host, $username, $password, $database_name);
$conn->set_charset("utf8");

// Get All Databases
function getAllDatabase(){
    global $conn;

    $dataBases = array();
    $sql = "SHOW DATABASES";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_row($result)) {
        $dataBases[] = $row[0];
    }

    return $dataBases;
}

// Get All Table Names From the Database
function allTables() {
    global $conn;

    $tables = array();
    $sql = "SHOW TABLES";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }

    return $tables;
}

// Get Tables From Specific Database
function allTableByName($nomDb) {
    // Get connection object and set the charset
    global $host, $password, $username;
    $conn = mysqli_connect($host, $username, $password, $nomDb);
    $conn->set_charset("utf8");

    $tables = array();
    $sql = "SHOW TABLES";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }
    return $tables;
}

function getOneTable($nomDb, $table) {

    // Get connection object and set the charset
    global $host, $password, $username;
    $conn = mysqli_connect($host, $username, $password, $nomDb);
    $conn->set_charset("utf8");

    $db_name = $database_name;
    $db_name = $db_name."_Tb_".$table;
    $sqlScript = "";

    global $conn;
        
        // Prepare SQLscript for creating table structure
        $query = "SHOW CREATE TABLE $table";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_row($result);
        
        $sqlScript .= "\n\n" . $row[1] . ";\n\n";
        
        
        $query = "SELECT * FROM $table";
        $result = mysqli_query($conn, $query);
        
        $columnCount = mysqli_num_fields($result);
        
        // Prepare SQLscript for dumping data for each table
        for ($i = 0; $i < $columnCount; $i ++) {
            while ($row = mysqli_fetch_row($result)) {
                $sqlScript .= "INSERT INTO $table VALUES(";
                for ($j = 0; $j < $columnCount; $j ++) {
                    $row[$j] = $row[$j];
                    
                    if (isset($row[$j])) {
                        $sqlScript .= '"' . $row[$j] . '"';
                    } else {
                        $sqlScript .= '""';
                    }
                    if ($j < ($columnCount - 1)) {
                        $sqlScript .= ',';
                    }
                }
                $sqlScript .= ");\n";
            }
        }
        
        $sqlScript .= "\n"; 

    //var_dump($sqlScript);die();

    if(!empty($sqlScript))
    {
        // Save the SQL script to a backup file
        $backup_file_name = $db_name . '_backup_' . time() . '.sql';
        $fileHandler = fopen($backup_file_name, 'w+');
        fwrite($fileHandler, $sqlScript);

        fclose($fileHandler); 

        // Download the SQL backup file to the browser
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($backup_file_name));
        ob_clean();
        flush();
        readfile($backup_file_name);
        exec('rm ' . $backup_file_name); 
    }   
}

function getAllTables($db_name, $tables) {
    // Get connection object and set the charset
    global $host, $password, $username;
    global $conn;
    $conn = mysqli_connect($host, $username, $password, $db_name);
    $conn->set_charset("utf8");
    
    $database_name = $db_name;
    $sqlScript = "";
    
    
    foreach ($tables as $table) {
        
        // Prepare SQLscript for creating table structure
        $query = "SHOW CREATE TABLE $table";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_row($result);
        
        //var_dump($row); die();
        $sqlScript .= "\n\n" . $row[1] . ";\n\n";
        
        
        $query = "SELECT * FROM $table";
        $result = mysqli_query($conn, $query);
        
        $columnCount = mysqli_num_fields($result);
        
        // Prepare SQLscript for dumping data for each table
        for ($i = 0; $i < $columnCount; $i ++) {
            while ($row = mysqli_fetch_row($result)) {
                $sqlScript .= "INSERT INTO $table VALUES(";
                for ($j = 0; $j < $columnCount; $j ++) {
                    $row[$j] = $row[$j];
                    
                    if (isset($row[$j])) {
                        $sqlScript .= '"' . $row[$j] . '"';
                    } else {
                        $sqlScript .= '""';
                    }
                    if ($j < ($columnCount - 1)) {
                        $sqlScript .= ',';
                    }
                }
                $sqlScript .= ");\n";
            }
        }
        
        $sqlScript .= "\n"; 
    }

    //var_dump($sqlScript);die();

    if(!empty($sqlScript))
    {
        // Save the SQL script to a backup file
        $backup_file_name = $database_name . '_backup_' . time() . '.sql';
        $fileHandler = fopen($backup_file_name, 'w+');
        fwrite($fileHandler, $sqlScript);

        fclose($fileHandler); 

        // Download the SQL backup file to the browser
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($backup_file_name));
        ob_clean();
        flush();
        readfile($backup_file_name);
        exec('rm ' . $backup_file_name); 
    }
}

?>