<!-- Modal Đăng nhập -->
<div class="modal fade" id="LoginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Login form</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="logincode.php" method="POST">
        <div class="modal-body">
          <div class="form-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Enter email id">
          </div>
          <div class="form-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Enter Password">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="login_btn">Login</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Đăng ký -->
<div class="modal fade" id="RegisterModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="registerModalLabel">Register form</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="registercode.php" method="POST">
        <div class="modal-body">
          <div class="form-group mb-3">
            <input type="text" name="fullname" class="form-control" placeholder="Enter Full Name" required>
          </div>
          <div class="form-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
          </div>
          <div class="form-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Enter Password" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="register_btn">Register</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Thanh điều hướng -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">WEB IT</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="new.php">Add New</a>
        </li>
        <?php if (!isset($_SESSION['auth_user_id'])) { ?>
          <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#LoginModal">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#RegisterModal">Register</a>
          </li>
        <?php } else { ?>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="tb.php">Thông báo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        <?php } ?>
        <li class="nav-item">
          <a class="nav-link" href="#"><?php if (isset($_SESSION['authuser_name'])) echo $_SESSION['authuser_name']; ?></a>
        </li>
      </ul>
    </div>
  </div>
</nav>
