INSERT INTO Tarif (Tarif_horaire, Tarif_Demi_Journee, Tarif_Journee) VALUES
(5.00, 15.00, 25.00),   -- Vélo électrique urbain
(8.00, 20.00, 35.00),   -- VTT électrique
(4.00, 12.00, 20.00),   -- Hoverboard
(6.00, 18.00, 30.00),   -- Trottinette électrique
(7.00, 20.00, 32.00),   -- Gyropode / segway
(5.00, 14.00, 22.00);   -- Skateboard électrique

INSERT INTO Type_Vehicule (libelle_type, nb_place, id_tarif) VALUES
('Vélo électrique urbain', 1, 1),
('VTT électrique', 1, 2),
('Hoverboard', 1, 3),
('Trottinette électrique', 1, 4),
('Gyropode', 1, 5),
('Skateboard électrique', 1, 6);

INSERT INTO Agence_location (Nom_agence, Adresse, Telephone, Mail, Heure_ouverture) VALUES
('Annecy', '12 rue du Lac, 74000 Annecy', '0450000001', 'annecy@ecomobil.fr', '08:00:00'),
('Grenoble', '5 avenue Alsace Lorraine, 38000 Grenoble', '0450000002', 'grenoble@ecomobil.fr', '08:00:00'),
('Chambéry', '22 boulevard du Théâtre, 73000 Chambéry', '0450000003', 'chambery@ecomobil.fr', '08:00:00'),
('Valence', '33 rue Victor Hugo, 26000 Valence', '0450000004', 'valence@ecomobil.fr', '08:00:00'),
('Meylan (entrepôt)', '2 rue de l’Innovation, 38240 Meylan', '0450000005', 'meylan@ecomobil.fr', '07:00:00'),
('Lyon (direction)', '10 place Bellecour, 69000 Lyon', '0450000006', 'lyon@ecomobil.fr', '09:00:00'),
('Bron (direction + entrepôt)', '99 avenue Franklin Roosevelt, 69500 Bron', '0450000007', 'bron@ecomobil.fr', '07:00:00'),
('Saint-Etienne', '12 cours Fauriel, 42000 Saint-Etienne', '0450000008', 'stetienne@ecomobil.fr', '08:00:00'),
('Bourg-en-Bresse', '17 rue Alsace, 01000 Bourg-en-Bresse', '0450000009', 'bourg@ecomobil.fr', '08:00:00');

INSERT INTO Vehicule (Num_serie, Marque, Modele, Annee, Statut, id_type_Vehicule, id_Agence) VALUES
('VEL-001', 'Decathlon', 'Elops 500E', 2023, 'disponible', 1, 1),
('VEL-002', 'Decathlon', 'Elops 500E', 2023, 'disponible', 1, 1),
('VEL-003', 'Decathlon', 'Elops 500E', 2023, 'disponible', 1, 2);

INSERT INTO Vehicule (Num_serie, Marque, Modele, Annee, Statut, id_type_Vehicule, id_Agence) VALUES
('VTT-001', 'Specialized', 'Turbo Levo', 2023, 'disponible', 2, 1),
('VTT-002', 'Specialized', 'Turbo Levo', 2023, 'disponible', 2, 2);

INSERT INTO Client_connecter (Nom, Prenom, Telephone, Adresse, Mail, Mot_de_Passe_Securiser, Date_de_Creation) VALUES
('Durand', 'Paul', '0601020304', '15 rue des Alpes, 74000 Annecy', 'paul.durand@mail.fr', 'hashMdp123'),
('Martin', 'Sophie', '0611223344', '20 avenue du Rhône, 38000 Grenoble', 'sophie.martin@mail.fr', 'hashMdp456');

INSERT INTO Reservation (Date_reservation, Duree, Demande_speciale, Date_Debut_location, Montant_Totale, Date_fin_location, Statut_reservation, id_Client, id_Vehicule, id_Tarif) VALUES
(2, 'Casque enfant demandé', '2025-09-25 09:00:00', 10.00, '2025-09-25 11:00:00', 'confirmée', 1, 1, 1),
(1, '', '2025-09-26 14:00:00', 8.00, '2025-09-26 15:00:00', 'confirmée', 2, 2, 2);

INSERT INTO Participants (Nom, Prenom, Age, id_Reservation) VALUES
('Durand', 'Paul', 35, 1),
('Durand', 'Emma', 10, 1),
('Martin', 'Sophie', 28, 2);
