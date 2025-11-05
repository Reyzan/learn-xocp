drop database if exists xocp;
create database xocp;
use xocp;


# KALO DOWNLOAD INI HARUS DOWNLOAD YANG LAINNYA
# SOALNYA YANG LAMA BEDA STRUKTUR UNTUK HTMLNYA
#
# 
#
# Multi Portal Management System
#--------------------------------------------------------
# 
CREATE TABLE portals (
  pid int(10) unsigned NOT NULL default '0',       # portal id
  portal_nm char(100) NOT NULL default '',
  login_ext char(5) NOT NULL default '',           # login extension in case multiple portal 
                                                   # in single site/server.
  host char(200) binary NOT NULL default '',       # ip address atau url portal
  PRIMARY KEY  (pid) 
) TYPE=MyISAM;


CREATE TABLE orgs (
  org_id int(10) unsigned NOT NULL auto_increment,
  org_nm text NOT NULL,
  addr_txt text NOT NULL,
  telecom text NOT NULL,
  begin_dttm datetime default NULL,
  end_dttm datetime default NULL,
  orgclass_id int(10) unsigned NOT NULL default '0',
  status_cd enum('active','inactive','normal','nullified') NOT NULL default 'active',
  parent_id int(10) unsigned NOT NULL default '0',  # parent organization.
                                                    # 1 organisasi hanya bisa punya 1 parent.
                                                    # kalo definisi akses ke multiple
                                                    # organisasi akan ditangani oleh tabel
                                                    # org_ access oleh masing-masing module.
  PRIMARY KEY  (org_id)
) TYPE=MyISAM;

# table org_resolve adalah table untuk
# menterjemahkan organisasi non lokal portal ke lokal portal
CREATE TABLE org_resolve (
  pid int(10) unsigned NOT NULL default '0',         # portal asal
  org_id int(10) unsigned NOT NULL default '0',      # id org di portal asal
  local_id int(10) unsigned NOT NULL default '0',    # id org di portal lokal
  PRIMARY KEY (pid,org_id)
);



######################################################
# below these are portal independent table
######################################################

CREATE TABLE modules (
  module_id char(50) NOT NULL default '',
  isactive enum('n','y') NOT NULL default 'y',
  del_ind enum('n','y') NOT NULL default 'y',
  PRIMARY KEY  (module_id)
) TYPE=MyISAM;

INSERT INTO modules VALUES ('system','y','y');
INSERT INTO modules VALUES ('calendar','y','y');

CREATE TABLE blocks (
  module_id char(50) NOT NULL default '0',
  class_nm char(60) NOT NULL default '',
  file_nm char(100) NOT NULL default '',
  catchvar char(5) NOT NULL default '',
  isactive enum('n','y') NOT NULL default 'y',
  side_allow char(30) NOT NULL default '',
  PRIMARY KEY  (module_id,class_nm)
) TYPE=MyISAM;

INSERT INTO blocks VALUES ('system','_system_Login','login.php','','','y');
INSERT INTO blocks VALUES ('system','_system_Whoami','whoami.php','','','y');
INSERT INTO blocks VALUES ('system','_system_Logout','logout.php','','','y');
INSERT INTO blocks VALUES ('calendar','_calendar_Small','calendar.php','','','y');

CREATE TABLE pages (
  page_no int(10) unsigned NOT NULL auto_increment,
  page_id char(50) NOT NULL default '',
  guest_dfl enum('n','y') NOT NULL default 'n',
  user_dfl enum('n','y') NOT NULL default 'n',
  PRIMARY KEY (page_no),
  UNIQUE KEY (page_id)
);

INSERT INTO pages VALUES (NULL,'startpage.php','y','n');

CREATE TABLE pages2blocks (
  block_id int(10) unsigned NOT NULL auto_increment,
  page_id char(50) NOT NULL default '',
  module_id char(50) NOT NULL default '',
  class_nm char(60) NOT NULL default '',
  weight int(10) unsigned NOT NULL default '0',
  side char(4) NOT NULL default '',
  rowspan tinyint(3) unsigned NOT NULL default '0',
  align char(6) NOT NULL default 'left',
  valign char(6) NOT NULL default 'top',
  PRIMARY KEY (block_id)
);

INSERT INTO pages2blocks VALUES (NULL,'startpage.php','system','_system_Whoami',1,'ABCD',0,'left','top');
INSERT INTO pages2blocks VALUES (NULL,'startpage.php','calendar','_calendar_Small',2,'A',0,'left','top');
INSERT INTO pages2blocks VALUES (NULL,'startpage.php','system','_system_Login',2,'BCD',0,'left','top');
INSERT INTO pages2blocks VALUES (NULL,'startpage.php','system','_system_Logout',3,'A',0,'left','top');

######################################################
# above these are portal independent table
######################################################









CREATE TABLE groups2pages (
  group_id int(10) unsigned NOT NULL default '0',
  page_id char(50) NOT NULL default '0',
  grant_priv enum('n','y') NOT NULL default 'n',
  passwd enum('n','y') NOT NULL default 'n',
  PRIMARY KEY (group_id,page_id)
);

