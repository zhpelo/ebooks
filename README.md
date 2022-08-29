# 这是一个wordpress主题代码

可以实现电子书在线发布和阅读功能

```
git clone git@github.com:eenot/ebooks.git

docker-compose up -d

```

### docker-compose.yml
```
version: "3.9"

services:
  db:
    image: mysql:5.7
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
    networks:
        - wp

  wordpress:
    depends_on:
      - db
    image: wordpress:latest
    volumes:
      - .:/var/www/html
    ports:
      - "8000:80"
    restart: always
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
    networks:
      - wp
networks:
  wp:
volumes:
  db_data: {}

```