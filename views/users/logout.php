<?php
    $logout = $users->logout();
    if ($logout): 
        header('location: /');
        else :
            echo 'GAGAL';
        endif;