CREATE TABLE groups2blocks (
  group_id int(10) unsigned NOT NULL default '0',
  module_id char(50) NOT NULL default '0',
  class_nm char(60) NOT NULL default '',
  grant_priv enum('n','y') NOT NULL default 'n',
  passwd enum('n','y') NOT NULL default 'n',
  PRIMARY KEY (group_id,module_id,class_nm)
);

CREATE TABLE custompages (
  cpage_id int(10) unsigned NOT NULL default '0',
  cpage_nm char(100) NOT NULL default '',
  user_id int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (cpage_id,user_id)
);

CREATE TABLE custompages2blocks (
  user_id int(10) unsigned NOT NULL default '0',
  cpage_id int(10) unsigned NOT NULL default '0',
  module_id char(50) NOT NULL default '0',
  class_nm char(60) NOT NULL default '0',
  weight int(10) unsigned NOT NULL default '0',
  side char(4) NOT NULL default '',
  rowspan tinyint(3) unsigned NOT NULL default '1',
  align char(6) NOT NULL default 'left',
  valign char(6) NOT NULL default 'top',
  PRIMARY KEY (cpage_id,module_id,class_nm)
);

# table : groups -> portal groups
CREATE TABLE groups (
  group_id int(10) unsigned NOT NULL auto_increment,
  group_cd char(50) NOT NULL default '',
  PRIMARY KEY (group_id),
  UNIQUE KEY (group_cd)
) TYPE=MyISAM;

INSERT INTO groups VALUES (1,'GUEST');
INSERT INTO groups VALUES (2,'ADMIN_PORTAL');

CREATE TABLE menuitems (
  menuitem_id int(10) unsigned NOT NULL auto_increment,
  group_id int(10) unsigned NOT NULL default '0',
  weight int(10) unsigned NOT NULL default '0',
  menu_nm char(100) NOT NULL default '',
  page_id char(50) NOT NULL default '',
  extra_prm char(255) NOT NULL default '',
  PRIMARY KEY  (menuitem_id)
) TYPE=MyISAM;

INSERT INTO menuitems VALUES (NULL,1,0,'My First Menu Item',2,'');

# table : users
# 
CREATE TABLE users (
  user_id int(10) unsigned NOT NULL default '0',
  person_id int(10) unsigned NOT NULL default '0',
  user_nm char(30) NOT NULL default '',
  pwd0 char(32) NOT NULL default '',            # encrypted password.
  pwd1 char(32) NOT NULL default '',            # plain password (reset).
  language char(32) NOT NULL default '',
  avatar char(30) NOT NULL default '',
  regdate int(10) unsigned NOT NULL default '0',
  icq char(15) NOT NULL default '',
  viewemail enum('n','y') NOT NULL default 'n',
  aim char(18) NOT NULL default '',
  yim char(25) NOT NULL default '',
  msnm char(25) NOT NULL default '',
  startpage char(50) NOT NULL default '',
  user_theme enum('n','y') NOT NULL default 'y', # user may use theme other than system default.
  theme char(250) NOT NULL default '',           # theme selected.
  sig char(250) NOT NULL default '',            # signature text in e-mail.
  attachsig enum('n','y') NOT NULL default 'n', # attach signature in e-mail.
  tz_offset tinyint(3) NOT NULL default '0',    # time zone offset.
  popmsgon enum('n','y') NOT NULL default 'y',  # pop message is on.
  last_login int(10) unsigned NOT NULL default '0',
  status_cd enum('active','inactive','nullified') NOT NULL default 'inactive',
  PRIMARY KEY (user_id),
  UNIQUE KEY (person_id)
) TYPE=MyISAM;

INSERT INTO users (user_id,person_id,user_nm,pwd0) VALUES (0,0,'guest','');
UPDATE users SET user_id = 0;
UPDATE users SET person_id = 0;
INSERT INTO users (user_id,person_id,user_nm,pwd0,startpage) VALUES (1,1,'adiet',md5('asdf'),'startpage.php');
UPDATE users SET status_cd = 'active';

# table : lnk_users_groups
CREATE TABLE users2groups (
  user_id int(10) unsigned NOT NULL default '0',
  group_id int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (user_id,group_id)
) TYPE=MyISAM;

INSERT INTO users2groups VALUES(0,1);

CREATE TABLE persons (
  person_id int(10) unsigned NOT NULL default '0',
  ssn char(250) NOT NULL default '',
  ext_id text NOT NULL,
  person_nm text NOT NULL,

  metaphone_nm text NOT NULL,
  adm_gender_cd enum('m','f') NOT NULL default 'm',
  addr_txt text NOT NULL,
  zip_cd char(5) NOT NULL default '',
  telecom text NOT NULL,
  status_cd enum('active','inactive','normal','nullified') NOT NULL default 'active',
  del_ind enum('n','y') NOT NULL default 'y',
  PRIMARY KEY  (person_id)
) TYPE=MyISAM;


