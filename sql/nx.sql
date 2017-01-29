drop table if exists `nx_admin`;
create table if not exists `nx_admin` (
  `adminid` int unsigned not null auto_increment comment 'Admin ID',
  `adminuser` varchar(32) not null default '',
  `adminpass` char(32) not null default '',
  `adminemail` varchar(50) not null default '',
  `loginip`  bigint not null default '0',
  `logintime` int unsigned not null default '0',
  `createtime` int unsigned not null default '0',
  primary key(`adminid`),
  unique nx_admin_user_pass(`adminuser`, `adminpass`),
  unique nx_admin_user_email(`adminuser`, `adminemail`)
)engine=InnoDB default charset=utf8;

insert into `nx_admin`(adminuser, adminpass, adminemail, createtime) values('admin', md5('warrior'), 'mcyzlizhun@163.com', UNIX_TIMESTAMP());

drop table if exists `nx_user`;
create table if not exists `nx_user` (
  `userid` bigint unsigned not null auto_increment comment 'User ID',
  `username` varchar(32) not null default '',
  `userpass` char(32) not null default '',
  `useremail` varchar(32) not null default '',
  `createtime` date not null default '2017-01-01',
  unique nx_user_username_userpass(`username`, `userpass`),
  unique nx_user_useremail_userpass(`useremail`, `userpass`),
  primary key(`userid`)
)engine=InnoDB default charset=utf8;

drop table if exists `nx_user_profile`;
create table if not exists `nx_user_profile` (
  `id` bigint unsigned not null auto_increment comment "User Profile ID",
  `userid` bigint unsigned not null default '0',
  `truename` varchar(32) not null default ' ',
  `age` tinyint unsigned not null default '0',
  `sex` enum('0', '1', '2') not null default '0',
  unique nx_user_profile_userid(`userid`),
  primary key(`id`)
)engine=InnoDB default charset=utf8;
