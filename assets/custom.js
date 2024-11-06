$(document).ready(function() {
    load_comment();

    $(document).on('click', '.toggleButton', function() {
        let post_id = $(this).siblings('.post_id').val();
        let commentContainer = $('#comment-container-' + post_id);
        let button = $(this);
    
        // Kiểm tra trạng thái hiển thị và thay đổi tương ứng
        if (commentContainer.is(':visible')) {
            commentContainer.hide();
            button.text('Xem bình luận');
        } else {
            commentContainer.show();
            button.text('Đóng');
            load_comment(post_id); // Gọi hàm tải bình luận với post_id
        }
    });
    
    function load_comment(post_id) {
        $.ajax({
            type: "POST",
            url: "code.php",
            data: {
                'comment_load_data': true,
                'post_id': post_id // Gửi post_id
            },
            success: function(response) {
                $('#comment-container-' + post_id).html(""); 
                $.each(response, function(key, value) {
                    $('#comment-container-' + post_id).append('\
                    <div class="reply_box border p-2 mb-2">\
                        <h6 class="border-bottom d-inline">' + value.user['hovaten'] +
                        ' : ' + value.cmt['commented_on'] +
                        ' </h6>\
                        <p class="para">' + value.cmt['msg'] +
                        ' </p>\
                        <button value="' + value.cmt['id'] +
                        '" class="badge btn btn-warning text-dark reply_btn">Reply</button>\
                        <button value="' + value.cmt['id'] +
                        '" class="badge btn btn-danger view_reply_btn">View Replies</button>\
                        <div class="ml-4 reply_section"></div>\
                    </div>');
                });
            }
        });
    }
    
    $(document).on('click', '.reply_btn', function() {

        var thisClicked = $(this);
        var cmt_id = thisClicked;


        $('.reply_section').html("");
        thisClicked.closest('.reply_box').find('.reply_section').html('\
                        <input type="text" class="reply-msg form-control my-2" placeholder="Reply">\
                        <div class="text-end">\
                            <div class="btn btn-sm btn-primary reply_add_btn">Reply</div>\
                            <div class="btn btn-sm btn-danger reply_cancel_btn">Cancel</div>\
                        </div>');

    });


    $(document).on('click', '.reply_cancel_btn', function() {
        $('.reply_section').html("");
    });


    $(document).on('click', '.reply_add_btn', function(e) {
        e.preventDefault();
        var thisClicked = $(this);
        var cmt_id = thisClicked.closest('.reply_box').find('.reply_btn').val();
        var reply = thisClicked.closest('.reply_box').find('.reply-msg').val();

        var data = {
            'comment_id': cmt_id,
            'reply_msg': reply,
            'add_reply': true
        };

        $.ajax({
            type: "POST",
            url: "code.php",
            data: data,
            success: function(response) {
                alert(response);
            }
        });
    });

    $(document).on('click', '.view_reply_btn', function(e) {

        e.preventDefault();

        var thisClicked = $(this);

        var cmt_id = thisClicked.val();

        $.ajax({
            type: "POST",
            url: "code.php",
            data: {
                'cmt_id': cmt_id,
                'view_comment_data': true
            },
            success: function(response) {
                // console.log(response);
                $('.reply_section').html("")
                $.each(response, function(key, value) {
                    thisClicked.closest('.reply_box').find('.reply_section')
                        .append('\
                <div class="sub_reply_box border-bottom p-2 mb-2">\
                <input type="hidden" class="get_username" value="' + value.user['hovaten'] +
                            '"/>\
                    <h6 class="border-bottom d-inline">' + value.user['hovaten'] +
                            ' : ' + value.cmt['commented_on'] +
                            ' </h6>\
                    <p class="para">' + value.cmt['reply_msg'] +
                            ' </p>\
                    <button value="' + value.cmt['comment_id'] +
                            '" class="badge btn btn-warning text-dark sub_reply_btn">Reply</button>\
                    <div class="ml-4 sub_reply_section"></div>\
                </div>');

                });
            }
        });
    });
    $(document).on('click', '.sub_reply_btn', function(e) {
        e.preventDefault();
        var thisClicked = $(this);
        var cmt_id = thisClicked.val();
        var username = thisClicked.closest('.sub_reply_box').find('.get_username').val();
        $('.sub_reply_section').html("");
        thisClicked.closest('.sub_reply_box').find('.sub_reply_section').append('<div>\
                    <input type="text" value="@' +
            username + '" class="sub_reply_msg form-control my-2" placeholder="Your Reply">\
                </div>\
                <div class="text-end">\
                        <button class="btn btn-sm btn-primary text-dark sub_reply_add_btn">Reply</button>\
                        <button class="btn btn-sm btn-danger text-dark sub_reply_cancel_btn">Cancel</button>\
                    </div>\
                    ');
    });

    $(document).on('click', '.sub_reply_add_btn', function(e) {
        e.preventDefault();
        var thisClicked = $(this);
        var cmt_id = thisClicked.closest('.sub_reply_box').find('.sub_reply_btn').val();
        var reply = thisClicked.closest('.sub_reply_box').find('.sub_reply_msg').val();
        var data = {
            'cmt_id': cmt_id,
            'reply_msg': reply,
            'add_subreplies': true
        }
        $.ajax({
            type: "POST",
            url: "code.php",
            data: data,
            success: function(response) {
                $('.reply_section').html("")
                alert(response);
            }
        });
    });

    $(document).on('click', '.sub_reply_cancel_btn', function(e) {
        e.preventDefault();
        $('.sub_reply_section').html("");

    });
    $('.add_comment_btn').click(function(e) {
        e.preventDefault();
    
        var msg = $(this).closest('.main-comment').find('.comment_textbox').val();
        var post_id = $(this).closest('.main-comment').find('.post_id').val(); // Lấy id_baiviet
    
        if ($.trim(msg).length == 0) {
            $('#error_status').text('Please type a comment');
        } else {
            $('#error_status').text('');
    
            var data = {
                'msg': msg,
                'post_id': post_id,  // Thêm id_baiviet vào dữ liệu gửi đi
                'add_comment': true
            };
    
            $.ajax({
                type: "POST",
                url: "code.php",
                data: data,
                success: function(response) {
                    alert(response);
                    load_comment(); // Tải lại bình luận sau khi thêm
                }
            });
        }
    });    
});