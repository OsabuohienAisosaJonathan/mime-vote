<?php
return [
    'app_name' => 'UTEVS',
    'app_env' => 'development', // 'production' or 'development'
    'base_url' => 'http://localhost/MIME-VOTE',
    
    // SECURITY KEYS - MUST BE CHANGED IN PRODUCTION!
    // AES-256-CBC expects a 32-byte string for the key
    'encryption_key' => 'uTeVs_Secur3_KeY_2026!@#VerySafe', 
    'hash_salt' => 'random_salt_for_vote_hashing_9876543210'
];
