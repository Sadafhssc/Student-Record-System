<?php
class Student
{
    private $connection;

    public $name;
    public $email;
    public $id;
    public $age;
    public $gender;
    public $image;

    public function __construct($servername, $username, $password, $database)
    {
        // Establish database connection
        $this->connection = new mysqli($servername, $username, $password, $database);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function insertStudent()
    {
        $folder = 'images/' . $this->image;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $folder)) {
            $sql = "INSERT INTO `studentrecord` (`name`, `email`, `id`, `gender`, `age`, `image`) 
                    VALUES ('$this->name', '$this->email', '$this->id', '$this->gender', '$this->age', '$this->image')";
            return $this->connection->query($sql);
        }
        return false;
    }

    public function updateStudent($sno)
    {
        $sql = "";
        if (isset($_FILES['imageEdit']) && !$_FILES['imageEdit']['error']) {
            $folder = 'images/' . $this->image;
            if (move_uploaded_file($_FILES['imageEdit']['tmp_name'], $folder)) {
                $sql = "UPDATE `studentrecord` SET `name`='$this->name', `email`='$this->email', `id`='$this->id',
                        `age`='$this->age', `gender`='$this->gender', `image`='$this->image' WHERE `srno`='$sno'";
            }
        } else {
            $sql = "UPDATE `studentrecord` SET `name`='$this->name', `email`='$this->email', `id`='$this->id',
                    `age`='$this->age', `gender`='$this->gender' WHERE `srno`='$sno'";
        }

        return $this->connection->query($sql);
    }

    public function deleteStudent($sno)
    {
        $sql = "DELETE FROM `studentrecord` WHERE `srno` = '$sno'";
        return $this->connection->query($sql);
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}

// Instantiate the Student class
$student = new Student("localhost", "root", "", "student");

// Handle Insertions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['snoEdit'])) {
    $student->name = $_POST['name'];
    $student->email = $_POST['email'];
    $student->id = $_POST['idNumber'];
    $student->age = $_POST['age'];
    $student->gender = $_POST['gender'];
    $student->image = $_FILES['image']['name'];

    if ($student->insertStudent()) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        Your Record has been inserted successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } else {
        echo '<div class="alert alert-danger">Error inserting record.</div>';
    }
}

// Handle Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['snoEdit'])) {
    $student->name = $_POST['nameEdit'];
    $student->email = $_POST['emailEdit'];
    $student->id = $_POST['idEdit'];
    $student->age = $_POST['ageEdit'];
    $student->gender = $_POST['genderEdit'];
    $student->image = $_FILES['imageEdit']['name'];

    if ($student->updateStudent($_POST['snoEdit'])) {
        echo '<div class="alert alert-primary alert-dismissible fade show" role="alert">
        Your Record has been updated successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } else {
        echo '<div class="alert alert-danger">Error updating record.</div>';
    }
}

// Handle Deletion
if (isset($_GET['delete'])) {
    if ($student->deleteStudent($_GET['delete'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        Your Record has been deleted successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } else {
        echo '<div class="alert alert-danger">Error deleting record.</div>';
    }
}
?>
