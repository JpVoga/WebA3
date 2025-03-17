-- Password: ecommerce

CREATE USER "joaoWebA3" IDENTIFIED BY "ecommerce";

GRANT ALL PRIVILEGES ON *.* TO "joaoWebA3";

USE mysql;

SELECT * FROM user;