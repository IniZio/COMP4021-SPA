CREATE TABLE Comments
(
  id                INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  author_user_id    INTEGER                           NOT NULL,
  content           TEXT                              NOT NULL,
  created_timestamp INTEGER                           NOT NULL,
  course_id         INTEGER                           NOT NULL
);
CREATE UNIQUE INDEX Comments_id_uindex
  ON Comments (id);
CREATE INDEX Comments_author_user_id_index
  ON Comments (author_user_id);
CREATE INDEX Comments_course_id_index
  ON Comments (course_id);



CREATE TABLE Courses
(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  name TEXT NOT NULL,
  description TEXT,
  code TEXT NOT NULL ,
  summary TEXT,
  professor TEXT NOT NULL
);
CREATE UNIQUE INDEX Courses_id_uindex ON Courses (id);
CREATE UNIQUE INDEX Courses_name_uindex ON Courses (name);
CREATE INDEX Courses_code_index ON Courses (code);
CREATE INDEX Courses_code_index ON Courses (professor);

INSERT INTO Courses (name, code, professor, description)
VALUES ('Internet Computing', 'COMP4021', 'LAM, Gibson', 'Technologies and standards for World Wide Web (WWW), user interfaces and Browsers, authoring tools, Internet protocols, Internet servers, database connectivity, Robots, Search engines, server-side programming, client-side programming, security and privacy, recent advances.');

INSERT INTO Courses (name, code, professor, description)
VALUES ('Software Engineering Practices', 'COMP4111', 'ZHANG, Charles Chuan', 'This course provides students with the exposure of effective real-world software engineering practices and the underlying concepts via working around a realistic modern software system and applying popular tools and practices in industry. ');


CREATE TABLE Files
(
  id           INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  file_name    TEXT                              NOT NULL,
  content_type TEXT                              NOT NULL
);
CREATE UNIQUE INDEX Files_id_uindex
  ON Files (id);
CREATE UNIQUE INDEX Files_file_name_uindex
  ON Files (file_name);



CREATE TABLE Resources
(
  id           INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  name         TEXT                              NOT NULL,
  course_id    INTEGER                           NOT NULL,
  description  TEXT,
  type         TEXT                              NOT NULL,
  file_id      INTEGER,
  text_content TEXT
);
CREATE UNIQUE INDEX Resources_id_uindex
  ON Resources (id);
CREATE INDEX Resources_name_index
  ON Resources (name);
CREATE INDEX Resources_course_id_index
  ON Resources (course_id);
CREATE INDEX Resources_type_index
  ON Resources (type);



CREATE TABLE Users
(
  id              INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  username        TEXT                              NOT NULL,
  hashed_password TEXT                              NOT NULL,
  picture_file_id INTEGER,
  first_name      TEXT,
  last_name       TEXT,
  email           TEXT,
  major           TEXT,
  year            TEXT,
  status          TEXT
);
CREATE UNIQUE INDEX Users_Auth_id_uindex
  ON Users (id);
CREATE UNIQUE INDEX Users_Auth_username_uindex
  ON Users (username);
CREATE UNIQUE INDEX Users_email_uindex
  ON Users (email);
CREATE INDEX Users_major_index
  ON Users (major);
CREATE INDEX Users_year_index
  ON Users (year);
CREATE INDEX Users_status_index
  ON Users (status);
