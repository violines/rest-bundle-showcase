DELETE FROM "review";
DELETE FROM "candy_translation";
DELETE FROM "candy";

INSERT INTO "candy" ("id","gtin","weight")
VALUES (1,'886037363214',5),(2,'9272037363324',10),(3,'5567037363214',15),(4,'893037363214',20);

INSERT INTO "candy_translation" ("id","candy_id","language","title")
VALUES 
(DEFAULT, 1,'en','White Choclate Crisp'),
(DEFAULT, 1,'de','Weiße Schokolade mit Krisp'),
(DEFAULT, 2,'en','Peanut Butter Cup'),
(DEFAULT, 2,'de','Erdnuss Cups'),
(DEFAULT, 3,'en','Dark Chocolate'),
(DEFAULT, 3,'de','Zartbitter Schokolade'),
(DEFAULT, 4,'en','Princess Cake'),
(DEFAULT, 4,'de','Prinzessinen Rolle');