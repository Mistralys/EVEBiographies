----
-- Table structure for characters
----
CREATE TABLE 'characters' ('character_id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'foreign_id' TEXT, 'name' TEXT, 'date_added' DATETIME, 'last_login' DATETIME, 'amount_logins' INTEGER DEFAULT 0, 'biography_id'  INTEGER NOT NULL  , 'slug' TEXT, 'portrait_filetype' TEXT DEFAULT 'jpg', 'terms_accepted' BOOLEAN, 'blocked' BOOLEAN DEFAULT 0);

----
-- Table structure for biographies
----
CREATE TABLE 'biographies' ('biography_id' INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 'text' TEXT, 'skin' TEXT, 'background' TEXT, 'font' TEXT, 'published' BOOLEAN DEFAULT 0, 'blocked' BOOLEAN DEFAULT 0, 'words' INTEGER DEFAULT 0, 'views' INTEGER DEFAULT 0);