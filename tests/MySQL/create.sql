CREATE DATABASE _squid_test_;
USE _squid_test_;


CREATE USER '_squid_test_u_'@'%' IDENTIFIED WITH mysql_native_password;
SET PASSWORD FOR '_squid_test_u_'@'%' = PASSWORD('_squid_test_pass_');
GRANT ALL PRIVILEGES ON `_squid_test_`.* TO '_squid_test_u_'@'%';