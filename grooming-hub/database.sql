

DROP DATABASE IF EXISTS grooming_hub;
CREATE DATABASE grooming_hub CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE grooming_hub;


-- USERS TABLE

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- PRODUCTS TABLE

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category VARCHAR(50),
    stock INT DEFAULT 100,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ORDERS TABLE (FIXED - No redundant columns!)

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    total DECIMAL(10,2) NOT NULL DEFAULT 0,  -- ✅ FIXED: Now NOT NULL with default
    status VARCHAR(50) DEFAULT 'Pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_email (email),
    INDEX idx_date (order_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ORDER ITEMS TABLE

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,  -- ✅ Added for referential integrity
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- CONTACT MESSAGES TABLE

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_date (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ADMIN USERS TABLE 

CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- INSERT SAMPLE DATA


-- Insert default admin (username: admin, password: password)
INSERT INTO admin_users (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample products (with CORRECT image paths - just filenames!)
INSERT INTO products (name, description, price, image, category, stock) VALUES
('Royal Beard Balm', 'Smooth finishing balm for a clean, refined look.', 750.00, 'Royal_Beard_Balm.jpg', 'Beard', 50),
('Precision Metal Razor', 'Engineered for unmatched precision and control.', 2990.00, 'Precision_Metal_Razor.jpg', 'Shaving', 30),
('Midnight Shave Cream', 'A rich, luxurious lather for a flawless shave.', 550.00, 'Midnight_Shave_Cream.jpg', 'Shaving', 75),
('Ironclad Aftershave', 'Instant cooling and soothing after every shave.', 650.00, 'Ironclad_Aftershave.jpg', 'Skin', 60),
('Alpha Beard Oil', 'Adds shine, strength, and confidence to every beard.', 600.00, 'Alpha_Beard_Oil.jpg', 'Beard', 80),
('Razor Kit', 'Complete set for a professional grooming experience.', 2200.00, 'Razor_Kit.jpg', 'Shaving', 25),
('Classic Beard Oil', 'Nourishing oil for healthy, manageable beards.', 580.00, 'beard_oil.jpg', 'Beard', 100),
('Premium Shaving Cream', 'Smooth, hydrating cream for the perfect shave.', 500.00, 'shaving_cream.jpg', 'Shaving', 90);

-- Insert a test user (email: test@example.com, password: password123)
INSERT INTO users (name, email, password) VALUES
('Test User', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');


