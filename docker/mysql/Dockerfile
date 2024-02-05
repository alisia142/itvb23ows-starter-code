from mysql:8.3

COPY ./hive.sql /hive/sql

CMD ['mysqld', '--character-set-server=utf8mb4', '--collation-server=utf8mb4_unicode_ci', '--default-authentication-plugin=mysql_native_password', '--init-file=/hive.sql']