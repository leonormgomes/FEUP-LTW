PRAGMA foreign_keys = ON;
PRAGMA encoding = "UTF-8";

.headers ON
.mode column

drop table if exists Follows;
drop table if exists Favorite;
drop table if exists Owns;
drop table if exists ResponseToPost;
drop table if exists ResponseToResponse;
drop table if exists ResponseToQuestion;
drop table if exists Response;
drop table if exists Post;
drop table if exists Question;
drop table if exists AdoptionProposal;
drop table if exists Animal;
drop table if exists User;

CREATE TABLE User(
  email VARCHAR(128) PRIMARY KEY,
  username VARCHAR(64) UNIQUE,
  password VARCHAR(255) NOT NULL,
  first_name VARCHAR(64) NOT NULL,
  last_name VARCHAR(64) NOT NULL,
  phone_number VARCHAR(16),
  description TEXT,
  profile_picture VARCHAR(254) DEFAULT '1.gif',
  cover_picture VARCHAR(254) DEFAULT '2.jpeg',
  location VARCHAR(128),
  birth_date VARCHAR(10)
);

CREATE TABLE Animal(
  id INTEGER PRIMARY KEY,
  name VARCHAR(128) NOT NULL,
  photo VARCHAR(128) DEFAULT '3.jpeg',
  description TEXT,
  location VARCHAR(128),
  age INTEGER,
  size VARCHAR(20),
  species VARCHAR(128),
  listed_for_adoption INTEGER 
);

CREATE TABLE AdoptionProposal(
    status VARCHAR(20),
    animal INTEGER REFERENCES Animal(id),
    person VARCHAR(64) REFERENCES User(email)
);

CREATE TABLE Question(
    id INTEGER PRIMARY KEY,
    content TEXT,
    animal INTEGER REFERENCES Animal(id),
    person VARCHAR(64) REFERENCES User(email),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Post(
    id INTEGER PRIMARY KEY,
    title VARCHAR(64),
    content TEXT,
    photo VARCHAR(254),
    animal INTEGER REFERENCES Animal(id),
    person VARCHAR(64) REFERENCES User(email),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Response(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    content TEXT,
    person VARCHAR(64) REFERENCES User(email),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ResponseToQuestion(
    id_question REFERENCES Question(id),
    id_response REFERENCES Response(id)
);

CREATE TABLE ResponseToResponse(
    id_response1 REFERENCES Response(id),
    id_response2 REFERENCES Response(id)
);

CREATE TABLE ResponseToPost(
    id_post REFERENCES Post(id),
    id_response REFERENCES Response(id)
);

CREATE TABLE Owns(
    user REFERENCES User(email),
    animal REFERENCES Animal(id)
);

CREATE TABLE Favorite(
    user REFERENCES User(email),
    animal REFERENCES Animal(id)
);

CREATE TABLE Follows(
    user1 REFERENCES User(email),
    user2 REFERENCES User(email)
);
