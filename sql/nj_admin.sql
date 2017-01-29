DROP TABLE IF EXISTS `nj_admin`;
CREATE TABLE IF NOT EXISTS `nj_admin` (
  `adminid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `adminuser` VARCHAR(32) NOT NULL DEFAULT '',
  `adminpassword` CHAR(32) NOT NULL DEFAULT '',
  `adminemail` VARCHAR(50) NOT NULL DEFAULT '',
  `loginip`  BIGINT NOT NULL DEFAULT '0',
  `logintime` INT UNSIGNED NOT NULL DEFAULT '0',
  `createtime` INT UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY(`adminid`),
  UNIQUE nj_admin_user_password(`adminuser`, `adminpassword`),
  UNIQUE nj_admin_user_email(`adminuser`, `adminemail`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `nj_admin`(adminuser, adminpassword, adminemail, createtime) VALUES('admin', md5('nj888'), 'mcyzlizhun@163.com', UNIX_TIMESTAMP());
