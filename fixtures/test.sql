DELETE FROM "product_translation";
DELETE FROM "review";
DELETE FROM "product";
DELETE FROM "user";
DELETE FROM "category";

INSERT INTO "product" ("id","gtin","weight")
VALUES 
(1,'886037363214',5),
(2,'9272037363324',10),
(3,'5567037363214',15),
(4,'893037363214',20);
SELECT setval('product_id_seq', 4);

INSERT INTO "product_translation" ("id","product_id","language","title")
VALUES 
(1,(SELECT id FROM product WHERE gtin = '886037363214'),'en','White Choclate Crisp'),
(2,(SELECT id FROM product WHERE gtin = '886037363214'),'de','Weiße Schokolade mit Krisp'),
(3,(SELECT id FROM product WHERE gtin = '9272037363324'),'en','Peanut Butter Cup'),
(4,(SELECT id FROM product WHERE gtin = '9272037363324'),'de','Erdnuss Cups'),
(5,(SELECT id FROM product WHERE gtin = '5567037363214'),'en','Dark Chocolate'),
(6,(SELECT id FROM product WHERE gtin = '5567037363214'),'de','Zartbitter Schokolade'),
(7,(SELECT id FROM product WHERE gtin = '893037363214'),'en','Princess Cake'),
(8,(SELECT id FROM product WHERE gtin = '893037363214'),'de','Prinzessinen Rolle');
SELECT setval('product_translation_id_seq', 8);

/* password is always: 'pass1234' */
INSERT INTO "user" ("id","email","password","key","roles")
VALUES 
(1,'import@test.test','$argon2id$v=19$m=65536,t=4,p=1$Ko4WKUHmT33ALGE9HqlL8g$X9HwgAEeILZU4ESXOozez0zw7chBwXYZjvApW4un490','USKRZAOT', '["ROLE_IMPORT"]'),
(2,'admin@test.test','$argon2id$v=19$m=65536,t=4,p=1$Ko4WKUHmT33ALGE9HqlL8g$X9HwgAEeILZU4ESXOozez0zw7chBwXYZjvApW4un490',NULL, '["ROLE_ADMIN"]'),
(3,'user@test.test','$argon2id$v=19$m=65536,t=4,p=1$Ko4WKUHmT33ALGE9HqlL8g$X9HwgAEeILZU4ESXOozez0zw7chBwXYZjvApW4un490',NULL, '[]');
SELECT setval('user_id_seq', 3);

INSERT INTO "review" ("id", "product_id", "user_id", "taste", "ingredients", "healthiness", "packaging", "availability", "comment")
VALUES
(1, 2, 1, 4, 5, 5, 3, 5, 'This was the best ever.'),
(2, 2, 1, 5, 3, 4, 5, 4, 'I really liked it.'),
(3, 2, 1, 4, 4, 5, 5, 5, 'Solid.'),
(4, 2, 1, 3, 5, 4, 5, 5, 'The best!'),
(5, 2, 1, 4, 5, 3, 5, 5, 'I really liked it.');
SELECT setval('review_id_seq', 5);

INSERT INTO "category" ("id","key","sorting")
VALUES 
(1,'Chocolate',1),
(2,'Biscuits',2),
(3,'Bubblegum',3);
SELECT setval('category_id_seq', 3);