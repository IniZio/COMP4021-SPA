create table Users
(
 id INTEGER not null,
 name TEXT not null,
 hash_salt TEXT not null,
 hashed_password TEXT not null,
 picture_id INTEGER
 );

create unique index Users_Auth_id_uindex on Users (id);

create unique index Users_Auth_name_uindex on Users (name);



create table Resources
(
 id INTEGER not null,
 name TEXT not null,
 course_id INTEGER not null,
 description TEXT,
 type TEXT not null,
 file_id INTEGER,
 text_content TEXT
 );

create unique index Resources_id_uindex on Resources (id);



create table Files
(
	id INTEGER not null,
	file_name TEXT not null,
	content_type TEXT not NULL
);

create unique index Files_file_name_uindex on Files (file_name);

create unique index Files_id_uindex on Files (id);



create table Courses
(
	id INTEGER not null,
	name TEXT not null,
	descrption TEXT
);

create unique index Courses_id_uindex on Courses (id);

create unique index Courses_name_uindex on Courses (name);



create table Comments
(
	id INTEGER not null,
	author_user_id INTEGER not null,
	content TEXT not null,
	created_timestamp INTEGER not null,
	course_id INTEGER not null
);

create unique index Comments_id_uindex on Comments (id);
