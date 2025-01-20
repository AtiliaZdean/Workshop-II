<?php
session_start();
include('database.php');

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || !isset($_SESSION['VolunteerID'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data from the database
$volunteerID = $_SESSION['VolunteerID']; // Get VolunteerID from session
$userDataQuery = "SELECT Name, ContactNo, YearOfStudy, RoleID, ManagerID FROM Volunteer WHERE VolunteerID = ?";
$stmt = $conn->prepare($userDataQuery);
$stmt->bind_param("i", $volunteerID);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <form method="POST" action="UpdateFormDB.php">
        <section class="p-3 p-md-4 p-xl-5">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="row g-0">
                        <div class="col-12 col-md-6 text-center" style="background-color: #fab702;">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="col-10 col-xl-8 py-3">
                                    <h1 class="m-0"><img src="Logo FTMK.png" alt="FTMK Food Share Logo" style="height: 40px;"> FoodShare</h1>
                                    <hr class="border-primary-subtle mb-4">
                                    <h2 class="h1 mb-4">The Best Platform for Sharing Food</h2>
                                    <p class="lead">FTMK FoodShare helps connect individuals to share surplus food, promote sustainability, and support those in need within the faculty community.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card-body p-3 p-md-4 p-xl-5">
                                <div class="mb-5">
                                    <h2 class="h3">Update - Volunteer</h2>
                                    <h3 class="fs-6 fw-normal text-secondary m-0">Enter your details to update</h3>
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" id="name" name="Name" class="form-control" value="<?php echo htmlspecialchars($userData['Name']); ?>" maxlength="100" required pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed." oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                                </div>

                                <div class="mb-3">
                                    <label for="contactNo" class="form-label">Contact Number</label>
                                    <div class="input-group">
                                        <input type="text" id="contactNo1" name="ContactNo1" class="form-control" value="<?php echo htmlspecialchars(substr($userData['ContactNo'], 0, 3)); ?>" maxlength="3" required pattern="[0-9]+" title="Only numbers are allowed." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        <span class="input-group-text">-</span>
                                        <input type="text" id="contactNo2" name="ContactNo2" class="form-control" value="<?php echo htmlspecialchars(substr($userData['ContactNo'], 4)); ?>" maxlength="7" required pattern="[0-9]+" title="Only numbers are allowed." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="yearOfStudy" class="form-label">Year of Study</label>
                                    <select class="form-control" id="yearOfStudy" name="YearOfStudy" required>
                                        <option value="" disabled>Select Year of Study</option>
                                        <option value="1" <?php echo $userData['YearOfStudy'] == 1 ? 'selected' : ''; ?>>1</option>
                                        <option value="2" <?php echo $userData['YearOfStudy'] == 2 ? 'selected' : ''; ?>>2</option>
                                        <option value="3" <?php echo $userData['YearOfStudy'] == 3 ? 'selected' : ''; ?>>3</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password (Leave blank to keep current password)</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="password" id="password" name="Password" class="form-control" placeholder="Enter new password">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="password" id="confirmPassword" name="ConfirmPassword" class="form-control" placeholder="Re-enter new password">
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.</small>
                                </div>

                                <div class="mt-4 d-grid gap-2">
                                    <button type="submit" name="updateProfile" class="btn btn-dark btn-lg">Update</button>
                                    <?php echo "<p><a href='volunteer.php'>Back</a></p>"; ?>
                                </div>

                                <?php if (isset($_SESSION['status'])): ?>
                                    <div class="alert alert-success mt-3"><?php echo $_SESSION['status'];
                                                                            unset($_SESSION['status']); ?></div>
                                <?php endif; ?>

                                <?php if (isset($_SESSION['EmailMessage'])): ?>
                                    <div class="alert alert-danger mt-3"><?php echo $_SESSION['EmailMessage'];
                                                                            unset($_SESSION['EmailMessage']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
</body>

</html>

</div>
</div>
</section>
</form>
<script>
    function validateForm() {
      const name = document.querySelector('input[name="Name"]');
      const contactNo1 = document.querySelector('input[name="ContactNo1"]');
      const contactNo2 = document.querySelector('input[name="ContactNo2"]');

      // Validate Name
      const namePattern = /^[A-Za-z\s]+$/;
      if (!namePattern.test(name.value)) {
        alert("Name cannot contain numbers.");
        return false;
      }

      // Validate Contact Number
      const contactNoPattern = /^[0-9]+$/;
      if (!contactNoPattern.test(contactNo1.value) || !contactNoPattern.test(contactNo2.value)) {
        alert("Contact Number can only contain numbers.");
        return false;
      }

      return true; // All validations passed
    }
  </script>
</body>

</html>