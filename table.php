<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iRecord - Student Record System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
  <?php 
  include 'partials/_nav.php';
  include 'partials/CRUD.php';
  $connection = $student->getConnection(); 
   ?>
  <div class="container my-4">
    <h2 class="text-center my-4">iRecord - Student Records</h2>
  <!-- Table -->
  <?php 
    $result = $connection->query("SELECT * FROM `studentrecord`"); // Use $connection 
    $sno = 0; 

    // Display the table
    echo "<table class='table table-bordered'>
            <thead class='thead-dark'>
                <tr>
                    <th>Sr.No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Id</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>";
    
    while ($row = $result->fetch_assoc()) {
        $sno++;
        echo "<tr>
                <td>". $sno . "</td>
                <td>". $row['name'] . "</td>
                <td>". $row['email'] . "</td>
                <td>". $row['id']. "</td>
                <td>". $row['gender']. "</td>
                <td>". $row['age'] . "</td>
                <td><img src='images/" . $row['image'] . "' width='100' alt='Student Image'></td>
                <td>
                    <button class='btn btn-sm btn-primary edit' data-bs-toggle='modal' data-bs-target='#editModal' id='". $row['srno'] ."'>Edit</button> 
                    <button class='btn btn-sm btn-danger delete' id='d". $row['srno'] ."'>Delete</button>
                </td>
              </tr>";
    }
    echo "</tbody></table>";
    ?>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit this Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/studentrecord/table.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="mb-3">
                            <label for="nameEdit" class="form-label"><strong>Name</strong></label>
                            <input type="text" class="form-control" name="nameEdit" id="nameEdit">
                        </div>
                        <div class="mb-3">
                            <label for="emailEdit" class="form-label"><strong>Email</strong></label>
                            <input type="text" class="form-control" name="emailEdit" id="emailEdit">
                        </div>
                        <div class="mb-3">
                            <label for="idEdit" class="form-label"><strong>Id</strong></label>
                            <input type="text" class="form-control" name="idEdit" id="idEdit">
                        </div>
                        <div class="mb-3">
                            <label for="ageEdit" class="form-label"><strong>Age</strong></label>
                            <input type="text" class="form-control" name="ageEdit" id="ageEdit">
                        </div>
                        <div class="mb-3">
                            <label for="genderEdit" class="form-label"><strong>Gender</strong></label>
                            <input type="text" class="form-control" name="genderEdit" id="genderEdit">
                        </div>
                        <div class="mb-3">
                            <label for="imageEdit" class="form-label"><strong>Image</strong></label>
                            <input type="file" class="form-control" name="imageEdit" id="imageEdit" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Record</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    let nameEdit = document.getElementById('nameEdit');
    let emailEdit = document.getElementById('emailEdit');
    let snoEdit = document.getElementById('snoEdit');
    let idEdit = document.getElementById('idEdit');
    let ageEdit = document.getElementById('ageEdit');
    let genderEdit = document.getElementById('genderEdit');

    // Handle Edit Modal
    document.querySelectorAll('.edit').forEach((element) => {
        element.addEventListener("click", (e) => {
            let tr = e.target.closest('tr');
            let name = tr.getElementsByTagName("td")[1].innerText;
            let email = tr.getElementsByTagName("td")[2].innerText;
            let id = tr.getElementsByTagName("td")[3].innerText;
            let gender = tr.getElementsByTagName("td")[4].innerText;
            let age = tr.getElementsByTagName("td")[5].innerText;

            nameEdit.value = name;
            emailEdit.value = email;
            idEdit.value = id;
            genderEdit.value = gender;
            ageEdit.value = age;
            snoEdit.value = e.target.id;
        });
    });

    // Handle Delete
    document.querySelectorAll('.delete').forEach((element) => {
        element.addEventListener("click", (e) => {
            let sno = e.target.id.substr(1);
            if (confirm("Are you sure you want to delete this record?")) {
                window.location = `/studentrecord/table.php?delete=${sno}`;
            }
        });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>