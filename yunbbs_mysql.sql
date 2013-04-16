DROP TABLE IF EXISTS yunbbs_articles;
CREATE TABLE yunbbs_articles (
  id mediumint(8) unsigned NOT NULL auto_increment,
  cid smallint(6) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL default '0',
  ruid mediumint(8) unsigned NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  content mediumtext NOT NULL,
  addtime int(10) unsigned NOT NULL default '0',
  edittime int(10) unsigned NOT NULL default '0',
  views int(10) unsigned NOT NULL default '1',
  comments mediumint(8) unsigned NOT NULL default '0',
  closecomment tinyint(1) NOT NULL default '0',
  favorites int(10) unsigned NOT NULL default '0',
  visible tinyint(1) NOT NULL default '1',
  top tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (id),
  KEY cid (cid),
  KEY edittime (edittime),
  KEY uid (uid)
) ENGINE=MyISAM ;

DROP TABLE IF EXISTS yunbbs_categories;
CREATE TABLE yunbbs_categories (
  id smallint(6) unsigned NOT NULL auto_increment,
  name char(50) NOT NULL,
  articles mediumint(8) unsigned NOT NULL default '0',
  about text NOT NULL,
  PRIMARY KEY  (id),
  KEY articles (articles)
) ENGINE=MyISAM ;

INSERT INTO yunbbs_categories VALUES(1, '默认分类', 0, '');

DROP TABLE IF EXISTS yunbbs_comments;
CREATE TABLE yunbbs_comments (
  id int(10) unsigned NOT NULL auto_increment,
  articleid mediumint(8) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL default '0',
  addtime int(10) unsigned NOT NULL default '0',
  content mediumtext NOT NULL,
  PRIMARY KEY  (id),
  KEY articleid (articleid)
) ENGINE=MyISAM ;

DROP TABLE IF EXISTS yunbbs_links;
CREATE TABLE yunbbs_links (
  id smallint(6) unsigned NOT NULL auto_increment,
  name varchar(100) NOT NULL default '',
  url varchar(200) NOT NULL default '',
  PRIMARY KEY  (id)
) ENGINE=MyISAM ;

INSERT INTO yunbbs_links VALUES(null,'YouBBS', 'http://youbbs.sinaapp.com');

DROP TABLE IF EXISTS yunbbs_settings;
CREATE TABLE yunbbs_settings (
  title varchar(50) NOT NULL default '',
  value text NOT NULL,
  PRIMARY KEY  (title)
) ENGINE=MyISAM ;


