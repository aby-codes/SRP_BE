<?php
header('Access-Control-Allow-Origin: *'); // Allow all domains to access this resource
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Allow specific headers

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // If it's a preflight request, exit here
    exit(0);
}

$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "kesehatan";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$table = $_GET['table'] ?? null; 
$view = $_GET['view'] ?? null; 
$id = $_GET['id'] ?? null; 
$ID_Dokter = $_GET['ID_Dokter'] ?? null;
$ID_Penyakit = $_GET['ID_Penyakit'] ?? null;
$ID_Obat = $_GET['ID_Obat'] ?? null;
$ID_Pasien = $_GET['ID_Pasien'] ?? null;
$ID_Hubungan = $_GET['ID_Hubungan'] ?? null;

$method = $_SERVER['REQUEST_METHOD'];
date_default_timezone_set('Asia/Jakarta');

$now = date('Y-m-d H:i:s');
switch ($method) {
    case 'GET':
        if ($table) {
            if ($ID_Pasien) {
                $result = getDataFromTableByIdPasien($conn, $table, $ID_Pasien); 
            } elseif ($ID_Dokter) {
                $result = getDataFromTableByIdDokter($conn, $table, $ID_Dokter);
            } elseif ($ID_Obat) {
                $result = getDataFromTableByIdObat($conn, $table, $ID_Obat);
            } elseif ($ID_Penyakit) {
                $result = getDataFromTableByIdPenyakit($conn, $table, $ID_Penyakit);
            } elseif ($ID_Hubungan) {
                $result = getDataFromTableByIdHubungan($conn, $table, $ID_Hubungan);
            } else {
                $result = getAllDataFromTable($conn, $table); 
            }
        } elseif ($view) {
            if ($ID_Hubungan) {
                $result = getDataFromTableByViewIdHubungan($conn, $view, $ID_Hubungan);
            } else {
                $result = getAllDataFromView($conn, $view); 
            }
        } else {
            $result = getAllData($conn); 
        }
        break;
    
    case 'DELETE':
        if ($table) {
            if ($ID_Pasien) {
                $result = deleteDataFromTableByIdPasien($conn, $table, $ID_Pasien); 
            } elseif ($ID_Dokter) {
                $result = deleteDataFromTableByIdDokter($conn, $table, $ID_Dokter);
            } elseif ($ID_Obat) {
                $result = deleteDataFromTableByIdObat($conn, $table, $ID_Obat);
            } elseif ($ID_Penyakit) {
                $result = deleteDataFromTableByIdPenyakit($conn, $table, $ID_Penyakit);
            } elseif ($ID_Hubungan) {
                $result = deleteDataFromTableByIdHubungan($conn, $table, $ID_Hubungan);
            } else {
                $result = "Invalid endpoint for update. Please provide table name and ID.";
            }
        } else {
            $result = "Please provide both table name and ID to delete.";
        } 
        break;

    case 'POST':
        if ($table) {
            if (!empty($_POST)) {
                $result = addDataToTable($conn, $table, $_POST);
            } else {
                $result = "Invalid endpoint";
            }
        } else {
            $result = "Please provide table name.";
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);
        if ($table) {
            if ($ID_Pasien) {
                $result = updateDataInTableByIdPasien($conn, $table, $ID_Pasien, $_PUT);
            } elseif ($ID_Dokter) {
                $result = updateDataInTableByIdDokter($conn, $table, $ID_Dokter, $_PUT);
            } elseif ($ID_Obat) {
                $result = updateDataInTableByIdObat($conn, $table, $ID_Obat, $_PUT);
            } elseif ($ID_Penyakit) {
                $result = updateDataInTableByIdPenyakit($conn, $table, $ID_Penyakit, $_PUT);
            } elseif ($ID_Hubungan) {
                $result = updateDataInTableByIdHubungan($conn, $table, $ID_Hubungan, $_PUT);
            } else {
                $result = "Invalid endpoint for update. Please provide table name and ID.";
            }
        } else {
            $result = "Please provide both table name and ID to update.";
        }
        break;

    default:
        $result = "Invalid request method.";
        break;
}

function getDataFromTableByViewIdHubungan($conn, $view, $ID_Hubungan) {
    $sql = "SELECT * FROM $view WHERE ID_Hubungan = $ID_Hubungan";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return "No data found in view $view";
    }
}

function getAllDataFromView($conn, $view) {
    $sql = "SELECT * FROM $view";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return "No data found in view $view";
    }
}

function getDataFromTableByIdPasien($conn, $table, $ID_Pasien) {
    $sql = "SELECT * FROM $table WHERE ID_Pasien = '$ID_Pasien'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return "No data found in table $table";
    }
}
function getDataFromTableByIdPenyakit($conn, $table, $ID_Penyakit) {
    $sql = "SELECT * FROM $table WHERE ID_Penyakit = '$ID_Penyakit'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return "No data found in table $table";
    }
}

function getDataFromTableByIdDokter($conn, $table, $ID_Dokter) {
    $sql = "SELECT * FROM $table WHERE ID_Dokter = '$ID_Dokter'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return "No data found in table $table";
    }
}

function getDataFromTableByIdObat($conn, $table, $ID_Obat) {
    $sql = "SELECT * FROM $table WHERE ID_Obat = '$ID_Obat'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return "No data found in table $table";
    }
}

function getDataFromTableByIdHubungan($conn, $table, $ID_Hubungan) {
    $sql = "SELECT * FROM $table WHERE ID_Hubungan = '$ID_Hubungan'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return "No data found in table $table";
    }
}

function getAllDataFromTable($conn, $table) {
    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    } else {
        return "No data found in table $table";
    }
}