############# DHS PROJECT #############################      
#######################################################
# INDIKATOR KESEHATAN

# untuk modul indikator kesehatan, tabel-tablenya
# sudah cukup stabil, dan reliable untuk mengatasi
# segala kondisi.

CREATE TABLE ind_template (
  tmpl_id int(10) unsigned NOT NULL auto_increment,
  tmpl_nm text NOT NULL,
  tmpl_vars text NOT NULL,
  tmpl_unit text NOT NULL,
  formula text NOT NULL,
  description text NOT NULL,
  PRIMARY KEY (tmpl_id)
);

CREATE TABLE ind_org_seq (
  org_id int(10) unsigned NOT NULL default '0',
  ind_id_seq int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (org_id)
);

CREATE TABLE ind_items (
  org_id int(10) unsigned NOT NULL default '0',
  ind_id int(10) unsigned NOT NULL default '0',

  ind_nm text NOT NULL,
  description text NOT NULL,
  tmpl_id int(10) unsigned NOT NULL default '0',
  ind_vars text NOT NULL,
  ind_value text NOT NULL,
  post_dttm datetime NOT NULL default '0000-00-00 00:00:00',
  created datetime NOT NULL default '0000-00-00 00:00:00',
  modified datetime NOT NULL default '0000-00-00 00:00:00',
  publish enum('n','y') NOT NULL default 'y',
  PRIMARY KEY (org_id,ind_id)
);

# table : ind_org2org
# dengan table ini, maka organisasi atas (kabupaten), bisa
# merekap/merangkum indikator bawah (kecamatan)

CREATE TABLE ind_org2org (
  org_id int(10) unsigned NOT NULL default '0',     # super organization.
  sub_id int(10) unsigned NOT NULL default '0',     # sub organization.
  PRIMARY KEY  (org_id,sub_id)
) TYPE=MyISAM;







#######################################################
# MANAJEMEN PROYEK


CREATE TABLE prj_projects (
  org_id int(10) unsigned NOT NULL default '0',       # org_id dimana proyek didaftar
  project_id int(10) unsigned NOT NULL default '0',   
  project_nm char(250) NOT NULL default '',
  description text NOT NULL,

  owner_id int(10) unsigned NOT NULL default '0',     # person_id this project belong

  priority tinyint(3) unsigned NOT NULL default '0',

  parent_id int(10) unsigned NOT NULL default '0',    # parent project
  parent_org int(10) unsigned NOT NULL default '0',   # org of the parent project

  created datetime NOT NULL default '0000-00-00 00:00:00',
  modified datetime NOT NULL default '0000-00-00 00:00:00',

  phase_set int(10) unsigned NOT NULL default '0',    # current project phase

  status tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY (org_id,project_id)
);

CREATE TABLE prj_tasks (
  task_id int(10) unsigned NOT NULL default '0',
  parent_id int(10) unsigned NOT NULL default '0',    # parent task_id
  task_nm char(155) default NULL,
  description text,

  project_id int(10) unsigned NOT NULL default '0',   # project_id of the task belong to
  owner_id int(10) unsigned NOT NULL default '0',     # person_id pemilik
  assigned_to int(10) unsigned NOT NULL default '0',  # person_id pelaksana tugas

  priority int(10) unsigned NOT NULL default '0',
  status int(10) unsigned NOT NULL default '0',

  confirm_st enum('n','y') NOT NULL default 'n',      # tugas butuh konfirmasi oleh pelaksana
                                                      # bahwa tugas diterima
  confirmed datetime default NULL,                    # set NULL kalo belum ada konfirmasi

  start_dt date NOT NULL default '0000-00-00',        # waktu tugas dimulai
  due_dt date NOT NULL default '0000-00-00',          # batas waktu pelaksanaan
  estimated_time date NOT NULL default '0000-00-00',    # perkiraan tugas selesai
  actual_time datetime NOT NULL default '0000-00-00 00:00:00',     # ...?
  complete_date date NOT NULL default '0000-00-00 00:00:00',   # waktu tugas selesai
  comments text NOT NULL,
  completion int(10) unsigned NOT NULL default '0',
  created datetime NOT NULL default '0000-00-00 00:00:00',  # record ini dibikin
  modified datetime NOT NULL default '0000-00-00 00:00:00', # waktu record di modifikasi
  assigned datetime NOT NULL default '0000-00-00 00:00:00', # tanggal tugas diberikan
  published enum('n','y') NOT NULL default 'y',
  project_phase int(0) unsigned NOT NULL default '0', # tugas dibuat pada fase proyek yg mana
  PRIMARY KEY  (task_id,project_id)
) TYPE=MyISAM;

CREATE TABLE prj_access (
  org_id int(10) unsigned NOT NULL default '0',     # organisasi yang mengakses.
  target_id int(10) unsigned NOT NULL default '0',  # organisasi yg jadi target akses.
  PRIMARY KEY  (org_id,target_id)
) TYPE=MyISAM;


##########################################################
