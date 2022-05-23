<?php
/**
 * Created by PhpStorm.
 * User: josel
 * Date: 5/25/2017
 * Time: 2:46 PM
 */
ob_start();
session_start();
require_once("../../admin/config/db.php");
require_once ("../vendor/autoload.php");
$config = require ("config.php");
