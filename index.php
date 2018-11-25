<?php
error_reporting(E_ALL);
ini_set("display_errors",1);

require_once("FrenchPop.php");

use frenchpop\FrenchPop;

//FrenchPop::initStore();
FrenchPop::connectDb();
FrenchPop::initApi();
