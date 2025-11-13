USE Ecomobil;

-- Table des tarifs
INSERT INTO Tarif (Tarif_horaire, Tarif_Demi_Journee, Tarif_Journee) VALUES
(8.00, 30.00, 40.00),   -- Vélo électrique urbain
(9.00, 32.00, 45.00),   -- VTT électrique
(7.00, 25.00, 35.00),   -- Hoverboard & Trottinette électrique
(10.00, 40.00, 60.00),  -- Gyropode / Segway
(8.00, 25.00, 35.00);   -- Skateboard électrique

-- Table des types de véhicules
INSERT INTO Type_Vehicule (libelle_type, nb_place, id_tarif) VALUES
('Vélo électrique urbain', 1, 1),
('VTT électrique', 1, 2),
('Hoverboard', 1, 3),
('Trottinette électrique', 1, 3),
('Gyropode', 1, 4),
('Skateboard électrique', 1, 5);

-- Table des agences
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

-- Table des véhicules (exemple : 10 par type)
INSERT INTO Vehicule (Num_serie, Marque, Modele, Annee, Statut, id_type_Vehicule, id_Agence) VALUES
-- Vélos urbains électriques
('VEL-001', 'Decathlon', 'Elops 500E', 2023, 'disponible', 1, 1),
('VEL-002', 'Decathlon', 'Elops 500E', 2023, 'disponible', 1, 2),
('VEL-003', 'Decathlon', 'Elops 500E', 2023, 'disponible', 1, 3),
('VEL-004', 'Decathlon', 'Elops 500E', 2023, 'disponible', 1, 4),
('VEL-005', 'Decathlon', 'Elops 500E', 2023, 'disponible', 1, 1),
('VEL-006', 'Decathlon', 'Elops 500E', 2023, 'disponible', 1, 2),
('VEL-007', 'Decathlon', 'Elops 500E', 2023, 'disponible', 1, 3),
('VEL-008', 'Decathlon', 'Elops 500E', 2023, 'disponible', 1, 4),
('VEL-009', 'Decathlon', 'Elops 500E', 2023, 'disponible', 1, 1),
('VEL-010', 'Decathlon', 'Elops 500E', 2023, 'disponible', 1, 2),

-- VTT électriques
('VTT-001', 'Specialized', 'Turbo Levo', 2023, 'disponible', 2, 1),
('VTT-002', 'Specialized', 'Turbo Levo', 2023, 'disponible', 2, 2),
('VTT-003', 'Specialized', 'Turbo Levo', 2023, 'disponible', 2, 1),
('VTT-004', 'Specialized', 'Turbo Levo', 2023, 'disponible', 2, 2),
('VTT-005', 'Specialized', 'Turbo Levo', 2023, 'disponible', 2, 1),
('VTT-006', 'Specialized', 'Turbo Levo', 2023, 'disponible', 2, 2),
('VTT-007', 'Specialized', 'Turbo Levo', 2023, 'disponible', 2, 1),
('VTT-008', 'Specialized', 'Turbo Levo', 2023, 'disponible', 2, 2),
('VTT-009', 'Specialized', 'Turbo Levo', 2023, 'disponible', 2, 1),
('VTT-010', 'Specialized', 'Turbo Levo', 2023, 'disponible', 2, 2),

-- Hoverboards
('HOV-001', 'Razor', 'Hovertrax 2.0', 2023, 'disponible', 3, 2),
('HOV-002', 'Razor', 'Hovertrax 2.0', 2023, 'disponible', 3, 4),
('HOV-003', 'Razor', 'Hovertrax 2.0', 2023, 'disponible', 3, 2),
('HOV-004', 'Razor', 'Hovertrax 2.0', 2023, 'disponible', 3, 4),
('HOV-005', 'Razor', 'Hovertrax 2.0', 2023, 'disponible', 3, 2),
('HOV-006', 'Razor', 'Hovertrax 2.0', 2023, 'disponible', 3, 4),
('HOV-007', 'Razor', 'Hovertrax 2.0', 2023, 'disponible', 3, 2),
('HOV-008', 'Razor', 'Hovertrax 2.0', 2023, 'disponible', 3, 4),
('HOV-009', 'Razor', 'Hovertrax 2.0', 2023, 'disponible', 3, 2),
('HOV-010', 'Razor', 'Hovertrax 2.0', 2023, 'disponible', 3, 4),

