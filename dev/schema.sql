drop table appuser cascade;
drop table restaurant cascade;

create table appuser (
	id varchar(50) primary key,
	password varchar(50),
  gender varchar(6),
  birthday varchar(10),
  timesvoted integer default 0,
  bio varchar(150) default ' '
);

insert into appuser values('auser', 'apassword');

create table restaurant (
	name varchar(50) primary key,
	rating numeric default 1200,
  wins integer default 0,
  ties integer default 0,
  loss integer default 0
);

\copy restaurant(name) from '~/www/repo_ShimizuSenpai/restaurantMash/dev/restaurants.txt'