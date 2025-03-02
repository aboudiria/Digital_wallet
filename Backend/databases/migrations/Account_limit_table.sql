CREATE TABLE account_limits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    daily_limit DECIMAL(10,2) DEFAULT 1000.00,
    weekly_limit DECIMAL(10,2) DEFAULT 5000.00,
    monthly_limit DECIMAL(10,2) DEFAULT 20000.00,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);