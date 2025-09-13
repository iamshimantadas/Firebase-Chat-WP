<?php
// Template Name: Login
get_header();
?>

<main>
    <br>
    <br>

    <div class="row">
        <div class="col-3"></div>
        <div class="col-6">

            <form id="loginForm">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>

            <br>
            <div id="loginResponse"></div>

        </div>
        <div class="col-3"></div>
    </div>

</main>



<script>
jQuery(document).ready(function($){
    $('#loginForm').on('submit', function(e){
        e.preventDefault();

        let formData = {
            action: 'custom_user_login',
            email: $('#exampleInputEmail1').val(),
            password: $('#exampleInputPassword1').val()
        };

        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            data: formData,
            success: function(response){
                if(response.success){
                    $('#loginResponse').html('<p style="color:green;">' + response.data.message + '</p>');
                    window.location.href = '<?php echo get_site_url(); ?>/chat'; // redirect to chat
                } else {
                    $('#loginResponse').html('<p style="color:red;">' + response.data.message + '</p>');
                }
            },
            error: function(){
                $('#loginResponse').html('<p style="color:red;">Something went wrong.</p>');
            }
        });
    });
});
</script>


<?php get_footer(); ?>