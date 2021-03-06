<?php
    //Yet another Gnomo.fe.up.pt fix
    //Fixes session default location being unaccessable.
    session_save_path("$root/_system/");


    session_start();

    if(! isset($_SESSION["online"])){
        $_SESSION["online"]=false;
    }
?>



<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Libraries -->
<link rel="stylesheet" href="<?php echo "$root/res/lib/css/bootstrap.css"; ?>">
<script src="<?php echo "$root/res/lib/js/jquery-1.12.1.js"; ?>">
    //JQuery is included before Bootstrap, to prevent issues.
</script> 
<script src="<?php echo "$root/res/lib/js/bootstrap.js"; ?>">
    //The Bootstrap Framework
</script>



<!-- Application -->
<link rel="stylesheet" href="<?php echo "$root/res/css/main.css"; ?>">

<title><?php echo "EventBook - $title" ?></title>        
