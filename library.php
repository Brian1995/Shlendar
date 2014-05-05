<?php

require_once 'lib/calendar.php';
require_once 'lib/db.php';
require_once 'lib/pageelements.php';
require_once 'lib/render.php';
require_once 'lib/utils.php';
require_once 'lib/xml.php';

mb_internal_encoding("UTF-8");
setlocale(LC_ALL, 'de_DE.utf-8');
session_start();