DROP TABLE IF EXISTS authors; 

-- Crea la tabla de autores
CREATE TABLE authors (
  author_id INT NOT NULL AUTO_INCREMENT,
  author_name VARCHAR(250) UNIQUE NOT NULL,
  books_count INT(50) NOT NULL,

  PRIMARY KEY(author_id)
);

DROP TABLE IF EXISTS genders; 

-- Crea la tabla de géneros
CREATE TABLE genders (
  gender_id INT NOT NULL AUTO_INCREMENT,
  gender_name VARCHAR(250) UNIQUE NOT NULL,

  PRIMARY KEY(gender_id)
);

DROP TABLE IF EXISTS books;

-- Crea la tabla de libros
CREATE TABLE books (
  book_id INT NOT NULL AUTO_INCREMENT,
  book_title VARCHAR(250) NOT NULL,
  book_author INT(50) NOT NULL,
  book_gender INT(50) NOT NULL,
  published_at DATE NOT NULL,
  book_cover VARCHAR(250) NOT NULL,
  book_sku VARCHAR(250) UNIQUE NOT NULL, 

  PRIMARY KEY(book_id),
  FOREIGN KEY(book_author) REFERENCES authors(author_id),
  FOREIGN KEY(book_gender) REFERENCES genders(gender_id)
);

-- Define el trigger para actualizar la cantidad de libros de cada autor
CREATE TRIGGER update_author_books
after INSERT 
ON books
FOR EACH ROW
UPDATE authors SET books_count = books_count + 1 WHERE author_id = new.book_author;

-- Insertando información
INSERT INTO authors ( author_name, books_count ) VALUES 
( "Stephen King", 0 ),
( "Isabel Allende", 0 ),
( "Paulo Coelho", 0 ),
( "Mario Medoza", 0 ),
( "Haruki Murakami", 0 );

INSERT INTO genders ( gender_name ) VALUES 
( 'Horror' ),
( 'Ficción' ),
( 'Biografía' ),
( 'Fantasía' );

INSERT INTO books ( book_title, book_author, book_gender, published_at, book_cover, book_sku ) VALUES
( "Paula", 2, 2, "2004-02-12", "http://localhost/API/public/img/154236897542.jpg", '154236897542' ),
( "Pet Sematary", 1, 1, "1989-10-25", "http://localhost/API/public/img/154698653245.jpg", "154698653245" ),
( "Veronika decide morir", 2, 2, "2004-02-12", "http://localhost/API/public/img/414846912129.jpg", '414846912129' ),
( "Diario del fin del mundo", 2, 2, "2004-02-12", "http://localhost/API/public/img/567396797097.jpg", '567396797097' ),
( "Tokio Blues", 2, 2, "2004-02-12", "http://localhost/API/public/img/663503855717.jpg", '663503855717' ),
( "La casa de los espíritus", 2, 2, "2004-02-12", "http://localhost/API/public/img/849929837130.jpg", '849929837130' );

-- Obteniendo información
SELECT book_title, author_name, gender_name, published_at, book_sku, book_cover 
FROM books 
INNER JOIN authors
ON authors.author_id = book_author
INNER JOIN genders
ON genders.gender_id = book_gender