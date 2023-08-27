<?php

require( "../config.php" );
require( "../classes/db.class.php" );

/**
 * Obtiene en formato json todos los libros de la db	
 * 
 * @todo Parámetro adicional para obtener libros específicos
 * @return mixed
 */
function get_books(){

	$db = new db();
	$sql = "
	SELECT book_title, author_name, gender_name, published_at, book_sku, book_cover 
	FROM books 
	INNER JOIN authors
	ON authors.author_id = book_author
	INNER JOIN genders
	ON genders.gender_id = book_gender";

	$result = $db->get_results( $sql );

	if( empty($result) ){
		
		print_r( ERROR['EMPTY'] );
		http_response_code(204);

	} else {

		print_r( json_encode($result) );

	}
}

/**
 * Obtiene en formato json, el libro con el id $book_id
 * 
 * @param int $book_id
 * 
 * @return mixed
 */
function get_book( $book_id ){

	if( empty($book_id) ){

		print_r( ERROR['NOT_FOUND'] );
		http_response_code(204);
	}	

	$db = new db();
	$sql = "
	SELECT book_title, author_name, gender_name, published_at, book_sku, book_cover 
	FROM books 
	INNER JOIN authors
	ON authors.author_id = books.book_author
	INNER JOIN genders
	ON genders.gender_id = books.book_gender
	WHERE books.book_id = :book_id OR books.book_sku = :book_sku";

	$params = array( ":book_id" => $book_id, ":book_sku" => $book_id );
	$result = $db->get_results( $sql, $params );

	if( empty($result) ){

		print_r( ERROR['NOT_FOUND'] );
		http_response_code(204);

	} else {

		print_r( json_encode($result) );

	}
}