-- Universal Transparent E-Voting System (UTEVS) Database Schema
-- Production-grade schema ensuring complete verifiability, encrypted votes, and robust auditing.

CREATE DATABASE IF NOT EXISTS utevs_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE utevs_db;

-- 1. ROLES & PERMISSIONS
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE, -- 'superadmin', 'admin', 'voter'
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. USERS (VOTERS & ADMINS)
-- Separating identity from vote
CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36) NOT NULL UNIQUE, -- Public identifier for auditing
    role_id INT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL, -- bcrypt
    is_verified BOOLEAN DEFAULT FALSE,
    two_factor_secret VARCHAR(255) NULL,
    status ENUM('active', 'suspended', 'pending') DEFAULT 'pending',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- 3. ELECTIONS
CREATE TABLE elections (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    election_type ENUM('school', 'organization', 'corporate', 'government') NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    status ENUM('draft', 'scheduled', 'active', 'completed', 'archived') DEFAULT 'draft',
    is_public_results BOOLEAN DEFAULT FALSE, -- for Observer Mode
    created_by BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- 4. POSITIONS (Offices being voted for)
CREATE TABLE positions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    election_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL, -- e.g., 'President', 'Treasurer'
    max_votes_per_user INT DEFAULT 1,
    display_order INT DEFAULT 0,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE
);

-- 5. CANDIDATES
CREATE TABLE candidates (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    position_id BIGINT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    bio TEXT,
    manifesto_url VARCHAR(255),
    photo_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE
);

-- 6. VOTER REGISTRY (Eligibility per election)
CREATE TABLE election_voters (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    election_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    has_voted BOOLEAN DEFAULT FALSE,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_voter_election (election_id, user_id),
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 7. VOTES (Anonymized & Encrypted)
-- CRITICAL SECURITY: Does NOT link to user_id. Links only to election_id and candidate.
CREATE TABLE votes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    election_id BIGINT NOT NULL,
    position_id BIGINT NOT NULL,
    encrypted_candidate_data TEXT NOT NULL, -- AES-256 Encrypted Candidate ID to prevent direct DB tampering mapping
    vote_hash CHAR(64) NOT NULL UNIQUE, -- SHA-256 hash of (encrypted_data + salt + timestamp) for public ledger
    cast_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (election_id) REFERENCES elections(id),
    FOREIGN KEY (position_id) REFERENCES positions(id)
);

-- 8. VOTER RECEIPTS
-- Provided to user upon voting. Hash can be used on public dashboard to verify if vote was counted.
CREATE TABLE receipts (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    vote_hash CHAR(64) NOT NULL, -- Links to the vote anonymously
    receipt_code VARCHAR(100) NOT NULL UNIQUE, -- Given to the voter, e.g. UTEVS-XXX-YYY
    election_id BIGINT NOT NULL,
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vote_hash) REFERENCES votes(vote_hash),
    FOREIGN KEY (election_id) REFERENCES elections(id)
);

-- 9. AUDIT LOGS (Observer Mode & Fraud Detection)
CREATE TABLE audit_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NULL, -- Null if system action or anonymous visitor
    action VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    details JSON,
    severity ENUM('info', 'warning', 'critical') DEFAULT 'info',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 10. REAL-TIME OBSERVER STATS (Denormalized for performance)
CREATE TABLE observer_stats (
    election_id BIGINT PRIMARY KEY,
    total_eligible BIGINT DEFAULT 0,
    total_cast BIGINT DEFAULT 0,
    last_vote_cast_at TIMESTAMP NULL,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE
);

-- Insert Default Roles
INSERT INTO roles (name, description) VALUES 
('superadmin', 'System Owner'),
('admin', 'Election Manager'),
('voter', 'Standard Voter');
