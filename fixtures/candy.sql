DELETE FROM "review";
DELETE FROM "candy_translation";
DELETE FROM "candy";
DELETE FROM "user";
DELETE FROM "category";

INSERT INTO "candy" ("gtin","weight")
VALUES ('886037363214',5),('9272037363324',10),('5567037363214',15),('893037363214',20);

INSERT INTO "candy_translation" ("candy_id","language","title")
VALUES 
((SELECT id FROM candy WHERE gtin = '886037363214'),'en','White Choclate Crisp'),
((SELECT id FROM candy WHERE gtin = '886037363214'),'de','Weiße Schokolade mit Krisp'),
((SELECT id FROM candy WHERE gtin = '9272037363324'),'en','Peanut Butter Cup'),
((SELECT id FROM candy WHERE gtin = '9272037363324'),'de','Erdnuss Cups'),
((SELECT id FROM candy WHERE gtin = '5567037363214'),'en','Dark Chocolate'),
((SELECT id FROM candy WHERE gtin = '5567037363214'),'de','Zartbitter Schokolade'),
((SELECT id FROM candy WHERE gtin = '893037363214'),'en','Princess Cake'),
((SELECT id FROM candy WHERE gtin = '893037363214'),'de','Prinzessinen Rolle');

/* password is always: 'pass1234' */
INSERT INTO "user" ("email","password","key","roles")
VALUES 
('import@test.test','$argon2id$v=19$m=65536,t=4,p=1$Ko4WKUHmT33ALGE9HqlL8g$X9HwgAEeILZU4ESXOozez0zw7chBwXYZjvApW4un490','USKRZAOT', '["ROLE_IMPORT"]'),
('admin@test.test','$argon2id$v=19$m=65536,t=4,p=1$Ko4WKUHmT33ALGE9HqlL8g$X9HwgAEeILZU4ESXOozez0zw7chBwXYZjvApW4un490',NULL, '["ROLE_ADMIN"]'),
('user@test.test','$argon2id$v=19$m=65536,t=4,p=1$Ko4WKUHmT33ALGE9HqlL8g$X9HwgAEeILZU4ESXOozez0zw7chBwXYZjvApW4un490',NULL, '[]');

INSERT INTO "category" ("key","sorting")
VALUES 
('Chocolate',1),
('Biscuits',2),
('Bubblegum',3);