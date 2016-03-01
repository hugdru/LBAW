CREATE TABLE User(
idUser INTEGER PRIMARY KEY AUTOINCREMENT,
idProfPic INTEGER REFERENCES Image(idImage) ON UPDATE CASCADE,
name VARCHAR NOT NULL,
username VARCHAR NOT NULL,
password VARCHAR NOT NULL,
loginAttempts INTEGER NOT NULL,
lastLoginAttempt INTEGER NOT NULL,
description VARCHAR NOT NULL
);

CREATE TABLE Event(
idEvent INTEGER PRIMARY KEY AUTOINCREMENT,
idCover INTEGER REFERENCES Image(idImage) ON UPDATE CASCADE,
idOwner INTEGER REFERENCES User(idUser) ON UPDATE CASCADE,
name VARCHAR NOT NULL,
eventDate DATE NOT NULL,
description VARCHAR NOT NULL,
location VARCHAR NOT NULL,
eventType VARCHAR NOT NULL,
visibility INTEGER NOT NULL
);

CREATE TABLE EventThread(
idEventThread INTEGER PRIMARY KEY AUTOINCREMENT,
idEvent INTEGER REFERENCES Event(idEvent) ON UPDATE CASCADE,
idOwner INTEGER REFERENCES User(idUser) ON UPDATE CASCADE,
threadText VARCHAR NOT NULL
);

CREATE TABLE EventThreadComment(
idEventThreadComment INTEGER PRIMARY KEY AUTOINCREMENT,
idEventThread INTEGER REFERENCES EventThread(idEventThread) ON UPDATE CASCADE,
idOwner INTEGER REFERENCES User(idUser) ON UPDATE CASCADE,
commentText VARCHAR NOT NULL
);

CREATE TABLE InvitedEvent(
idIE INTEGER PRIMARY KEY AUTOINCREMENT,
idUser INTEGER REFERENCES User(idUser) ON UPDATE CASCADE,
idEvent INTEGER REFERENCES Event(idEvent) ON UPDATE CASCADE,
intention INTEGER NOT NULL
);

CREATE TABLE Image(
idImage INTEGER PRIMARY KEY AUTOINCREMENT,
idEvent INTEGER REFERENCES Event(idEvent) ON UPDATE CASCADE,
idAlbum INTEGER REFERENCES Album(idAlbum) ON UPDATE CASCADE,
description VARCHAR
);

CREATE TABLE Album(
idAlbum INTEGER PRIMARY KEY AUTOINCREMENT,
idEvent INTEGER REFERENCES Event(idEvent) ON UPDATE CASCADE,
title VARCHAR
);

CREATE TRIGGER DeleteAlbum BEFORE
DELETE ON Album
BEGIN
  DELETE FROM Image WHERE Image.idAlbum = OLD.idAlbum;
END;

CREATE TRIGGER DeleteEventThread BEFORE
DELETE ON EventThread
BEGIN
  DELETE FROM EventThreadComment WHERE EventThreadComment.idEventThread = OLD.idEventThread;
END;

CREATE TRIGGER DeleteEvent BEFORE
DELETE ON Event
BEGIN
  DELETE FROM Image WHERE Image.idEvent = OLD.idEvent;
  DELETE FROM Album WHERE Album.idEvent = OLD.idEvent;
  DELETE FROM EventThread WHERE EventThread.idEvent = OLD.idEvent;
  DELETE FROM InvitedEvent WHERE InvitedEvent.idEvent = OLD.idEvent;
END;

INSERT INTO Image VALUES(0, NULL, NULL, '');
