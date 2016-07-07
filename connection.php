<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
$_SESSION["user_id"]=66;

//$db=new PDO("mysql:host=localhost;dbname=internDB","","");
$db=new PDO("mysql:host=mysql.hostinger.co.uk;dbname=u651177071_datab","u651177071_usern","adebej");
