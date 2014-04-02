<?php 
include_once 'library.php';
	
execLogin();
beginPage();
drawHeaderRow(FALSE, FALSE);
drawLoginForm();
endPage();

