<?php
require_once 'db.php';

try {
    // Create users table (if not exists)
    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        display_name VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    
    echo "Users table created successfully.\n";
    
    // Create members table
    $conn->exec("CREATE TABLE IF NOT EXISTS members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fullname VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        major VARCHAR(100) NOT NULL,
        academic_year INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    
    echo "Members table created successfully.\n";
    
    // Insert sample data if tables are empty
    $stmt = $conn->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    
    if ($userCount == 0) {
        // Create a default admin user (password: admin123)
        $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password_hash, display_name) VALUES (?, ?, ?)");
        $stmt->execute(['admin', $passwordHash, 'Administrator']);
        echo "Default admin user created (username: admin, password: admin123)\n";
    }
    
    $stmt = $conn->query("SELECT COUNT(*) FROM members");
    $memberCount = $stmt->fetchColumn();
    
    if ($memberCount == 0) {
        // Insert sample member data
        $stmt = $conn->prepare("INSERT INTO members (fullname, email, major, academic_year) VALUES (?, ?, ?, ?)");
        $stmt->execute(['John Doe', 'john@example.com', 'วิศวกรรมซอฟต์แวร์', 2567]);
        $stmt->execute(['Jane Smith', 'jane@example.com', 'วิทยาการคอมพิวเตอร์', 2567]);
        echo "Sample member data inserted.\n";
    }
    
    echo "Database initialization completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>