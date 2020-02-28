DELETE FROM "review";
DELETE FROM "candy_translation";
DELETE FROM "candy";

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