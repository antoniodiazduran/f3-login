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
VALUES (2,'adiaz','antonio@diaz.works','$2y$10$One/z.61I/ZaCejNpreP/e4K372D3QvzsLc0RPADUYmYJogD8g.su',1,1,NULL,NULL)
