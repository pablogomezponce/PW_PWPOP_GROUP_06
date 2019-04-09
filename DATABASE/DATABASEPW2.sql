CREATE DATABASE PWPOP;
USE PWPOP;

CREATE TABLE User(
                   id SERIAL,
                   username VARCHAR(255),
                   email varchar(255),
                   password varchar(255), -- #TODO: Remember to MD5!!
                   name VARCHAR(255),
                   birthdate DATE,
                   phone BIGINT,
                   image_dir TEXT,


                   PRIMARY KEY (ID)

);



CREATE TABLE Product(
                      title TEXT,
                      description TEXT,
                      price float,
                      product_image_dir text,
                      category text,
                      id SERIAL,

                      PRIMARY KEY (id)
);

CREATE TABLE UserProductOwn(
                             owner INT,
                             product INT,

                             PRIMARY KEY (owner, product)
);

CREATE TABLE UserProductBuy(
                             buyer INT,
                             product INT,

                             PRIMARY KEY (buyer, product)
);

CREATE TABLE Favorites(
                        user INT,
                        product INT,

                        PRIMARY KEY (user, product)

);
