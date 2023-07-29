<?php

require( "../controller/books.controller.php" );

if( $_SERVER['REQUEST_METHOD'] === "GET" ){

	if ( isset( $_GET['b'] ) ){

		get_book( $_GET['b'] );

	} else {

		get_books();
	
	}
}