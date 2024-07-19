<?php
    if(isset($_SESSION['success'])){?>
    <div class="alert alert-success w-50 margin-auto"> <?php echo $_SESSION['success'] ?></div>
    <?php
    } unset($_SESSION['success']);
    ?>