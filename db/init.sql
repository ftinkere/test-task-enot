CREATE TABLE IF NOT EXISTS users (
    id int not null auto_increment,
    username varchar(255) not null,
    password varchar(255) not null,
    PRIMARY KEY (id),
    UNIQUE (username)
);

CREATE TABLE IF NOT EXISTS rates (
    id int not null auto_increment,
    datetime datetime not null,
    usd real,
    eur real,
    PRIMARY KEY (id)
);