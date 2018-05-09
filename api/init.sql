CREATE TABLE Comments
(
  id                    INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  author_user_id        INTEGER                           NOT NULL,
  content               TEXT                              NOT NULL,
  picture_url           TEXT,
  created_timestamp     INTEGER                           NOT NULL,
  last_edited_timestamp INTEGER                           NOT NULL,
  course_id             INTEGER                           NOT NULL,
  picture_file_id       INTEGER                           NOT NULL
);
CREATE UNIQUE INDEX Comments_id_uindex
  ON Comments (id);
CREATE INDEX Comments_author_user_id_index
  ON Comments (author_user_id);
CREATE INDEX Comments_course_id_index
  ON Comments (course_id);


CREATE TABLE Courses
(
  id          INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  name        TEXT                              NOT NULL,
  description TEXT,
  code        TEXT                              NOT NULL,
  summary     TEXT,
  professor   TEXT                              NOT NULL
);
CREATE UNIQUE INDEX Courses_id_uindex
  ON Courses (id);
CREATE UNIQUE INDEX Courses_name_uindex
  ON Courses (name);
CREATE INDEX Courses_code_index
  ON Courses (code);
CREATE INDEX Courses_professor_index
  ON Courses (professor);

INSERT INTO Courses (name, code, professor, description)
VALUES ('Internet Computing', 'COMP4021', 'LAM, Gibson',
        'Technologies and standards for World Wide Web (WWW), user interfaces and Browsers, authoring tools, Internet protocols, Internet servers, database connectivity, Robots, Search engines, server-side programming, client-side programming, security and privacy, recent advances.');

INSERT INTO Courses (name, code, professor, description)
VALUES ('Software Engineering Practices', 'COMP4111', 'ZHANG, Charles Chuan',
        'This course provides students with the exposure of effective real-world software engineering practices and the underlying concepts via working around a realistic modern software system and applying popular tools and practices in industry. ');

INSERT INTO Courses (name, code, professor, description)
VALUES ('Introduction to Computing with Java', 'COMP1022P', ' TSOI, Yau Chat',
        'This course is designed to equip students with the fundamental concepts of programming elements and data abstraction using Java. Students will learn how to write procedural programs using variables, arrays, control statements, loops, recursion, data abstraction and objects using an integrated development environment.');

INSERT INTO Courses (name, code, professor, description)
VALUES ('Introduction to Computing with Excel VBA', 'COMP1022Q', 'LAM, Gibson',
        'This course is designed to equip students with the fundamental concepts of programming using the VBA programming language, within the context of the Microsoft Excel program. Students will first learn how to use Excel to analyze and present data and will then learn how to use VBA code to build powerful programs.');

INSERT INTO Courses (name, code, professor, description)
VALUES
  ('Introduction to Object-oriented Programming', 'COMP2011', 'CHAN, Ki Cecia',
   'This course is an introduction to object-oriented programming and data structures. Students will learn abstract data types and their implementation as classes in an object-oriented programming language; static and dynamic construction and destruction of objects; data member and member functions; public interface and encapsulation. It will cover data structures such as stacks, queues, linked lists, and binary trees.');

INSERT INTO Courses (name, code, professor, description)
VALUES ('Computer Organization', 'COMP2611', 'LAM, Ngok',
        'Inner workings of modern digital computer systems and tradeoffs at the hardware-software interface. Topics include: instructions set design, memory systems, input-output systems, interrupts and exceptions, pipelining, performance and cost analysis, assembly language programming, and a survey of advanced architectures.');

INSERT INTO Courses (name, code, professor, description)
VALUES ('Java Programming', 'COMP3021', 'LAM, Ngok',
        'Inner workings of modern digital computer systems and tradeoffs at the hardware-software interface. Topics include: instructions set design, memory systems, input-output systems, interrupts and exceptions, pipelining, performance and cost analysis, assembly language programming, and a survey of advanced architectures.');

INSERT INTO Courses (name, code, professor, description)
VALUES ('Software Engineering', 'COMP3111', 'LEUNG, Wai Ting',
        'Methods and tools for planning, designing, implementing, validating, and maintaining large software systems. Project work to build a software system as a team, using appropriate software engineering tools and techniques.');

INSERT INTO Courses (name, code, professor, description)
VALUES ('Fundamentals of Artificial Intelligence', 'COMP3211', 'LIN, Fangzhen',
        'Foundations underlying design of intelligent systems. Relations between logical, statistical, cognitive, biological paradigms; basic techniques for heuristic search, theorem proving, knowledge representation, adaptation; applications in vision, language, planning, expert systems.');

INSERT INTO Courses (name, code, professor, description)
VALUES ('Database Management Systems', 'COMP3311', 'NG, Wilfred Siu Hung',
        'Principles of database systems; conceptual modeling and data models; logical and physical database design; query languages and query processing; database services including concurrency, crash recovery, security and integrity. Hands-on DBMS experience.');

INSERT INTO Courses (name, code, professor, description)
VALUES ('Operating Systems', 'COMP3511', 'LI, Bo',
        'Principles, purpose and structure of operating systems; processes, threads, and multi-threaded programming; CPU scheduling; synchronization, mutual exclusion; memory management and virtual memory; device management; file systems, security and protection.');

INSERT INTO Courses (name, code, professor, description)
VALUES ('Design and Analysis of Algorithms', 'COMP3711', 'GOLIN, Mordecai Jay',
        'Techniques for designing algorithms, proving their correctness, and analyzing their running times. Topics covered include: sorting, selection, heaps, balanced search trees, divide-and-conquer, greedy algorithms, dynamic programming, and graph algorithms.');

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
