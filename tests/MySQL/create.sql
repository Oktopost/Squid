CREATE DATABASE _squid_test_;
USE _squid_test_;


CREATE USER '_squid_test_u_'@'%' IDENTIFIED BY '_squid_test_pass_';
GRANT ALL PRIVILEGES ON `_squid_test_`.* TO '_squid_test_u_'@'%';