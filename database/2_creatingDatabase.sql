CREATE DATABASE webA3;

USE webA3;

CREATE TABLE users(
    id INT AUTO_INCREMENT,
    name VARCHAR(80) NOT NULL,
    passwordHash VARCHAR(80) NOT NULL,

    CONSTRAINT usersPk PRIMARY KEY(id),
    CONSTRAINT userNameIsUnique UNIQUE(name),
    CONSTRAINT userNameIsNotEmpty CHECK(length(name) > 0),
    CONSTRAINT userPasswordIsNotEmpty CHECK(length(passwordHash) > 0)
);

CREATE TABLE products(
    id INT AUTO_INCREMENT,
    name VARCHAR(80) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description VARCHAR(250),
    keyword VARCHAR(80) NOT NULL,
    imagePath VARCHAR(100),
    
    CONSTRAINT productsPk PRIMARY KEY(id),
    CONSTRAINT productNameIsNotEmpty CHECK(length(name) > 0),
    CONSTRAINT productPriceIsPositive CHECK(price > 0.00)
);

CREATE TABLE searches(
    id INT AUTO_INCREMENT,
    input VARCHAR(100) NOT NULL,
    moment DATETIME NOT NULL DEFAULT NOW(),
    userId INT,
    
    CONSTRAINT searchesPk PRIMARY KEY(id),
    CONSTRAINT searchUserFk FOREIGN KEY(userId) REFERENCES users(id)
);

CREATE TABLE addresses(
    id INT AUTO_INCREMENT NOT NULL,
    country VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    city VARCHAR(50) NOT NULL,
    road VARCHAR(50) NOT NULL,
    house VARCHAR(50) NOT NULL,
    
    CONSTRAINT addressesPk PRIMARY KEY(id)
);

CREATE TABLE sales(
    id INT AUTO_INCREMENT,
    paymentKey VARCHAR(50) NOT NULL,
    paymentMethod VARCHAR(25),
    moment DATETIME NOT NULL DEFAULT NOW(),
    userId INT,
    productId INT NOT NULL,
    deliveryAddressId INT NOT NULL,
    
    CONSTRAINT salesPk PRIMARY KEY(id),
    CONSTRAINT paymentKeyIsNotEmpty CHECK(length(paymentKey) > 0),
    CONSTRAINT saleUserFk FOREIGN KEY(userId) REFERENCES users(id),
    CONSTRAINT saleProductFk FOREIGN KEY(productId) REFERENCES products(id),
    CONSTRAINT saleDeliveryAddressFk FOREIGN KEY(deliveryAddressId) REFERENCES addresses(id)
);

SELECT * FROM users;
SELECT * FROM products;
SELECT * FROM searches;
SELECT * FROM addresses;
SELECT * FROM sales;

SET SQL_SAFE_UPDATES = TRUE;

SHOW VARIABLES WHERE Variable_Name LIKE "%dir"; -- Check for data directory

-- SELECT * FROM employee ORDER BY hireDate DESC; -- Reverse orderby example

-- In case I need to clear everything:

DELETE FROM sales;
ALTER TABLE sales AUTO_INCREMENT = 1;

DELETE FROM addresses;
ALTER TABLE addresses AUTO_INCREMENT = 1;

DELETE FROM searches;
ALTER TABLE searches AUTO_INCREMENT = 1;

-- Probably shouldn't clear this one
-- DELETE FROM products; 
-- ALTER TABLE products AUTO_INCREMENT = 1;

DELETE FROM users;
ALTER TABLE users AUTO_INCREMENT = 1;

DROP DATABASE webA3; -- In case I need to drop everything