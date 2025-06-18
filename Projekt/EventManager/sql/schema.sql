CREATE DATABASE IF NOT EXISTS event_manager;
USE event_manager;
CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       login VARCHAR(50) NOT NULL,
                       email VARCHAR(100) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       role ENUM('admin', 'organizer', 'user') DEFAULT 'user',
                       verified TINYINT(1) DEFAULT 0,
                       is_blocked TINYINT(1) NOT NULL DEFAULT 0,
                       reset_token VARCHAR(255),
                       reset_token_expires DATETIME,
                       remember_token VARCHAR(255) NULL,
                       remember_token_expires DATETIME NULL,
                       created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE events (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        title VARCHAR(100) NOT NULL,
                        description TEXT,
                        location VARCHAR(100),
                        start_date DATETIME,
                        end_date DATETIME,
                        image_path VARCHAR(255),
                        organizer_id INT,
                        capacity INT DEFAULT 0,
                        is_closed TINYINT(1) DEFAULT 0,
                        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (organizer_id) REFERENCES users(id)
);

CREATE TABLE registrations (
                               id INT AUTO_INCREMENT PRIMARY KEY,
                               user_id INT NOT NULL,
                               event_id INT NOT NULL,
                               registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                               FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                               FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

CREATE TABLE comments (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          event_id INT,
                          user_id INT NULL,
                          guest_name VARCHAR(50) NULL,
                          content TEXT,
                          parent_id INT DEFAULT NULL,
                          is_organizer_reply TINYINT(1) DEFAULT 0,
                          created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                          FOREIGN KEY (event_id) REFERENCES events(id),
                          FOREIGN KEY (user_id) REFERENCES users(id),
                          FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE
);

CREATE TABLE attachments (
                             id INT AUTO_INCREMENT PRIMARY KEY,
                             event_id INT NOT NULL,
                             file_path VARCHAR(255) NOT NULL,
                             uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                             FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

INSERT INTO users (login, email, password, role, verified, is_blocked)
VALUES ('admin', 'admin@example.com', '$2y$12$y9gg0pHReCSpay/DptkuBOeWoYwPo0Eg5cNkOjFLNvUXosh9z79oC', 'admin', 1, 0);