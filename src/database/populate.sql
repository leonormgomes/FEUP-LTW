insert into User values ('rick.astley@protonmail.com','dog_lover','$2y$12$7KpaSxgcDnHbPZRIQCTSY.aCtghywos/CcGfKQN01Fi8RG3N2BldG', 'Rick', 'Astley','961234567','Dogs>Cats. I love dogs','1.gif','2.jpeg', 'Somewhere over the rainbow','1998-01-14'); 
-- password above is 1..9

insert into Animal values(NULL,'Doggo','default-animal.jpeg','Cute doggo','Porto',3,'M','dog',0);
insert into Animal values(NULL,'Catoo','default-animal.jpeg','Cute doggo','Lisboa',4,'S','cat',1);
insert into Animal values(NULL,'Doggy','default-animal.jpeg','Cute doggo','Lisboa',3,'M','dog',0);

insert into Owns values('rick.astley@protonmail.com',1);

INSERT INTO Post(title, content, photo, animal, person)values('Just a random title','Just a random description','3.jpeg',1,'rick.astley@protonmail.com');