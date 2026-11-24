SET NAMES utf8mb4;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    balance INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, balance) VALUES
    ('Ivan Ivanov', 'ivan@gmail.com', 50),
    ('Maria Popescu', 'maria@gmail.com', 100),
    ('Andrei Rusu', 'andrei@gmail.com', 0);
