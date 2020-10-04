CREATE TABLE IF NOT EXISTS people (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(256) NOT NULL,
  points int(11) DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- INSERT INTO people (id, name, points) VALUES
-- (1, 'Roberto Maravilha', 6),
-- (2, 'Oliver Tsubasa', 1),
-- (3, 'Benji Price', 3);

CREATE TABLE IF NOT EXISTS matches (
  id int(11) NOT NULL AUTO_INCREMENT,
  solids_id int(11) NOT NULL,
  stripes_id int(11) NOT NULL,
  date date NOT NULL,
  winner int(11) DEFAULT NULL,
  absencent int(11) DEFAULT NULL,
  solids_left int(11) DEFAULT 7,
  stripes_left int(11) DEFAULT 7,
  PRIMARY KEY (id),
  FOREIGN KEY (solids_id) REFERENCES people(id),
  FOREIGN KEY (stripes_id) REFERENCES people(id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS scores (
  matches_id int(11) NOT NULL,
  people_id int(11) NOT NULL,
  ball int(11) NOT NULL,
  FOREIGN KEY (matches_id) REFERENCES matches(id),
  FOREIGN KEY (people_id) REFERENCES people(id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;