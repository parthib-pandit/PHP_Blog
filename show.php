<?php require "includes/header.php"; ?>


<?php require "config.php"; ?>


<?php


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $onePost = $conn->query("SELECT * FROM posts WHERE id='$id'");
    $onePost->execute();



    $posts = $onePost->fetch(PDO::FETCH_OBJ);
}


$comments = $conn->query("SELECT * FROM comments WHERE post_id='$id'");
$comments->execute();



$comment = $comments->fetchAll(PDO::FETCH_OBJ);





?>

<div class="card mt-5">
    <div class="card-body">
        <h5 class="card-title"><?php echo $posts->title; ?></h5>
        <p class="card-text"><?php echo $posts->body; ?></p>
    </div>
</div>
</div>

<div class="row">
    <form method="POST" id="comment_data">

        <div class="form-floating">
            <input name="username" type="hidden" value=<?php if(isset($_SESSION['username'])){echo $_SESSION['username'];} ?> class="form-control"
                id="username">
        </div>

        <div class="form-floating">
            <input name="post_id" type="hidden" value=<?php echo $posts->id; ?> class="form-control" id="post_id">
        </div>

        <div class="form-floating mt-4">
            <textarea name="comment" rows="9" class="form-control" id="comment"></textarea>
            <label for="floatingPassword">Comment</label>
        </div>
        <?php if(isset($_SESSION['username'])) : ?>
        <button name="submit" id="submit" class="w-100 btn btn-lg btn-primary mt-5" type="submit">Comment</button>
        <?php endif; ?>
        <?php if(!isset($_SESSION['username'])) {echo "Login to Comment on this thread.";} ?>

        

    </form>

    <div id="msg" class="nothing"></div>
</div>

<div class="card mt-5">
    <?php foreach ($comment as $singlecomment): ?>
    <div class="card-body">
        <h5 class="card-title"><?php echo $singlecomment->username; ?></h5>
        <p class="card-text"><?php echo $singlecomment->comment; ?></p>
        <?php if(isset($_SESSION['username']) AND $_SESSION['username'] == $singlecomment->username) : ?>
        <button class=" btn btn-danger mt-2 delete-btn"
            data-comment-id="<?php echo $singlecomment->id; ?>">Delete</button>
            <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>





<?php require "includes/footer.php"; ?>

<script>
$(document).ready(function() {

    $(document).on('submit', (e) => {
        //alert("form submitted");
        e.preventDefault();
        var formdata = $("#comment_data").serialize() + '&submit=submit';

        $.ajax({
            type: 'post',
            url: 'insert-comments.php',
            data: formdata,

            success: () => {
                //alert('success');
                $("#comment").val(null);
                $("#username").val(null);
                $("#post_id").val(null);
                $("#msg").html("Added Successfully").toggleClass(
                    "alert alert-success bg-success text-white mt-3");
                fetch();
            }
        })

    })

    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('comment-id'); 

        $.ajax({
            type: 'post',
            url: 'delete-comment.php',
            data: {
                delete: 'delete',
                id: id
            },

            success: () => {
                alert(id);
                
                $(this).closest('.card-body').remove();
            }
        });
    });

    const fetch = () => {
        setInterval(() => {
            $("body").load("show.php?id=<?php echo $_GET['id']; ?>")
        }, 4000);
    }
});
</script>