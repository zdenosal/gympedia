<?php 
    $conn = mysqli_connect('localhost', 'zdeno', 'nirakamor2212', 'db_mp');
    
    if(!$conn){
        echo 'Connection failed: ' . mysqli_connect_error();
    }
?>