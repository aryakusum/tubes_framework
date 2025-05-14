<?php

$page = $_SERVER['PHP_SELF'];
$sec = "2";
date_default_timezone_set('Asia/Jakarta');

?>
<html>
    <head>
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    </head>
    <body>
    <?php
        echo "Watch the page reload itself in 10 second!<br>";
        echo "Tanggal dan Waktu sekarang adalah " . date("Y-m-d h:i:sa") . "<br>";
    ?>
    
    </body>
</html>