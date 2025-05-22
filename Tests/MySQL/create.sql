CREATE DATABASE IF NOT EXISTS _squid_test_;
USE _squid_test_;

CREATE USER IF NOT EXISTS '_squid_test_u_'@'%' IDENTIFIED WITH mysql_native_password BY '_squid_test_pass_';
GRANT ALL PRIVILEGES ON `_squid_test_`.* TO '_squid_test_u_'@'%';
FLUSH PRIVILEGES;