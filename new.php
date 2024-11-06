<?php
session_start();
include("includes/header.php");
include("includes/navbar.php");
include("dbcon.php");
?>

<?php
if (isset($_SESSION['status'])) {
?>
<div class="alert"><?php echo $_SESSION['status']; ?></div>
<?php
unset($_SESSION['status']); 
}
?>
<?php
if (!isset($_SESSION['auth_user_id'])) {
    echo '<script type="text/javascript">
            alert("Bạn cần đăng nhập mới có thể đăng bài");
            window.location.href = "index.php";
          </script>';
}
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Thêm bài viết</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="xulythembaiviet.php" enctype="multipart/form-data">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td><label for="tenbaiviet" class="form-label">Tên bài viết</label></td>
                                        <td><input type="text" class="form-control" id="tenbaiviet" name="tenbaiviet"
                                                required></td>
                                    </tr>
                                    <tr>
                                        <td><label for="hinhanh" class="form-label">Hình ảnh</label></td>
                                        <td><input type="file" class="form-control" id="hinhanh" name="hinhanh"
                                                required></td>
                                    </tr>
                                </tbody>
                            </table>
                            <tr>
                                <td  colspan="2"><input type="submit"name="thembaiviet" value="Thêm bài viết" class="btn btn-primary"></td>
                            </tr>
                        </form>
                        <hr>
                        <div id="error_status"></div>
                        <?php
                            $sql_lietke_bv = "SELECT * FROM baiviet, users WHERE baiviet.id_users = users.id AND baiviet.id_users = ? ORDER BY baiviet.id_baiviet DESC";
                            $stmt = $conn->prepare($sql_lietke_bv);
                            $stmt->bind_param("i", $_SESSION['auth_user_id']); 
                            $stmt->execute();
                            $query_lietke_bv = $stmt->get_result();
                            ?>

                        <h6 style="text-align: center;text-transform: uppercase;font-weight: bold;">Liệt kê bài viết
                        </h6>
                        <table style="width: 100%;" border="1" style="border-collapse: collapse;">
                            <tr>
                                <th>ID</th>
                                <th>Tên bài viết</th>
                                <th>Hình ảnh</th>
                                <th>Quản lý</th>
                            </tr>
                            <?php 
                                $i=0;
                                while($row = mysqli_fetch_array($query_lietke_bv)){
                                $i++;  
                                ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $row['tenbaiviet'] ?></td>
                                <td><img src="uploads/<?php echo $row['hinhanh'] ?>"
                                        width="150px"></td>
                                <td>
                                    <a class="btn btn-primary"
                                        href="xulythembaiviet.php?idbaiviet=<?php echo  $row['id_baiviet']?>">Xóa</a>
                                    |
                                    <a class="btn btn-secondary"
                                        href="edit.php?idbaiviet=<?php echo  $row['id_baiviet']?>">Sửa</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php
include("includes/footer.php");
?>