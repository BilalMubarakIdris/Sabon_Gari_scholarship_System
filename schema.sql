CREATE TABLE applicants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50),
    lastname VARCHAR(50),
    othername VARCHAR(50),
    gender ENUM('Male', 'Female'),
    email VARCHAR(100) UNIQUE,
    school_name VARCHAR(100),
    department VARCHAR(100),
    course_of_study VARCHAR(100),
    level VARCHAR(50),
    local_government VARCHAR(50) DEFAULT 'Sabon Gari',
    ward VARCHAR(50),
    student_type ENUM('Return', 'New'),
    residential_address VARCHAR(255),
    indigene_document VARCHAR(255),
    admission_document VARCHAR(255),
    id_card_document VARCHAR(255),
    payment_document VARCHAR(255),
    profile_picture VARCHAR(255),
    bank_name VARCHAR(100),
    account_number VARCHAR(20),
    account_name VARCHAR(100),
    status ENUM('Pending', 'Verified', 'Rejected') DEFAULT 'Pending',
     email_verified TINYINT(1) DEFAULT 0,
    token VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    reset_token VARCHAR(255) DEFAULT NULL;

);
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255)
);
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100),
    message TEXT,
    reply TEXT
);
CREATE TABLE successful_applicants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id)
);
