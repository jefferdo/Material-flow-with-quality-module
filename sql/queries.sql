use mFlow;
select * from woht;
SELECT id from woht ORDER BY id desc LIMIT 1;
SELECT id from umf ORDER BY id desc LIMIT 1;
INSERT INTO umf (id, priLev, passwd, name, lsl) VALUES ('001', '0', 'passwd1', 'name', CURRENT_TIMESTAMP()) ON DUPLICATE KEY UPDATE priLev = '0', passwd ='passwd2', name = 'name', lsl = CURRENT_TIMESTAMP();
INSERT INTO umf (id, prilev, passwd, name, lsl, bc, hkey) VALUES ('001', '0', '106c5724ef30d43d173a9eed9a574722c135e591', 'jeewaka', '2019-09-24 13:55:24', '123456789', '112233445566778899') ON DUPLICATE KEY UPDATE prilev = '0', passwd ='106c5724ef30d43d173a9eed9a574722c135e591', name = 'jeewaka', lsl = '2019-09-24 13:55:24', bc = '123456789', hkey = '112233445566778899';
SELECT * FROM mFlow.umf;
select id, qty, td as data from poht inner join podt on poht.id = podt.poid;
SELECT * FROM umf where id = '001';