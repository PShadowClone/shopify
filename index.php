<?php
require 'vendor/autoload.php'; // Include Composer's autoloader
require 'config/ReadIni.php';
include_once "./Helpers/helpers.php";
$pageFromRequestedUri = str_replace('/'  , '' , $_SERVER['REQUEST_URI'] );
$page =  $pageFromRequestedUri == '' ? 'index': $pageFromRequestedUri;
include_once './src/'.$page.'.php';

