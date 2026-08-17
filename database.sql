CREATE DATABASE IF NOT EXISTS stock_management;
USE stock_management;

CREATE TABLE IF NOT EXISTS labs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lab_name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS stocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lab_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    FOREIGN KEY (lab_id) REFERENCES labs(id) ON DELETE CASCADE
);

INSERT INTO labs (lab_name) VALUES
('Computer Lab'),
('Physics Lab'),
('Chemistry Lab');