USE hospital_ticketing;
UPDATE users SET password = '$2y$10$GOzKzx7SBbEugcuFWiZAke4VFoIlKHDF3W3pvfrzb9CZFDb.0RtA2' WHERE username = 'admin';
UPDATE users SET password = '$2y$10$ZCAwae2pBzC8gjjEyxhO6uP7E2hPZHgXI6FJmciXGoO/zW8w5p6D6' WHERE username LIKE 'staff%';