-- Trottinettes électriques
('TRO-001', 'Xiaomi', 'Mi Pro 2', 2023, 'disponible', 4, 1),
('TRO-002', 'Xiaomi', 'Mi Pro 2', 2023, 'disponible', 4, 2),
('TRO-003', 'Xiaomi', 'Mi Pro 2', 2023, 'disponible', 4, 1),
('TRO-004', 'Xiaomi', 'Mi Pro 2', 2023, 'disponible', 4, 2),
('TRO-005', 'Xiaomi', 'Mi Pro 2', 2023, 'disponible', 4, 1),
('TRO-006', 'Xiaomi', 'Mi Pro 2', 2023, 'disponible', 4, 2),
('TRO-007', 'Xiaomi', 'Mi Pro 2', 2023, 'disponible', 4, 1),
('TRO-008', 'Xiaomi', 'Mi Pro 2', 2023, 'disponible', 4, 2),
('TRO-009', 'Xiaomi', 'Mi Pro 2', 2023, 'disponible', 4, 1),
('TRO-010', 'Xiaomi', 'Mi Pro 2', 2023, 'disponible', 4, 2),

-- Gyropodes
('GYR-001', 'Segway', 'Ninebot S', 2023, 'disponible', 5, 1),
('GYR-002', 'Segway', 'Ninebot S', 2023, 'disponible', 5, 2),
('GYR-003', 'Segway', 'Ninebot S', 2023, 'disponible', 5, 3),
('GYR-004', 'Segway', 'Ninebot S', 2023, 'disponible', 5, 4),
('GYR-005', 'Segway', 'Ninebot S', 2023, 'disponible', 5, 1),
('GYR-006', 'Segway', 'Ninebot S', 2023, 'disponible', 5, 2),
('GYR-007', 'Segway', 'Ninebot S', 2023, 'disponible', 5, 3),
('GYR-008', 'Segway', 'Ninebot S', 2023, 'disponible', 5, 4),
('GYR-009', 'Segway', 'Ninebot S', 2023, 'disponible', 5, 1),
('GYR-010', 'Segway', 'Ninebot S', 2023, 'disponible', 5, 2),

-- Skateboards électriques
('SKT-001', 'Boosted', 'Stealth', 2023, 'disponible', 6, 1),
('SKT-002', 'Boosted', 'Stealth', 2023, 'disponible', 6, 3),
('SKT-003', 'Boosted', 'Stealth', 2023, 'disponible', 6, 1),
('SKT-004', 'Boosted', 'Stealth', 2023, 'disponible', 6, 3),
('SKT-005', 'Boosted', 'Stealth', 2023, 'disponible', 6, 1),
('SKT-006', 'Boosted', 'Stealth', 2023, 'disponible', 6, 3),
('SKT-007', 'Boosted', 'Stealth', 2023, 'disponible', 6, 1),
('SKT-008', 'Boosted', 'Stealth', 2023, 'disponible', 6, 3),
('SKT-009', 'Boosted', 'Stealth', 2023, 'disponible', 6, 1),
('SKT-010', 'Boosted', 'Stealth', 2023, 'disponible', 6, 3);

-- Clients
INSERT INTO Client_connecter (Nom, Prenom, Telephone, Adresse, Mail, Mot_de_Passe_Securiser, Date_de_Creation) VALUES
('Durand', 'Paul', '0601020304', '15 rue des Alpes, 74000 Annecy', 'paul.durand@mail.fr', 'hashMdp123', NOW()),
('Martin', 'Sophie', '0611223344', '20 avenue du Rhône, 38000 Grenoble', 'sophie.martin@mail.fr', 'hashMdp456', NOW());

-- Réservations (⚠️ Correction des colonnes et ordre)
INSERT INTO Reservation (Date_reservation, Duree, Demande_speciale, Date_Debut_location, Date_fin_location, Montant_Totale, Statut_reservation, id_Client, id_Vehicule, id_Tarif) VALUES
('2025-09-20 10:00:00', 2, 'Casque enfant demandé', '2025-09-25 09:00:00', '2025-09-25 11:00:00', 10.00, 'confirmée', 1, 1, 1),
('2025-09-21 15:00:00', 1, '', '2025-09-26 14:00:00', '2025-09-26 15:00:00', 8.00, 'confirmée', 2, 2, 2);
