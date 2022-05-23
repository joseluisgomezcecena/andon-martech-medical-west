<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// init para mensajes sms
require_once ("../includes/header.php");

require_once ("functions/andon_v2.php");

?>


<div id="misalertas32">

        <?php
        
        executeAndon();


        ?>
</div>


<?php include("../includes/footer_andon.php") ?>

