// users.sqlite file
//   
CREATE TABLE  users  (
   id  INTEGER PRIMARY KEY AUTOINCREMENT,
   username  TEXT DEFAULT NULL,
   email  TEXT DEFAULT NULL,
   password  TEXT NOT NULL,
   hash  TEXT NOT NULL,
   activated  INTEGER NOT NULL DEFAULT  0,
   user_type  INTEGER DEFAULT  1,
   created_at  TEXT DEFAULT NULL,
   updated_at  TEXT DEFAULT NULL
)

INSERT INTO users (id, username, email, password, activated, user_type,created_at,updated_at) 
VALUES (1,'<username>','<email>','<password hash>',1,1,NULL,NULL)

// schema.sqlite file
//
CREATE TABLE schema (
id INTEGER PRIMARY KEY AUTOINCREMENT,
_section TEXT DEFAULT NULL,
_type TEXT DEFAULT NULL,
_label TEXT DEFAULT NULL,
_name TEXT DEFAULT NULL,
_idx TEXT DEFAULT NULL,
_length INTEGER,
_required TEXT DEFAULT NULL,
_value TEXT DEFAULT NULL,
_style TEXT DEFAULT NULL,
_class TEXT DEFAULT NULL,
_event TEXT DEFAULT NULL,
_function TEXT DEFAULT NULL,
_order INTEGER DEFAULT 0,
_placeholder TEXT DEFAULT NULL,
_created_at TEXT DEFAULT NULL,
_updated_at TEXT DEFAULT NULL
, _joins TEXT DEFAULT NULL
, _sql_type TEXT DEFAULT NULL
, _visible TEXT DEFAULT 'YES'
, _mobile TEXT DEFAULT 'NO');

CREATE TABLE menus (
id INTEGER PRIMARY KEY AUTOINCREMENT,
section TEXT DEFAULT NULL,
item TEXT DEFAULT NULL,
url TEXT DEFAULT NULL,
_order INTEGER DEFAULT 0,
_created_at TEXT DEFAULT NULL,
_updated_at TEXT DEFAULT NULL
);

CREATE TABLE apps(
id INTEGER PRIMARY KEY AUTOINCREMENT,
Name TEXT DEFAULT NULL,
Company TEXT DEFAULT NULL,
_user_type INTEGER,
_company INTEGER,
_created_at TEXT DEFAULT NULL,
_updated_at TEXT DEFAULT NULL
);

CREATE TABLE company (
id integer primary key autoincrement,
Name text default null,
shortname text default null,
slogan text default null,
logo text default null,
hash text,
created_at text default null,
updated_at text default null
);

CREATE TABLE uploads (
id INTEGER PRIMARY KEY AUTOINCREMENT,
uid INTEGER DEFAULT 0,
section TEXT DEFAULT NULL,
originalFile TEXT DEFAULT NULL,
uploadFile TEXT DEFAULT NULL,
fileType TEXT DEFAULT NULL,
_created_at TEXT DEFAULT NULL,
_updated_at TEXT DEFAULT NULL
);