INSERT INTO yunbbs_settings VALUES('name', 'youbbs');
INSERT INTO yunbbs_settings VALUES('site_des', 'YouBBS ACI字幕组修改版');
INSERT INTO yunbbs_settings VALUES('site_create', '0');
INSERT INTO yunbbs_settings VALUES('icp', '');
INSERT INTO yunbbs_settings VALUES('admin_email', '');
INSERT INTO yunbbs_settings VALUES('home_shownum', '20');
INSERT INTO yunbbs_settings VALUES('list_shownum', '20');
INSERT INTO yunbbs_settings VALUES('newest_node_num', '20');
INSERT INTO yunbbs_settings VALUES('hot_node_num', '20');
INSERT INTO yunbbs_settings VALUES('bot_node_num', '100');
INSERT INTO yunbbs_settings VALUES('article_title_max_len', '60');
INSERT INTO yunbbs_settings VALUES('article_content_max_len', '3000');
INSERT INTO yunbbs_settings VALUES('article_post_space', '60');
INSERT INTO yunbbs_settings VALUES('reg_ip_space', '3600');
INSERT INTO yunbbs_settings VALUES('comment_min_len', '4');
INSERT INTO yunbbs_settings VALUES('comment_max_len', '1200');
INSERT INTO yunbbs_settings VALUES('commentlist_num', '32');
INSERT INTO yunbbs_settings VALUES('comment_post_space', '20');
INSERT INTO yunbbs_settings VALUES('close', '0');
INSERT INTO yunbbs_settings VALUES('close_note', '服务器在睡觉，不要打搅，否则他会发飙的=_=||');
INSERT INTO yunbbs_settings VALUES('authorized', '0');
INSERT INTO yunbbs_settings VALUES('register_review', '0');
INSERT INTO yunbbs_settings VALUES('close_register', '0');
INSERT INTO yunbbs_settings VALUES('close_upload', '0');
INSERT INTO yunbbs_settings VALUES('ext_list', '');
INSERT INTO yunbbs_settings VALUES('img_shuiyin', '0');
INSERT INTO yunbbs_settings VALUES('show_debug', '0');
INSERT INTO yunbbs_settings VALUES('jquery_lib', '/static/js/jquery-1.6.4.js');
INSERT INTO yunbbs_settings VALUES('head_meta', '');
INSERT INTO yunbbs_settings VALUES('analytics_code', '');
INSERT INTO yunbbs_settings VALUES('safe_imgdomain', '');
INSERT INTO yunbbs_settings VALUES('upyun_domain', '');
INSERT INTO yunbbs_settings VALUES('upyun_user', '');
INSERT INTO yunbbs_settings VALUES('upyun_pw', '');
INSERT INTO yunbbs_settings VALUES('ad_post_top', '');
INSERT INTO yunbbs_settings VALUES('ad_post_bot', '');
INSERT INTO yunbbs_settings VALUES('ad_sider_top', '');
INSERT INTO yunbbs_settings VALUES('ad_web_bot', '');
INSERT INTO yunbbs_settings VALUES('main_nodes', '');
INSERT INTO yunbbs_settings VALUES('spam_words', '');
INSERT INTO yunbbs_settings VALUES('qq_scope', 'get_user_info');
INSERT INTO yunbbs_settings VALUES('qq_appid', '');
INSERT INTO yunbbs_settings VALUES('qq_appkey', '');
INSERT INTO yunbbs_settings VALUES('wb_key', '');
INSERT INTO yunbbs_settings VALUES('wb_secret', '');

DROP TABLE IF EXISTS yunbbs_users;
CREATE TABLE yunbbs_users (
  id mediumint(8) unsigned NOT NULL auto_increment,
  name varchar(20) NOT NULL default '',
  flag tinyint(2) NOT NULL default '0',
  avatar mediumint(8) unsigned NOT NULL default '0',
  password char(32) NOT NULL,
  email varchar(40) NOT NULL,
  url varchar(75) NOT NULL,
  articles int(10) unsigned NOT NULL default '0',
  replies int(10) unsigned NOT NULL default '0',
  regtime int(10) unsigned NOT NULL default '0',
  lastposttime int(10) unsigned NOT NULL default '0',
  lastreplytime int(10) unsigned NOT NULL default '0',
  about text NOT NULL,
  notic text NOT NULL,
  PRIMARY KEY  (id),
  KEY name (name)
) ENGINE=MyISAM ;

DROP TABLE IF EXISTS yunbbs_favorites;
CREATE TABLE yunbbs_favorites (
  id mediumint(8) unsigned NOT NULL auto_increment,
  uid mediumint(8) unsigned NOT NULL default '0',
  articles mediumint(8) unsigned NOT NULL default '0',
  content mediumtext NOT NULL default '',
  PRIMARY KEY (id),
  KEY uid (uid)
) ENGINE=MyISAM ;

DROP TABLE IF EXISTS yunbbs_qqweibo;
CREATE TABLE yunbbs_qqweibo (
  id mediumint(8) unsigned NOT NULL auto_increment,
  uid mediumint(8) unsigned NOT NULL default '0',
  name varchar(20) NOT NULL default '',
  openid char(32) NOT NULL,
  PRIMARY KEY (id),
  KEY uid (uid),
  KEY openid (openid)
) ENGINE=MyISAM ;

DROP TABLE IF EXISTS yunbbs_weibo;
CREATE TABLE yunbbs_weibo (
  id mediumint(8) unsigned NOT NULL auto_increment,
  uid mediumint(8) unsigned NOT NULL default '0',
  name varchar(20) NOT NULL default '',
  openid char(12) NOT NULL,
  PRIMARY KEY (id),
  KEY uid (uid),
  KEY openid (openid)
) ENGINE=MyISAM ;
