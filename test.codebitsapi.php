<?php
    require_once 'CodebitsApi.php';
    $capi = new CodebitsApiUtils('eriksson.monteiro@ua.pt', '');
    $friends = $capi->getFriends();
   
?>
