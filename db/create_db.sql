
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


INSERT INTO  users  ( id ,  username ,  email ,  password ,  hash ,  activated ,  user_type ,  created_at ,  updated_at ) VALUES
(1, 'admin', 'admin@mail.invalid', '$2y$10$65g7M7Zpx5v7Mk65T59Vf.zREqI3m2gkpa/vaOHdpSGhf0E92On6q', '', 1, 100, NULL, '2019-03-27 13:57:32');
