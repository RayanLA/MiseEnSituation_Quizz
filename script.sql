/*Drop existing table */
DROP TABLE IF EXISTS utilisateurs, categories, quizz, questions,
					reponses, scores;


/*Table Creation */
CREATE TABLE utilisateurs as U 
(	
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	login VARCHAR(100) NOT NULL, 
	mdp VARCHAR(100) NOT NULL, 
	CONSTRAINT uni_util UNIQUE(login)
);

CREATE TABLE categories as C
(
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	nom VARCHAR(100) NOT NULL, 
	CONSTRAINT uni_categories UNIQUE(nom) 
);

CREATE TABLE quizz 
(
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	login_createur VARCHAR(100) NOT NULL,
	nom VARCHAR(100) NOT NULL, 
	id_categorie INT NOT NULL, 
	FOREIGN KEY (login_createur) REFERENCES U(id),
	FOREIGN KEY (id_categorie) REFERENCES C(id)?
	CONSTRAINT uni_quizz_categorie UNIQUE(id_categorie, nom)
);

CREATE TABLE questions as Q 
(
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	id_quizz INT,
	question VARCHAR(200) NOT NULL, 
	FOREIGN KEY (id_quizz) REFERENCES quizz(id)
);

CREATE TABLE reponses as R 
(
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	id_question INT, 
	reponse VARCHAR(100) NOT NULL, 
	correct BOOLEAN NOT NULL DEFAULT 0, 
	FOREIGN KEY id_question REFERENCES Q(id)
);

CREATE TABLE scores	as S 
(
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	id_quizz INT NOT NULL, 
	id_utilisateur INT NOT NULL, 
	FOREIGN KEY id_quizz REFERENCES quizz(id),
	FOREIGN KEY id_utilisateur REFERENCES U(id),
	CONSTRAINT uniq_quizz_utilisateur UNIQUE(id_quizz, id_utilisateur)
);

/*Insert */