function getAllData($conn) {
    $tables = $conn->query("SHOW TABLES")->fetch_all();
    $data = array();
    foreach ($tables as $table) {
        $tableName = $table[0];
        $data[$tableName] = getAllDataFromTable($conn, $tableName);
    }
    return $data;
}

function deleteDataFromTableById($conn, $table, $id) {
    $sql = "DELETE FROM $table WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        return "Data with ID $id deleted successfully from table $table";
    } else {
        return "Error deleting data: " . $conn->error;
    }
}

function deleteDataFromTableByIdPasien($conn, $table, $ID_Pasien) {
    $sql = "DELETE FROM $table WHERE ID_Pasien = '$ID_Pasien'";
    if ($conn->query($sql) === TRUE) {
        return "Data with ID_Pasien $ID_Pasien deleted successfully from table $table";
    } else {
        return "Error deleting data: " . $conn->error;
    }
}

function deleteDataFromTableByIdDokter($conn, $table, $ID_Dokter) {
    $sql = "DELETE FROM $table WHERE ID_Dokter = '$ID_Dokter'";
    if ($conn->query($sql) === TRUE) {
        return "Data with ID_Dokter $ID_Dokter deleted successfully from table $table";
    } else {
        return "Error deleting data: " . $conn->error;
    }
}

function deleteDataFromTableByIdObat($conn, $table, $ID_Obat) {
    $sql = "DELETE FROM $table WHERE ID_Obat = '$ID_Obat'";
    if ($conn->query($sql) === TRUE) {
        return "Data with ID_Obat $ID_Obat deleted successfully from table $table";
    } else {
        return "Error deleting data: " . $conn->error;
    }
}

function deleteDataFromTableByIdPenyakit($conn, $table, $ID_Penyakit) {
    $sql = "DELETE FROM $table WHERE ID_Penyakit = '$ID_Penyakit'";
    if ($conn->query($sql) === TRUE) {
        return "Data with ID_Penyakit $ID_Penyakit deleted successfully from table $table";
    } else {
        return "Error deleting data: " . $conn->error;
    }
}

function deleteDataFromTableByIdHubungan($conn, $table, $ID_Hubungan) {
    $sql = "DELETE FROM $table WHERE ID_Hubungan = '$ID_Hubungan'";
    if ($conn->query($sql) === TRUE) {
        return "Data with ID_Hubungan $ID_Hubungan deleted successfully from table $table";
    } else {
        return "Error deleting data: " . $conn->error;
    }
}

function addDataToTable($conn, $table, $data) {
    $columns = implode(", ", array_keys($data));
    $values = implode("', '", array_values($data));
    $sql = "INSERT INTO $table ($columns) VALUES ('$values')";
    
    if ($conn->query($sql) === TRUE) {
        return "New record created successfully in table $table";
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
}

function updateDataInTableByIdPasien($conn, $table, $ID_Pasien, $data) {
    $updateValues = "";
    foreach ($data as $key => $value) {
        $updateValues .= "$key = '$value', ";
    }
    $updateValues = rtrim($updateValues, ", ");
    
    $sql = "UPDATE $table SET $updateValues WHERE ID_Pasien = '$ID_Pasien'";
    if ($conn->query($sql) === TRUE) {
        return "Data with ID_Pasien $ID_Pasien updated successfully in table $table";
    } else {
        return "Error updating data: " . $conn->error;
    }
}

function updateDataInTableByIdDokter($conn, $table, $ID_Dokter, $data) {
    $updateValues = "";
    foreach ($data as $key => $value) {
        $updateValues .= "$key = '$value', ";
    }
    $updateValues = rtrim($updateValues, ", ");
    
    $sql = "UPDATE $table SET $updateValues WHERE ID_Dokter = '$ID_Dokter'";
    if ($conn->query($sql) === TRUE) {
        return "Data with ID_Dokter $ID_Dokter updated successfully in table $table";
    } else {
        return "Error updating data: " . $conn->error;
    }
}

function updateDataInTableByIdObat($conn, $table, $ID_Obat, $data) {
    $updateValues = "";
    foreach ($data as $key => $value) {
        $updateValues .= "$key = '$value', ";
    }
    $updateValues = rtrim($updateValues, ", ");
    
    $sql = "UPDATE $table SET $updateValues WHERE ID_Obat = '$ID_Obat'";
    if ($conn->query($sql) === TRUE) {
        return "Data with ID_Obat $ID_Obat updated successfully in table $table";
    } else {
        return "Error updating data: " . $conn->error;
    }
}

function updateDataInTableByIdPenyakit($conn, $table, $ID_Penyakit, $data) {
    $updateValues = "";
    foreach ($data as $key => $value) {
        $updateValues .= "$key = '$value', ";
    }
    $updateValues = rtrim($updateValues, ", ");
    
    $sql = "UPDATE $table SET $updateValues WHERE ID_Penyakit = '$ID_Penyakit'";
    if ($conn->query($sql) === TRUE) {
        return "Data with ID_Penyakit $ID_Penyakit updated successfully in table $table";
    } else {
        return "Error updating data: " . $conn->error;
    }
}

function updateDataInTableByIdHubungan($conn, $table, $ID_Hubungan, $data) {
    $updateValues = "";
    foreach ($data as $key => $value) {
        $updateValues .= "$key = '$value', ";
    }
    $updateValues = rtrim($updateValues, ", ");
    
    $sql = "UPDATE $table SET $updateValues WHERE ID_Hubungan = '$ID_Hubungan'";
    if ($conn->query($sql) === TRUE) {
        return "Data with ID_Hubungan $ID_Hubungan updated successfully in table $table";
    } else {
        return "Error updating data: " . $conn->error;
    }
}

header('Content-Type: application/json');
echo json_encode($result);
?>