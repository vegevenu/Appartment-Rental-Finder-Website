create database rentals;

use rentals;
create table users (id int auto_increment,
					username varchar(20) unique not null,
                    password varchar(20) not null,
					primary key(id))ENGINE = MYISAM;


create table user_info (info_id int auto_increment,
						user_id int not null,
						first_name varchar(25) not null,
						last_name varchar(25) not null,
						street varchar(25),
						city varchar(20) not null,
						state varchar(20) not null,
						country varchar(20) not null,
						email varchar(50) not null,
						phone varchar(15),
						primary key(info_id),
						foreign key(user_id) references users(id))ENGINE = MYISAM;


create table prop_ads (prop_id int auto_increment,
						user_id int not null,
						prop_title varchar(40) not null,
						prop_type varchar(15) not null,
						prop_street varchar(25),
						prop_city varchar(20) not null,
						prop_state varchar(20) not null,
						prop_country varchar(20) not null,
						prop_zip varchar(10) not null,
						prop_description varchar(255), 
						prop_price double not null,
						primary key(prop_id),
						foreign key(user_id) references users(id))ENGINE = MYISAM;
commit;