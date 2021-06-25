<?php
$servername="";
$username="";
$password="";
$dbname="";

$connection = new mysqli($servername,$username,$password,$dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

function getCredentials($username) {
    global $connection;

    $statement = $connection->prepare("SELECT username, pass FROM temperature_users WHERE username = ?");
    $statement->bind_param("s", $username);
    $statement->execute();

    $result = $statement->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
            return $row;
        } else { // no match
            return false;
    }
}

function authUser($json) {
        global $connection;

        $data = json_decode($json);
        $email = $data->email;
        $pass = $data->pass;

        $statement = $connection->prepare("SELECT id, username, pass FROM temperature_users WHERE username = ?");
        $statement->bind_param("s", $email);
        $statement->execute();

        $result = $statement->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            if (password_verify($pass, $row['pass'])) {
                $data->id = $row["id"];
                return json_encode($data);
            }
            else { // no match
                $data->id = -1;
                return json_encode($data);
            }
    }
    else { // no match
        $data->id = -1;
        return json_encode($data);
    }
}

function registerUser($json) {
        global $connection;
        $data = json_decode($json);
        
        $email = $data -> email;
        $pass = $data -> pass;

        // checking if email is already in use by another user
        $statement = $connection->prepare("SELECT username FROM temperature_users WHERE username = ?");
        $statement->bind_param("s", $email);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            return -1;
        }
        else {
            $sql = $connection->prepare("INSERT INTO temperature_users (username, pass) VALUES (?, ?)");
            $sql->bind_param("ss", $email, $pass);
            $sql->execute();
            $row = $sql->insert_id;

            return $row; // returns user id
        }
}

function uploadToDB($device, $date, $temperature, $unit, $gap) {
    global $connection;

    $statement = $connection->prepare("INSERT INTO temperature_readings(device, date_taken, temperature, unit, gap) VALUES(?, ?, ?, ?, ?)");
    $statement->bind_param("sssss", $device, $date, $temperature, $unit, $gap);
    $statement->execute();
}

function getAllFromDB() {
    global $connection;

    $statement = $connection->prepare("SELECT id, device, date_taken, temperature, unit FROM temperature_readings ORDER BY id DESC LIMIT 100");
    $statement->execute();
    $result = $statement->get_result();
    
    if ($result->num_rows == 0) {
        echo "<p>No results to display!</p>";
    }
    
    else {
        // printing out table and header
        echo "
              <div class='table-responsive-md'>
                <table class='table table-hover'>
                 <thead class='thead-light'>
                  <tr>";
                  // getting the field names from the db
                  while ($field = $result->fetch_field()) { 
                      echo "<th scope='col'>" . $field->name . "</th>";
                  }
                  // outputting header names for the buttons
                  echo "
                    </tr>
                </thead>
                <tbody>";
                // outputting each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                        echo "<td>" . $row["id"]. "</td>";
                        echo "<td>" . $row["device"]. "</td>";
                        echo "<td>" . $row["date_taken"]. "</td>";
                        echo "<td>" . $row["temperature"]. "</td>";
                        echo "<td>" . $row["unit"]. "</td>
                          </tr>";
                }
                echo "</tbody>
                    </table>
                </div>";
    }
    $statement->close();
    $connection->close();
}

function getLatestFromDB() {
    global $connection;

    $statement = $connection->prepare("SELECT date_taken, temperature, unit, gap FROM temperature_readings ORDER BY id DESC LIMIT 1");
    $statement->execute();
    $result = $statement->get_result();
    
    return $result->fetch_assoc();
}

function removeFromDB($id) {
    global $connection;

    $statement = $connection->prepare("DELETE * FROM temperature_readings WHERE id = ?");
    $statement->bind_param("i", $id);
    $statement->execute();
}