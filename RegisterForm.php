<?php
session_start();
include('database.php');

// Fetch all roles and their volunteer counts
$roleQuery = "
    SELECT r.RoleID, r.RoleName, COUNT(v.VolunteerID) AS VolunteerCount 
    FROM Role r 
    LEFT JOIN Volunteer v ON r.RoleID = v.RoleID 
    WHERE r.RoleID != 5 AND r.RoleID != 8 
    GROUP BY r.RoleID
";
$roleResult = mysqli_query($conn, $roleQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>

  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-9/assets/css/login-9.css">
  <link href="img/favicon.ico" rel="icon">

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Icon Font Stylesheet -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Libraries Stylesheet -->
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

  <!-- Customized Bootstrap Stylesheet -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Template Stylesheet -->
  <link href="css/style.css" rel="stylesheet">
</head>

<body>
  <!-- Registration Form -->
  <form method="POST" action="RegisterFormDB.php" onsubmit="return validateForm()">
    <section class="p-3 p-md-4 p-xl-5">
      <div class="container">
        <div class="card border-light-subtle shadow-sm">
          <div class="row g-0">
            <div class="col-12 col-md-6 text-center" style="background-color: #fab702;">
              <div class="d-flex align-items-center justify-content-center h-100">
                <div class="col-10 col-xl-8 py-3">
                  <h1 class="m-0 text-end"><img src="Logo FTMK.png" alt="FTMK Food Share Logo" style="height: 40px;"> FoodShare</h1>
                  <hr class="border-primary-subtle mb-4">
                  <h2 class="h1 mb-4">The Best Platform for Sharing Food</h2>
                  <p class="lead mb-5">FTMK FoodShare helps connect individuals to share surplus food, promote sustainability, and support those in need within the faculty community.</p>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="card-body p-3 p-md-4 p-xl-5">
                <div class="row">
                  <div class="col-12">
                    <div class="mb-5">
                      <h2 class="h3">Registration - Volunteer</h2>
                      <h3 class="fs-6 fw-normal text-secondary m-0">Enter your details to register</h3>
                    </div>
                  </div>
                </div>
                <div class="row gy-3 gy-md-4 overflow-hidden">
                  <div class="col-12">
                    <label for="matricNo" class="form-label">Matric Number<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="MatricNo" maxlength="10" placeholder="Matric Number" required pattern="[A-Za-z0-9]+" title="Only letters and numbers are allowed." oninput="this.value = this.value.replace(/[^A-Za-z0-9]/g, '')">
                    <small class="form-text text-muted">Include only letters and numbers. e.g. B032210369</small>
                  </div>

                  <div class="col-12">
                    <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="Name" placeholder="Full Name" maxlength="100" required pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed." oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
                  </div>

                  <div class="col-12">
                    <label for="contactNumber" class="form-label">Contact Number<span class="text-danger">*</span></label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="ContactNo1" maxlength="3" placeholder="01x" required pattern="[0-9]+" title="Only numbers are allowed." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                      <span class="input-group-text">-</span>
                      <input type="text" class="form-control" name="ContactNo2" maxlength="7" placeholder="xxxxxxx" required pattern="[0-9]+" title="Only numbers are allowed." oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="role" class="form-label">Role<span class="text-danger">*</span></label>
                    <div class="select-box">
                      <select class="form-control" name="RoleID" required>
                        <option value="" disabled selected>-Choose Role-</option>
                        <?php
                        if ($roleResult) {
                          while ($row = mysqli_fetch_assoc($roleResult)) {
                            $disabled = '';
                            if (($row['RoleID'] == 1 || $row['RoleID'] == 2) && $row['VolunteerCount'] >= 1) {
                              $disabled = 'disabled';
                            } elseif (($row['RoleID'] == 6 || $row['RoleID'] == 7) && $row['VolunteerCount'] >= 2) {
                              $disabled = 'disabled';
                            }
                            echo "<option value='{$row['RoleID']}' $disabled>{$row['RoleName']}</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="programme" class="form-label">Programme<span class="text-danger">*</span></label>
                    <div class="select-box">
                      <select class="form-control" name="ProgrammeID" required>
                        <option value="" disabled selected>-Choose Programme-</option>
                        <?php
                        $programmeQuery = "SELECT ProgrammeID, ProgrammeName FROM Programme";
                        $programmeResult = mysqli_query($conn, $programmeQuery);

                        if ($programmeResult) {
                          while ($row = mysqli_fetch_assoc($programmeResult)) {
                            echo "<option value='{$row['ProgrammeID']}'>{$row['ProgrammeName']}</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="yearOfStudy" class="form-label">Year of Study<span class="text-danger">*</span></label>
                    <select class="form-control" name="YearOfStudy" required>
                      <option value="" disabled selected>-Select Year-</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                    </select>
                  </div>

                  <div class="col-12">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="input-box">
                          <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
                          <input type="password" class="form-control" name="Password" maxlength="16" placeholder="Enter your password" required />
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="input-box">
                          <label for="confirmPassword" class="form-label">Confirm Password<span class="text-danger">*</span></label>
                          <input type="password" class="form-control" name="ConfirmPassword" placeholder="Confirm your password" required />
                        </div>
                      </div>
                    </div>
                    <small class="form-text text-muted">Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.</small>
                  </div>

                  <br>
                  <br>

                  <div class="row mt-4">
                    <!-- Sign Up Button -->
                    <div class="col-12 d-grid">
                      <button class="btn bsb-btn-xl btn-dark btn-lg" name="submit" type="submit">Sign Up</button>
                    </div>
                  </div>

                  <div class="row mt-4">
                    <div class="col-12 d-grid">
                      <?php echo "<p>Already have an account? <a href='login.php'>Log in</a></p>"; ?>
                    </div>
                  </div>

                  <?php
                  // Success message
                  if (isset($_SESSION['status'])) {
                  ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                      <?php echo $_SESSION['status']; ?>
                    </div>
                  <?php
                    unset($_SESSION['status']);
                  }

                  // Email already use
                  if (isset($_SESSION['EmailMessage'])) {
                  ?>
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                      <strong>Failed to register</strong> <?php echo $_SESSION['EmailMessage']; ?>
                    </div>
                  <?php
                    unset($_SESSION['EmailMessage']);
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
    </section>
  </form>

  <script>
    function validateForm() {
      const matricNo = document.querySelector('input[name="MatricNo"]');
      const name = document.querySelector('input[name="Name"]');
      const contactNo1 = document.querySelector('input[name="ContactNo1"]');
      const contactNo2 = document.querySelector('input[name="ContactNo2"]');

      // Validate Matric Number
      const matricNoPattern = /^[A-Za-z0-9]+$/;
      if (!matricNoPattern.test(matricNo.value)) {
        alert("Matric Number can only contain letters and numbers.");
        return false;
      }

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