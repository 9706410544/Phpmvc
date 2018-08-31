<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HRMS : Admin Area</title>

    <?php 
    if(isset($this->publiccss)){
        foreach($this->publiccss as $publiccss){
            echo '<link rel="stylesheet" href="'.URL.'public/'.$publiccss.'">';
        }
    } 
    if(isset($this->css)){
        foreach($this->css as $css){
            echo '<link rel="stylesheet" href="'.URL.'views/'.$css.'">';
        }
    } 
    ?>
</head>
<body>