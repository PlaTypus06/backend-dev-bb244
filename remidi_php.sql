-- db remidial

CREATE DATABASE remidi_php;

use remidi_php;

CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    no_hp TEXT NOT NULL,
    dibuat_di TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    produk VARCHAR(100) NOT NULL,
    quantitas INT NOT NULL,
    dibuat_di TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (customer_id)
        REFERENCES customers(id)
        ON DELETE CASCADE
);

show tables;
describe pesanan;
describe customers;
SELECT * FROM customers;


SELECT id, nama, email, no_hp
FROM customers;

INSERT INTO customers (nama, email, no_hp)
VALUES
('Tio', 'tio@gmail.com', '08123456789'),
('Thor', 'thor@gmail.com', '08234567890');