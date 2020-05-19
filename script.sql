/*Drop existing table */
DROP TABLE IF EXISTS scores, reponses, questions, quizz, categories, utilisateurs;

/*Table Creation */
CREATE TABLE utilisateurs
(	
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	login VARCHAR(100) NOT NULL, 
	mdp VARCHAR(100) NOT NULL, 
	date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
	CONSTRAINT uni_util UNIQUE(login)
);

CREATE TABLE categories
(
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	nom VARCHAR(100) NOT NULL, 
	CONSTRAINT uni_categories UNIQUE(nom) 
);

CREATE TABLE quizz 
(
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_createur INT NOT NULL,
	nom VARCHAR(100) NOT NULL, 
	id_categorie INT NOT NULL,
	description VARCHAR(300) NOT NULL,
	date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
	url VARCHAR(500),
	CONSTRAINT fk_id_createur FOREIGN KEY (id_createur) REFERENCES utilisateurs(id),
	CONSTRAINT fk_id_categorie FOREIGN KEY (id_categorie) REFERENCES categories(id),
	CONSTRAINT uni_quizz_categorie UNIQUE(id_categorie, nom)
);

CREATE TABLE questions
(
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	id_quizz INT,
	question VARCHAR(200) NOT NULL, 
	FOREIGN KEY (id_quizz) REFERENCES quizz(id)
);

CREATE TABLE reponses
(
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_question INT NOT NULL, 
	reponse VARCHAR(100) NOT NULL, 
	correct BOOLEAN NOT NULL DEFAULT 0, 
	CONSTRAINT fk_id_question FOREIGN KEY (id_question) REFERENCES questions(id)
);

CREATE TABLE scores
(
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	id_quizz INT NOT NULL, 
	id_utilisateur INT NOT NULL, 
	score INT NOT NULL DEFAULT 0,
	CONSTRAINT fk_id_quizz FOREIGN KEY (id_quizz) REFERENCES quizz(id),
	CONSTRAINT fk_id_utilisateur FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id),
	CONSTRAINT uniq_quizz_utilisateur UNIQUE(id_quizz, id_utilisateur)
);

/*Insert USERS */
INSERT INTO utilisateurs (login, mdp)
VALUES ('iborne', 'olaf');

INSERT INTO utilisateurs (login, mdp)
VALUES ('rlaroze', 'pikachu');

INSERT INTO utilisateurs (login, mdp)
VALUES ('aly', 'quentin');

INSERT INTO utilisateurs (login, mdp)
VALUES ('lnedjar', 'meunuiserie');

INSERT INTO utilisateurs (login, mdp)
VALUES ('rvende', 'campagne');



/*INSERT CATEGORIES */

INSERT INTO categories (nom) VALUES ('Jeux vidéos');

INSERT INTO categories (nom) VALUES ('Films');



/*INSERT QUIZZ */
INSERT INTO quizz (id_createur, nom, id_categorie, description)
VALUES ( (SELECT id FROM utilisateurs WHERE login='iborne'), 
		 'Disney', 
		 (SELECT id FROM categories WHERE nom='Films') , 
		 'Le monde merveilleux des dauphins ! '
);


INSERT INTO quizz (id_createur, nom, id_categorie, description)
VALUES ( (SELECT id FROM utilisateurs WHERE login='rlaroze'), 
		 'Pokemon', 
		 (SELECT id FROM categories WHERE nom='Jeux Vidéos'), 
		 'Le monde merveilleux des pikachus !'
);

INSERT INTO quizz (id_createur, nom, id_categorie, description)
VALUES ( (SELECT id FROM utilisateurs WHERE login='rlaroze'), 
		 'Uncharted', 
		 (SELECT id FROM categories WHERE nom='Jeux Vidéos'), 
		 'Le monde merveilleux des pikachus !'
);

INSERT INTO quizz (id_createur, nom, id_categorie, description)
VALUES ( (SELECT id FROM utilisateurs WHERE login='rlaroze'), 
		 'Call of Duty !', 
		 (SELECT id FROM categories WHERE nom='Jeux Vidéos'), 
		 'Le monde merveilleux des pikachus !'
);


/*INSERT QUESTIONS */
INSERT INTO questions (id_quizz, question)
VALUES ( (SELECT id FROM quizz WHERE nom='Disney'), 
	'Comment s\'appellent les amis d\'Ariel ? '
);



/*INSERT REPONSES */
INSERT INTO reponses (id_question, reponse)
VALUES ( (SELECT id FROM questions WHERE question='Comment s\'appellent les amis d\'Ariel ? '),
 		'Patrick et bob'
);

INSERT INTO reponses (id_question, reponse)
VALUES ( (SELECT id FROM questions WHERE question='Comment s\'appellent les amis d\'Ariel ? '),
 		'Olaf et sven'
);

INSERT INTO reponses (id_question, reponse, correct)
VALUES ( (SELECT id FROM questions WHERE question='Comment s\'appellent les amis d\'Ariel ? '),
 		'Sebastien et polochon',
  		'1'
);

/*INSERT SCORE*/

INSERT INTO scores (score, id_utilisateur, id_quizz)
VALUES ( 100,
		(SELECT id FROM utilisateurs WHERE login='rlaroze'),
		(SELECT id FROM quizz WHERE nom='Disney')

);

INSERT INTO scores (score, id_utilisateur, id_quizz)
VALUES ( 200,
		(SELECT id FROM utilisateurs WHERE login='rlaroze'),
		(SELECT id FROM quizz WHERE nom='Pokemon')

);

INSERT INTO scores (score, id_utilisateur, id_quizz)
VALUES ( 300,
		(SELECT id FROM utilisateurs WHERE login='rlaroze'),
		(SELECT id FROM quizz WHERE nom='Uncharted')

);

INSERT INTO scores (score, id_utilisateur, id_quizz)
VALUES ( 400,
		(SELECT id FROM utilisateurs WHERE login='rlaroze'),
		(SELECT id FROM quizz WHERE nom='Call of Duty !')

);

INSERT INTO scores (score, id_utilisateur, id_quizz)
VALUES ( 300,
		(SELECT id FROM utilisateurs WHERE login='iborne'),
		(SELECT id FROM quizz WHERE nom='Uncharted')

);

INSERT INTO scores (score, id_utilisateur, id_quizz)
VALUES ( 300,
		(SELECT id FROM utilisateurs WHERE login='rvende'),
		(SELECT id FROM quizz WHERE nom='Uncharted')

);

INSERT INTO scores (score, id_utilisateur, id_quizz)
VALUES ( 400,
		(SELECT id FROM utilisateurs WHERE login='iborne'),
		(SELECT id FROM quizz WHERE nom='Call of Duty !')

);