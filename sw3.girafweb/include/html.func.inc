<?php

abstract class html
{
    public static function header_start()
    {
    ?>
    <html>
    <head>
    <?php
    }

    public static function body_start()
    {
    ?>
    <body>
    <?php
    }

    public static function header_end()
    {
    ?>
    </head>    
    <?php
    }

    public static function body_end()
    {
    ?>
    </body>
    <?php
    }

    public static function test_text()
    {
    ?>
    <div>Look at this text!</div>
    <?php
    }
}

?>
