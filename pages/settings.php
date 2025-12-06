
<div class="pagetitle">
  <h1>Profile</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="../main.php">Home</a></li>
      <li class="breadcrumb-item active">Profile</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section profile">
  <div class="row">
    <div class="col-xl-4">

      <div class="card">
        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
          <h2><?php echo $_SESSION['full_name'] ?></h2>
          <h3><?php echo $_SESSION['position'] ?></h3>
        </div>
      </div>

    </div>

    <div class="col-xl-8">

      <div class="card">
        <div class="card-body pt-3">
          <!-- Bordered Tabs -->
          <ul class="nav nav-tabs nav-tabs-bordered">

            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Profile Details</button>
            </li>

            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
            </li>

            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
            </li>

          </ul>
          <div class="tab-content pt-2">

            <div class="tab-pane fade show active profile-overview" id="profile-overview">

              <div class="row">
                <div class="col-lg-3 col-md-4 label ">Full Name</div>
                <div class="col-lg-9 col-md-8"><?php echo $_SESSION['full_name'] ?></div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label">Employee #</div>
                <div class="col-lg-9 col-md-8"><?php echo $_SESSION['employee_number'] ?></div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label">Position</div>
                <div class="col-lg-9 col-md-8"><?php echo $_SESSION['position'] ?></div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label">Employment</div>
                <div class="col-lg-9 col-md-8"><?php echo $_SESSION['employment_type'] ?></div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label">Address</div>
                <div class="col-lg-9 col-md-8"><?php echo $_SESSION['address'] ?></div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label">Phone</div>
                <div class="col-lg-9 col-md-8"><?php echo $_SESSION['phone'] ?></div>
              </div>

              <div class="row">
                <div class="col-lg-3 col-md-4 label">Email</div>
                <div class="col-lg-9 col-md-8"><?php echo $_SESSION['email'] ?></div>
              </div>

            </div>

            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

              <!-- Profile Edit Form -->
              <form action="./backend/update_profile.php" method="POSt">

                <div class="row mb-3">
                  <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="fullName" type="text" class="form-control" id="fullName"
                          value="<?php echo $_SESSION['full_name']; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="position" class="col-md-4 col-lg-3 col-form-label">Position</label>
                  <div class="col-md-8 col-lg-9">
                  <input name="position" type="text" class="form-control" id="position"
                        value="<?php echo $_SESSION['position']; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="employment" class="col-md-4 col-lg-3 col-form-label">Employment</label>
                  <div class="col-md-8 col-lg-9">
                  <input name="employment" type="text" class="form-control" id="employment"
                        value="<?php echo $_SESSION['employment_type']; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                  <div class="col-md-8 col-lg-9">
                  <input name="address" type="text" class="form-control" id="Address"
                        value="<?php echo $_SESSION['address']; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                  <div class="col-md-8 col-lg-9">
                  <input name="phone" type="text" class="form-control" id="Phone"
                        value="<?php echo $_SESSION['phone']; ?>">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                  <div class="col-md-8 col-lg-9">
                  <input name="email" type="email" class="form-control" id="Email"
                        value="<?php echo $_SESSION['email']; ?>">
                  </div>
                </div>

                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </form><!-- End Profile Edit Form -->

            </div>

            <div class="tab-pane fade pt-3" id="profile-change-password">
              <!-- Change Password Form -->
              <form action="./backend/change_password.php" method="POST">

                <div class="row mb-3">
                  <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="password" type="password" class="form-control" id="currentPassword">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="newpassword" type="password" class="form-control" id="newPassword">
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                  <div class="col-md-8 col-lg-9">
                    <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                  </div>
                </div>

                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
              </form><!-- End Change Password Form -->

            </div>

          </div><!-- End Bordered Tabs -->

        </div>
      </div>

    </div>
  </div>
</section>