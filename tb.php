<?php
session_start();
include("includes/header.php");
include("includes/navbar.php");
include("dbcon.php");

$user_id = $_SESSION['auth_user_id']; 

// Truy vấn để kiểm tra xem người dùng có bài viết nào không
$sql_check_bv = "SELECT COUNT(*) AS total_posts FROM baiviet WHERE id_users = ?";
$stmt_check = $conn->prepare($sql_check_bv);
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row_check = $result_check->fetch_assoc();

if ($row_check['total_posts'] > 0) {
    // Người dùng có bài viết, tiếp tục lấy thông tin bài viết
    $sql_tbbv = "SELECT baiviet.id_baiviet, baiviet.tenbaiviet, baiviet.hinhanh, users.hovaten 
                  FROM baiviet 
                  JOIN users ON baiviet.id_users = users.id 
                  WHERE baiviet.id_users = ?";
    $stmt_bv = $conn->prepare($sql_tbbv);
    $stmt_bv->bind_param("i", $user_id);
    $stmt_bv->execute();
    $lktb = $stmt_bv->get_result();

    // Kiểm tra nếu có bài viết
    if (mysqli_num_rows($lktb) > 0) {
        while ($row_baiviet = mysqli_fetch_assoc($lktb)) {
            $id_baiviet = $row_baiviet['id_baiviet']; // Lấy id_baiviet
            $ten_baiviet = $row_baiviet['tenbaiviet']; // Lấy tên bài viết
            
            // Kiểm tra bình luận cho bài viết
            $sql_comments = "SELECT comments.*, users.hovaten 
                             FROM comments 
                             JOIN users ON comments.user_id = users.id 
                             WHERE comments.id_baiviet = ? 
                             ORDER BY comments.commented_on DESC"; // Sắp xếp theo thời gian bình luận
            $stmt_comments = $conn->prepare($sql_comments);
            $stmt_comments->bind_param("i", $id_baiviet);
            $stmt_comments->execute();
            $result_comments = $stmt_comments->get_result();

            // Kiểm tra và hiển thị thông báo bình luận
            if ($result_comments->num_rows > 0) {
                while ($row_comment = $result_comments->fetch_assoc()) {
                    // Chỉ hiển thị bình luận nếu không phải là của người dùng hiện tại
                    if ($row_comment['user_id'] != $user_id) {
                        echo "<p><strong><a href='bl.php?idbl={$id_baiviet}'>{$row_comment['hovaten']}</strong> bình luận vào bài viết <strong>{$ten_baiviet}</strong> vào lúc <strong>{$row_comment['commented_on']}</a></strong>:</p>";
                        echo "<p>{$row_comment['msg']}</p><hr>";
                    }
                }
            } else {
                echo "<p>Không có bình luận nào cho bài viết <strong>{$ten_baiviet}</strong> của bạn.</p><hr>";
            }
            
            // Kiểm tra phản hồi cho từng bình luận
            $sql_replies = "SELECT comment_replies.*, users.hovaten 
                            FROM comment_replies 
                            JOIN users ON comment_replies.user_id = users.id 
                            WHERE comment_replies.comment_id IN (SELECT id FROM comments WHERE id_baiviet = ?) 
                            ORDER BY comment_replies.commented_on  DESC"; // Sắp xếp phản hồi theo thời gian
            $stmt_replies = $conn->prepare($sql_replies);
            $stmt_replies->bind_param("i", $id_baiviet);
            $stmt_replies->execute();
            $result_replies = $stmt_replies->get_result();

            // Hiển thị phản hồi
            if ($result_replies->num_rows > 0) {
                while ($row_reply = $result_replies->fetch_assoc()) {
                    if ($row_reply['user_id'] != $user_id) {
                        echo "<p><strong><a href='bl.php?idbl={$id_baiviet}'>{$row_reply['hovaten']}</strong> đã trả lời bình luận vào bài viết <strong>{$ten_baiviet}</strong> vào lúc <strong>{$row_reply['commented_on']}</a></strong>:</p>";
                        echo "<p>{$row_reply['reply_msg']}</p><hr>";
                    }
                }
            }

            // Đóng câu lệnh bình luận
            $stmt_comments->close();
            // Đóng câu lệnh phản hồi
            $stmt_replies->close();
        }
    } else {
        echo "Không có bài viết nào.";
    }

    
    $stmt_bv->close();
} else {
    echo "Bạn chưa có bài viết nào.";
}


$conn->close();
?>
<?php
include("includes/footer.php");
?>
