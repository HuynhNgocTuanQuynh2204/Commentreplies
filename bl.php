<?php
session_start();
include("includes/header.php");
include("includes/navbar.php");
include("dbcon.php");
?>
<style>
.comment-container {
    display: none;
}
</style>
<?php
if (isset($_SESSION['status'])) {
?>
<div class="alert"><?php echo $_SESSION['status']; ?></div>
<?php
unset($_SESSION['status']); // Xóa session status sau khi hiển thị để tránh hiển thị lại ở các lần tải trang sau
}
?>

<?php
$idbl=$_GET['idbl'];
$sql_lietke_bv = "SELECT * FROM baiviet, users WHERE baiviet.id_users = users.id AND baiviet.id_baiviet = $idbl";
$query_lietke_bv = mysqli_query($conn, $sql_lietke_bv);
?>
<?php 
$i = 0;
while ($row = mysqli_fetch_array($query_lietke_bv)) {
    $i++;
    
    // Truy vấn để đếm bình luận và phản hồi cho bài viết hiện tại
    $post_id = $row['id_baiviet'];
    $sql_count_comments = "SELECT COUNT(*) AS total_comments FROM comments WHERE id_baiviet = $post_id";
    $sql_count_replies = "SELECT COUNT(*) AS total_replies FROM comment_replies WHERE comment_id IN (SELECT id FROM comments WHERE id_baiviet = $post_id)";
    
    $result_comments = mysqli_query($conn, $sql_count_comments);
    $result_replies = mysqli_query($conn, $sql_count_replies);
    
    $total_comments = mysqli_fetch_assoc($result_comments)['total_comments'];
    $total_replies = mysqli_fetch_assoc($result_replies)['total_replies'];
    
    $total_all_comments = $total_comments + $total_replies; // Tổng số bình luận và phản hồi
?>
<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-header">
                        <h4><?php echo $row['hovaten'] ?></h4>
                        <h4><?php echo $row['tenbaiviet'] ?></h4>
                    </div>
                    <div class="card-body">
                        <img src="uploads/<?php echo $row['hinhanh'] ?>" width="150px">
                        <hr>
                        <div class="main-comment">

                            <div id="error_status"></div>
                            <input type="hidden" class="post_id" value="<?php echo $row['id_baiviet']; ?>">
                            <textarea class="comment_textbox form-control mb-1" rows="2"></textarea>
                            <button type="button" class="btn btn-primary add_comment_btn">Comment</button>
                            <hr>
                            <p><strong>Tổng số bình luận: </strong><?php echo $total_all_comments; ?></p> <!-- Hiển thị tổng số bình luận -->
                            <button class="toggleButton" data-id="<?php echo $row['id_baiviet']; ?>">Xem bình luận</button>
                            <div class="comment-container" id="comment-container-<?php echo $row['id_baiviet']; ?>" data-post-id="<?php echo $row['id_baiviet']; ?>">
                                <!-- Bình luận sẽ được tải vào đây -->
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php 
}
?>
<?php
include("includes/footer.php");
?>
