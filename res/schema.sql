CREATE TABLE IF NOT EXISTS post (
    id int(11) NOT NULL AUTO_INCREMENT,
    title varchar(255) DEFAULT '' NOT NULL,
    slug varchar(255) DEFAULT '' NOT NULL,
    active tinyint(2) DEFAULT 0 NOT NULL,
    teaser tinytext,
    body text,
    created_at datetime DEFAULT NOW() NOT NULL,
    updated_at datetime DEFAULT NOW() NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (slug)
);
CREATE TABLE IF NOT EXISTS tag (
    id int(11) NOT NULL AUTO_INCREMENT,
    title varchar(255) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (title)
);
CREATE TABLE IF NOT EXISTS category (
    id int(11) NOT NULL AUTO_INCREMENT,
    title varchar(255) DEFAULT '' NOT NULL,
    parent_id int DEFAULT 0 NOT NULL,
    PRIMARY KEY (id),
    KEY (parent_id)
);
CREATE TABLE IF NOT EXISTS post_tag (
    post_id int DEFAULT 0 NOT NULL,
    tag_id int DEFAULT 0 NOT NULL,
    FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS post_category (
    post_id int DEFAULT 0 NOT NULL,
    category_id int DEFAULT 0 NOT NULL,
    FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE
);