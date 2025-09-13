<?php
// Template Name: Register
get_header();
?>



<main>
    <br>
    <br>

    <div class="row">
        <div class="col-3"></div>
        <div class="col-6">

           <form id="registerForm" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="firstName" class="form-label">First Name</label>
    <input type="text" class="form-control" id="firstName" name="firstName" required>
  </div>

  <div class="mb-3">
    <label for="lastName" class="form-label">Last Name</label>
    <input type="text" class="form-control" id="lastName" name="lastName" required>
  </div>

  <div class="mb-3">
    <label for="username" class="form-label">Username</label>
    <input type="text" class="form-control" id="username" name="username" required>
  </div>

  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="email" class="form-control" id="email" name="email" required>
    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
  </div>

  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password" name="password" required>
  </div>

  <div class="mb-3">
    <label for="formFile" class="form-label">Profile Image</label>
    <input class="form-control" type="file" id="formFile" name="profile_image">
  </div>

  <button type="submit" class="btn btn-dark">Register</button>
</form>

<div id="responseMsg"></div>


        </div>
        <div class="col-3"></div>
    </div>

</main>


<script>
jQuery(document).ready(function($){
    $('#registerForm').on('submit', function(e){
        e.preventDefault();

        let formData = new FormData(this);
        formData.append('action', 'custom_user_registration');

        $.ajax({
            type: 'POST',
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.success){
                    $('#responseMsg').html('<p style="color:green;">' + response.data.message + '</p>');
                    $('#registerForm')[0].reset();
                } else {
                    $('#responseMsg').html('<p style="color:red;">' + response.data.message + '</p>');
                }
            },
            error: function(){
                $('#responseMsg').html('<p style="color:red;">Something went wrong.</p>');
            }
        });
    });
});
</script>




<?php get_footer(); ?>