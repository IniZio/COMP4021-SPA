create table Users
(
	id INTEGER not null,
	username TEXT not null,
	hashed_password TEXT not null,
	picture_file_id INTEGER,
	first_name TEXT,
	last_name TEXT,
	email TEXT,
	major TEXT,
	year INTEGER,
	status TEXT
)
;

create unique index Users_Auth_id_uindex
	on Users (id)
;

create unique index Users_Auth_username_uindex
	on Users (username)
;

create unique index Users_email_uindex
	on Users (email)
;

create index Users_major_index
	on Users (major)
;



create table Resources
(
	id INTEGER not null,
	name TEXT not null,
	course_id INTEGER not null,
	description TEXT,
	type TEXT not null,
	file_id INTEGER,
	text_content TEXT
)
;

create unique index Resources_id_uindex
	on Resources (id)
;



create table Courses
(
	id INTEGER not null,
	name TEXT not null,
	descrption TEXT
)
;

create unique index Courses_id_uindex
	on Courses (id)
;

create unique index Courses_name_uindex
	on Courses (name)
;



create table Comments
(
	id INTEGER not null,
	author_user_id INTEGER not null,
	content TEXT not null,
	created_timestamp INTEGER not null,
	course_id INTEGER not null
)
;

create unique index Comments_id_uindex
	on Comments (id)
;


