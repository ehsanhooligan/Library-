CREATE DATABASE library;

USE library;

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    quantity INT NOT NULL
);

INSERT INTO books (name, author, category, quantity) VALUES
('The Great Gatsby', 'F. Scott Fitzgerald', 'Fiction', 5),
('Pride and Prejudice', 'Jane Austen', 'Romance', 3),
('1984', 'George Orwell', 'Dystopian', 4),
('Leaves of Grass', 'Walt Whitman', 'Poetry', 2),
('To Kill a Mockingbird', 'Harper Lee', 'Classic', 6);
