<?php
    if(isset($_SESSION['errors'])){
        
    foreach($_SESSION['errors'] as $error ){
    ?>
    <div class="alert alert-danger w-50 "> <?php echo $error ?></div>
    <?php
    }
     unset($_SESSION['errors']);
    }
    ?>