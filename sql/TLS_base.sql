CREATE DATABASE tls;
USE tls;

CREATE TABLE tls_articles ( 
article_id INT(11) AUTO_INCREMENT PRIMARY KEY,
article_title VARCHAR(150) NOT NULL,
article_url_title VARCHAR(40) UNIQUE NOT NULL,
article_desc VARCHAR(255) NOT NULL,
article_author VARCHAR(40) NOT NULL,
article_date VARCHAR(20) NOT NULL,
article_image VARCHAR(50) NOT NULL,
article_resume VARCHAR(500) NOT NULL,
article_content MEDIUMTEXT NOT NULL,
article_sources VARCHAR(500) NOT NULL
);

CREATE TABLE tls_categories (
category_id INT(11) AUTO_INCREMENT PRIMARY KEY,
category_name VARCHAR(40) UNIQUE NOT NULL
);

CREATE TABLE tls_article_category (
  ac_article_id INT(11) NOT NULL,
  ac_category_id INT(11) NOT NULL,
  CONSTRAINT pk_article_category PRIMARY KEY (ac_article_id,ac_category_id), 
  CONSTRAINT fk_article FOREIGN KEY (ac_article_id) REFERENCES tls_articles (article_id),
  CONSTRAINT fk_category FOREIGN KEY (ac_category_id) REFERENCES tls_categories (category_id)
);

CREATE TABLE tls_comments (
comment_id INT(11) AUTO_INCREMENT PRIMARY KEY,
comment_date TIMESTAMP NOT NULL,
comment_author VARCHAR(40) NOT NULL,
comment_content TEXT NOT NULL,
comment_article_id INT(11) NOT NULL,
CONSTRAINT fk_article_comment FOREIGN KEY (comment_article_id) REFERENCES tls_articles (article_id)
);

CREATE TABLE tls_rss (
rss_id INT(11) AUTO_INCREMENT PRIMARY KEY,
rss_article_id INT(11) NOT NULL,
rss_guid TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
CONSTRAINT fk_rss_article FOREIGN KEY (rss_article_id) REFERENCES tls_articles (article_id)
);

CREATE TABLE tls_badass (
badass_id INT(11) AUTO_INCREMENT PRIMARY KEY,
badass_sentence TEXT NOT NULL,
badass_author VARCHAR(50) NOT NULL
);

INSERT INTO tls_categories(category_name) VALUES ('Informatique');
INSERT INTO tls_categories(category_name) VALUES ('Ecologie');
INSERT INTO tls_categories(category_name) VALUES ('Société');

INSERT INTO tls_badass(badass_sentence, badass_author) VALUES ('Je suis une phrase badass', 'Le plus grand');
INSERT INTO tls_badass(badass_sentence, badass_author) VALUES ('Je suis une phrase badass2', 'Le plus fort');
INSERT INTO tls_badass(badass_sentence, badass_author) VALUES ('Je suis une phrase badass3', 'Le plus beau');
