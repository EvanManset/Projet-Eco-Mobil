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

INSERT INTO Vehicule (Num_serie, Marque, Modele, Annee, Statut, id_type_Vehicule, id_Agence) VALUES

-- ============================================================
-- TYPE 1 : Vélos urbains (Decathlon Elops 500E)
-- ============================================================
-- Agence 1 (Annecy)
('VEL-1-001', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 1),
('VEL-1-002', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 1),
('VEL-1-003', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 1),
('VEL-1-004', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 1),
('VEL-1-005', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 1),
('VEL-1-006', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 1),
('VEL-1-007', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 1),
('VEL-1-008', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 1),
('VEL-1-009', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 1),
('VEL-1-010', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 1),

-- Agence 2 (Grenoble)
('VEL-2-001', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 2),
('VEL-2-002', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 2),
('VEL-2-003', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 2),
('VEL-2-004', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 2),
('VEL-2-005', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 2),
('VEL-2-006', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 2),
('VEL-2-007', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 2),
('VEL-2-008', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 2),
('VEL-2-009', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 2),
('VEL-2-010', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 2),

-- Agence 3 (Chambéry)
('VEL-3-001', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 3),
('VEL-3-002', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 3),
('VEL-3-003', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 3),
('VEL-3-004', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 3),
('VEL-3-005', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 3),
('VEL-3-006', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 3),
('VEL-3-007', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 3),
('VEL-3-008', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 3),
('VEL-3-009', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 3),
('VEL-3-010', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 3),

-- Agence 4 (Valence)
('VEL-4-001', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 4),
('VEL-4-002', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 4),
('VEL-4-003', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 4),
('VEL-4-004', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 4),
('VEL-4-005', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 4),
('VEL-4-006', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 4),
('VEL-4-007', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 4),
('VEL-4-008', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 4),
('VEL-4-009', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 4),
('VEL-4-010', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 4),

-- Agence 5 (Meylan)
('VEL-5-001', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 5),
('VEL-5-002', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 5),
('VEL-5-003', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 5),
('VEL-5-004', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 5),
('VEL-5-005', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 5),
('VEL-5-006', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 5),
('VEL-5-007', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 5),
('VEL-5-008', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 5),
('VEL-5-009', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 5),
('VEL-5-010', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 5),

-- Agence 6 (Lyon)
('VEL-6-001', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 6),
('VEL-6-002', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 6),
('VEL-6-003', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 6),
('VEL-6-004', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 6),
('VEL-6-005', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 6),
('VEL-6-006', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 6),
('VEL-6-007', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 6),
('VEL-6-008', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 6),
('VEL-6-009', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 6),
('VEL-6-010', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 6),

-- Agence 7 (Bron)
('VEL-7-001', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 7),
('VEL-7-002', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 7),
('VEL-7-003', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 7),
('VEL-7-004', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 7),
('VEL-7-005', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 7),
('VEL-7-006', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 7),
('VEL-7-007', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 7),
('VEL-7-008', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 7),
('VEL-7-009', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 7),
('VEL-7-010', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 7),

-- Agence 8 (Saint-Etienne)
('VEL-8-001', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 8),
('VEL-8-002', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 8),
('VEL-8-003', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 8),
('VEL-8-004', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 8),
('VEL-8-005', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 8),
('VEL-8-006', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 8),
('VEL-8-007', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 8),
('VEL-8-008', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 8),
('VEL-8-009', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 8),
('VEL-8-010', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 8),

-- Agence 9 (Bourg-en-Bresse)
('VEL-9-001', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 9),
('VEL-9-002', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 9),
('VEL-9-003', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 9),
('VEL-9-004', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 9),
('VEL-9-005', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 9),
('VEL-9-006', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 9),
('VEL-9-007', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 9),
('VEL-9-008', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 9),
('VEL-9-009', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 9),
('VEL-9-010', 'Decathlon', 'Elops 500E', 2024, 'disponible', 1, 9),

-- ============================================================
-- TYPE 2 : VTT électriques (Specialized Turbo Levo)
-- ============================================================
-- Agence 1
('VTT-1-001', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 1),
('VTT-1-002', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 1),
('VTT-1-003', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 1),
('VTT-1-004', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 1),
('VTT-1-005', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 1),
('VTT-1-006', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 1),
('VTT-1-007', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 1),
('VTT-1-008', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 1),
('VTT-1-009', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 1),
('VTT-1-010', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 1),

-- Agence 2
('VTT-2-001', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 2),
('VTT-2-002', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 2),
('VTT-2-003', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 2),
('VTT-2-004', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 2),
('VTT-2-005', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 2),
('VTT-2-006', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 2),
('VTT-2-007', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 2),
('VTT-2-008', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 2),
('VTT-2-009', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 2),
('VTT-2-010', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 2),

-- Agence 3
('VTT-3-001', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 3),
('VTT-3-002', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 3),
('VTT-3-003', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 3),
('VTT-3-004', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 3),
('VTT-3-005', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 3),
('VTT-3-006', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 3),
('VTT-3-007', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 3),
('VTT-3-008', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 3),
('VTT-3-009', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 3),
('VTT-3-010', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 3),

-- Agence 4
('VTT-4-001', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 4),
('VTT-4-002', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 4),
('VTT-4-003', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 4),
('VTT-4-004', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 4),
('VTT-4-005', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 4),
('VTT-4-006', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 4),
('VTT-4-007', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 4),
('VTT-4-008', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 4),
('VTT-4-009', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 4),
('VTT-4-010', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 4),

-- Agence 5
('VTT-5-001', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 5),
('VTT-5-002', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 5),
('VTT-5-003', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 5),
('VTT-5-004', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 5),
('VTT-5-005', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 5),
('VTT-5-006', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 5),
('VTT-5-007', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 5),
('VTT-5-008', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 5),
('VTT-5-009', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 5),
('VTT-5-010', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 5),

-- Agence 6
('VTT-6-001', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 6),
('VTT-6-002', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 6),
('VTT-6-003', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 6),
('VTT-6-004', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 6),
('VTT-6-005', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 6),
('VTT-6-006', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 6),
('VTT-6-007', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 6),
('VTT-6-008', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 6),
('VTT-6-009', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 6),
('VTT-6-010', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 6),

-- Agence 7
('VTT-7-001', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 7),
('VTT-7-002', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 7),
('VTT-7-003', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 7),
('VTT-7-004', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 7),
('VTT-7-005', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 7),
('VTT-7-006', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 7),
('VTT-7-007', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 7),
('VTT-7-008', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 7),
('VTT-7-009', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 7),
('VTT-7-010', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 7),

-- Agence 8
('VTT-8-001', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 8),
('VTT-8-002', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 8),
('VTT-8-003', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 8),
('VTT-8-004', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 8),
('VTT-8-005', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 8),
('VTT-8-006', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 8),
('VTT-8-007', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 8),
('VTT-8-008', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 8),
('VTT-8-009', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 8),
('VTT-8-010', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 8),

-- Agence 9
('VTT-9-001', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 9),
('VTT-9-002', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 9),
('VTT-9-003', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 9),
('VTT-9-004', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 9),
('VTT-9-005', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 9),
('VTT-9-006', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 9),
('VTT-9-007', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 9),
('VTT-9-008', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 9),
('VTT-9-009', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 9),
('VTT-9-010', 'Specialized', 'Turbo Levo', 2024, 'disponible', 2, 9),

-- ============================================================
-- TYPE 3 : Hoverboards (Razor Hovertrax 2.0)
-- ============================================================
-- Agence 1
('HOV-1-001', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 1),
('HOV-1-002', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 1),
('HOV-1-003', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 1),
('HOV-1-004', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 1),
('HOV-1-005', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 1),
('HOV-1-006', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 1),
('HOV-1-007', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 1),
('HOV-1-008', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 1),
('HOV-1-009', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 1),
('HOV-1-010', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 1),

-- Agence 2
('HOV-2-001', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 2),
('HOV-2-002', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 2),
('HOV-2-003', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 2),
('HOV-2-004', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 2),
('HOV-2-005', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 2),
('HOV-2-006', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 2),
('HOV-2-007', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 2),
('HOV-2-008', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 2),
('HOV-2-009', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 2),
('HOV-2-010', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 2),

-- Agence 3
('HOV-3-001', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 3),
('HOV-3-002', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 3),
('HOV-3-003', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 3),
('HOV-3-004', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 3),
('HOV-3-005', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 3),
('HOV-3-006', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 3),
('HOV-3-007', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 3),
('HOV-3-008', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 3),
('HOV-3-009', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 3),
('HOV-3-010', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 3),

-- Agence 4
('HOV-4-001', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 4),
('HOV-4-002', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 4),
('HOV-4-003', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 4),
('HOV-4-004', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 4),
('HOV-4-005', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 4),
('HOV-4-006', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 4),
('HOV-4-007', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 4),
('HOV-4-008', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 4),
('HOV-4-009', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 4),
('HOV-4-010', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 4),

-- Agence 5
('HOV-5-001', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 5),
('HOV-5-002', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 5),
('HOV-5-003', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 5),
('HOV-5-004', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 5),
('HOV-5-005', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 5),
('HOV-5-006', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 5),
('HOV-5-007', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 5),
('HOV-5-008', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 5),
('HOV-5-009', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 5),
('HOV-5-010', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 5),

-- Agence 6
('HOV-6-001', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 6),
('HOV-6-002', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 6),
('HOV-6-003', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 6),
('HOV-6-004', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 6),
('HOV-6-005', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 6),
('HOV-6-006', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 6),
('HOV-6-007', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 6),
('HOV-6-008', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 6),
('HOV-6-009', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 6),
('HOV-6-010', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 6),

-- Agence 7
('HOV-7-001', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 7),
('HOV-7-002', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 7),
('HOV-7-003', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 7),
('HOV-7-004', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 7),
('HOV-7-005', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 7),
('HOV-7-006', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 7),
('HOV-7-007', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 7),
('HOV-7-008', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 7),
('HOV-7-009', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 7),
('HOV-7-010', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 7),

-- Agence 8
('HOV-8-001', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 8),
('HOV-8-002', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 8),
('HOV-8-003', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 8),
('HOV-8-004', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 8),
('HOV-8-005', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 8),
('HOV-8-006', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 8),
('HOV-8-007', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 8),
('HOV-8-008', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 8),
('HOV-8-009', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 8),
('HOV-8-010', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 8),

-- Agence 9
('HOV-9-001', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 9),
('HOV-9-002', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 9),
('HOV-9-003', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 9),
('HOV-9-004', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 9),
('HOV-9-005', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 9),
('HOV-9-006', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 9),
('HOV-9-007', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 9),
('HOV-9-008', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 9),
('HOV-9-009', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 9),
('HOV-9-010', 'Razor', 'Hovertrax 2.0', 2024, 'disponible', 3, 9),

-- ============================================================
-- TYPE 4 : Trottinettes électriques (Xiaomi Mi Pro 2)
-- ============================================================
-- Agence 1
('TRO-1-001', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 1),
('TRO-1-002', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 1),
('TRO-1-003', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 1),
('TRO-1-004', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 1),
('TRO-1-005', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 1),
('TRO-1-006', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 1),
('TRO-1-007', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 1),
('TRO-1-008', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 1),
('TRO-1-009', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 1),
('TRO-1-010', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 1),

-- Agence 2
('TRO-2-001', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 2),
('TRO-2-002', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 2),
('TRO-2-003', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 2),
('TRO-2-004', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 2),
('TRO-2-005', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 2),
('TRO-2-006', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 2),
('TRO-2-007', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 2),
('TRO-2-008', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 2),
('TRO-2-009', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 2),
('TRO-2-010', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 2),

-- Agence 3
('TRO-3-001', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 3),
('TRO-3-002', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 3),
('TRO-3-003', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 3),
('TRO-3-004', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 3),
('TRO-3-005', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 3),
('TRO-3-006', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 3),
('TRO-3-007', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 3),
('TRO-3-008', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 3),
('TRO-3-009', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 3),
('TRO-3-010', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 3),

-- Agence 4
('TRO-4-001', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 4),
('TRO-4-002', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 4),
('TRO-4-003', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 4),
('TRO-4-004', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 4),
('TRO-4-005', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 4),
('TRO-4-006', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 4),
('TRO-4-007', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 4),
('TRO-4-008', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 4),
('TRO-4-009', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 4),
('TRO-4-010', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 4),

-- Agence 5
('TRO-5-001', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 5),
('TRO-5-002', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 5),
('TRO-5-003', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 5),
('TRO-5-004', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 5),
('TRO-5-005', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 5),
('TRO-5-006', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 5),
('TRO-5-007', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 5),
('TRO-5-008', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 5),
('TRO-5-009', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 5),
('TRO-5-010', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 5),

-- Agence 6
('TRO-6-001', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 6),
('TRO-6-002', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 6),
('TRO-6-003', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 6),
('TRO-6-004', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 6),
('TRO-6-005', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 6),
('TRO-6-006', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 6),
('TRO-6-007', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 6),
('TRO-6-008', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 6),
('TRO-6-009', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 6),
('TRO-6-010', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 6),

-- Agence 7
('TRO-7-001', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 7),
('TRO-7-002', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 7),
('TRO-7-003', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 7),
('TRO-7-004', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 7),
('TRO-7-005', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 7),
('TRO-7-006', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 7),
('TRO-7-007', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 7),
('TRO-7-008', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 7),
('TRO-7-009', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 7),
('TRO-7-010', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 7),

-- Agence 8
('TRO-8-001', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 8),
('TRO-8-002', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 8),
('TRO-8-003', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 8),
('TRO-8-004', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 8),
('TRO-8-005', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 8),
('TRO-8-006', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 8),
('TRO-8-007', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 8),
('TRO-8-008', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 8),
('TRO-8-009', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 8),
('TRO-8-010', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 8),

-- Agence 9
('TRO-9-001', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 9),
('TRO-9-002', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 9),
('TRO-9-003', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 9),
('TRO-9-004', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 9),
('TRO-9-005', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 9),
('TRO-9-006', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 9),
('TRO-9-007', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 9),
('TRO-9-008', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 9),
('TRO-9-009', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 9),
('TRO-9-010', 'Xiaomi', 'Mi Pro 2', 2024, 'disponible', 4, 9),

-- ============================================================
-- TYPE 5 : Gyropodes (Segway Ninebot S)
-- ============================================================
-- Agence 1
('GYR-1-001', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 1),
('GYR-1-002', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 1),
('GYR-1-003', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 1),
('GYR-1-004', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 1),
('GYR-1-005', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 1),
('GYR-1-006', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 1),
('GYR-1-007', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 1),
('GYR-1-008', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 1),
('GYR-1-009', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 1),
('GYR-1-010', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 1),

-- Agence 2
('GYR-2-001', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 2),
('GYR-2-002', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 2),
('GYR-2-003', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 2),
('GYR-2-004', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 2),
('GYR-2-005', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 2),
('GYR-2-006', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 2),
('GYR-2-007', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 2),
('GYR-2-008', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 2),
('GYR-2-009', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 2),
('GYR-2-010', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 2),

-- Agence 3
('GYR-3-001', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 3),
('GYR-3-002', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 3),
('GYR-3-003', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 3),
('GYR-3-004', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 3),
('GYR-3-005', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 3),
('GYR-3-006', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 3),
('GYR-3-007', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 3),
('GYR-3-008', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 3),
('GYR-3-009', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 3),
('GYR-3-010', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 3),

-- Agence 4
('GYR-4-001', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 4),
('GYR-4-002', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 4),
('GYR-4-003', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 4),
('GYR-4-004', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 4),
('GYR-4-005', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 4),
('GYR-4-006', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 4),
('GYR-4-007', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 4),
('GYR-4-008', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 4),
('GYR-4-009', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 4),
('GYR-4-010', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 4),

-- Agence 5
('GYR-5-001', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 5),
('GYR-5-002', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 5),
('GYR-5-003', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 5),
('GYR-5-004', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 5),
('GYR-5-005', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 5),
('GYR-5-006', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 5),
('GYR-5-007', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 5),
('GYR-5-008', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 5),
('GYR-5-009', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 5),
('GYR-5-010', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 5),

-- Agence 6
('GYR-6-001', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 6),
('GYR-6-002', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 6),
('GYR-6-003', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 6),
('GYR-6-004', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 6),
('GYR-6-005', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 6),
('GYR-6-006', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 6),
('GYR-6-007', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 6),
('GYR-6-008', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 6),
('GYR-6-009', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 6),
('GYR-6-010', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 6),

-- Agence 7
('GYR-7-001', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 7),
('GYR-7-002', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 7),
('GYR-7-003', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 7),
('GYR-7-004', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 7),
('GYR-7-005', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 7),
('GYR-7-006', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 7),
('GYR-7-007', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 7),
('GYR-7-008', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 7),
('GYR-7-009', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 7),
('GYR-7-010', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 7),

-- Agence 8
('GYR-8-001', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 8),
('GYR-8-002', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 8),
('GYR-8-003', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 8),
('GYR-8-004', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 8),
('GYR-8-005', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 8),
('GYR-8-006', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 8),
('GYR-8-007', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 8),
('GYR-8-008', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 8),
('GYR-8-009', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 8),
('GYR-8-010', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 8),

-- Agence 9
('GYR-9-001', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 9),
('GYR-9-002', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 9),
('GYR-9-003', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 9),
('GYR-9-004', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 9),
('GYR-9-005', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 9),
('GYR-9-006', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 9),
('GYR-9-007', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 9),
('GYR-9-008', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 9),
('GYR-9-009', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 9),
('GYR-9-010', 'Segway', 'Ninebot S', 2024, 'disponible', 5, 9),

-- ============================================================
-- TYPE 6 : Skateboards électriques (Boosted Stealth)
-- ============================================================
-- Agence 1
('SKT-1-001', 'Boosted', 'Stealth', 2024, 'disponible', 6, 1),
('SKT-1-002', 'Boosted', 'Stealth', 2024, 'disponible', 6, 1),
('SKT-1-003', 'Boosted', 'Stealth', 2024, 'disponible', 6, 1),
('SKT-1-004', 'Boosted', 'Stealth', 2024, 'disponible', 6, 1),
('SKT-1-005', 'Boosted', 'Stealth', 2024, 'disponible', 6, 1),
('SKT-1-006', 'Boosted', 'Stealth', 2024, 'disponible', 6, 1),
('SKT-1-007', 'Boosted', 'Stealth', 2024, 'disponible', 6, 1),
('SKT-1-008', 'Boosted', 'Stealth', 2024, 'disponible', 6, 1),
('SKT-1-009', 'Boosted', 'Stealth', 2024, 'disponible', 6, 1),
('SKT-1-010', 'Boosted', 'Stealth', 2024, 'disponible', 6, 1),

-- Agence 2
('SKT-2-001', 'Boosted', 'Stealth', 2024, 'disponible', 6, 2),
('SKT-2-002', 'Boosted', 'Stealth', 2024, 'disponible', 6, 2),
('SKT-2-003', 'Boosted', 'Stealth', 2024, 'disponible', 6, 2),
('SKT-2-004', 'Boosted', 'Stealth', 2024, 'disponible', 6, 2),
('SKT-2-005', 'Boosted', 'Stealth', 2024, 'disponible', 6, 2),
('SKT-2-006', 'Boosted', 'Stealth', 2024, 'disponible', 6, 2),
('SKT-2-007', 'Boosted', 'Stealth', 2024, 'disponible', 6, 2),
('SKT-2-008', 'Boosted', 'Stealth', 2024, 'disponible', 6, 2),
('SKT-2-009', 'Boosted', 'Stealth', 2024, 'disponible', 6, 2),
('SKT-2-010', 'Boosted', 'Stealth', 2024, 'disponible', 6, 2),

-- Agence 3
('SKT-3-001', 'Boosted', 'Stealth', 2024, 'disponible', 6, 3),
('SKT-3-002', 'Boosted', 'Stealth', 2024, 'disponible', 6, 3),
('SKT-3-003', 'Boosted', 'Stealth', 2024, 'disponible', 6, 3),
('SKT-3-004', 'Boosted', 'Stealth', 2024, 'disponible', 6, 3),
('SKT-3-005', 'Boosted', 'Stealth', 2024, 'disponible', 6, 3),
('SKT-3-006', 'Boosted', 'Stealth', 2024, 'disponible', 6, 3),
('SKT-3-007', 'Boosted', 'Stealth', 2024, 'disponible', 6, 3),
('SKT-3-008', 'Boosted', 'Stealth', 2024, 'disponible', 6, 3),
('SKT-3-009', 'Boosted', 'Stealth', 2024, 'disponible', 6, 3),
('SKT-3-010', 'Boosted', 'Stealth', 2024, 'disponible', 6, 3),

-- Agence 4
('SKT-4-001', 'Boosted', 'Stealth', 2024, 'disponible', 6, 4),
('SKT-4-002', 'Boosted', 'Stealth', 2024, 'disponible', 6, 4),
('SKT-4-003', 'Boosted', 'Stealth', 2024, 'disponible', 6, 4),
('SKT-4-004', 'Boosted', 'Stealth', 2024, 'disponible', 6, 4),
('SKT-4-005', 'Boosted', 'Stealth', 2024, 'disponible', 6, 4),
('SKT-4-006', 'Boosted', 'Stealth', 2024, 'disponible', 6, 4),
('SKT-4-007', 'Boosted', 'Stealth', 2024, 'disponible', 6, 4),
('SKT-4-008', 'Boosted', 'Stealth', 2024, 'disponible', 6, 4),
('SKT-4-009', 'Boosted', 'Stealth', 2024, 'disponible', 6, 4),
('SKT-4-010', 'Boosted', 'Stealth', 2024, 'disponible', 6, 4),

-- Agence 5
('SKT-5-001', 'Boosted', 'Stealth', 2024, 'disponible', 6, 5),
('SKT-5-002', 'Boosted', 'Stealth', 2024, 'disponible', 6, 5),
('SKT-5-003', 'Boosted', 'Stealth', 2024, 'disponible', 6, 5),
('SKT-5-004', 'Boosted', 'Stealth', 2024, 'disponible', 6, 5),
('SKT-5-005', 'Boosted', 'Stealth', 2024, 'disponible', 6, 5),
('SKT-5-006', 'Boosted', 'Stealth', 2024, 'disponible', 6, 5),
('SKT-5-007', 'Boosted', 'Stealth', 2024, 'disponible', 6, 5),
('SKT-5-008', 'Boosted', 'Stealth', 2024, 'disponible', 6, 5),
('SKT-5-009', 'Boosted', 'Stealth', 2024, 'disponible', 6, 5),
('SKT-5-010', 'Boosted', 'Stealth', 2024, 'disponible', 6, 5),

-- Agence 6
('SKT-6-001', 'Boosted', 'Stealth', 2024, 'disponible', 6, 6),
('SKT-6-002', 'Boosted', 'Stealth', 2024, 'disponible', 6, 6),
('SKT-6-003', 'Boosted', 'Stealth', 2024, 'disponible', 6, 6),
('SKT-6-004', 'Boosted', 'Stealth', 2024, 'disponible', 6, 6),
('SKT-6-005', 'Boosted', 'Stealth', 2024, 'disponible', 6, 6),
('SKT-6-006', 'Boosted', 'Stealth', 2024, 'disponible', 6, 6),
('SKT-6-007', 'Boosted', 'Stealth', 2024, 'disponible', 6, 6),
('SKT-6-008', 'Boosted', 'Stealth', 2024, 'disponible', 6, 6),
('SKT-6-009', 'Boosted', 'Stealth', 2024, 'disponible', 6, 6),
('SKT-6-010', 'Boosted', 'Stealth', 2024, 'disponible', 6, 6),

-- Agence 7
('SKT-7-001', 'Boosted', 'Stealth', 2024, 'disponible', 6, 7),
('SKT-7-002', 'Boosted', 'Stealth', 2024, 'disponible', 6, 7),
('SKT-7-003', 'Boosted', 'Stealth', 2024, 'disponible', 6, 7),
('SKT-7-004', 'Boosted', 'Stealth', 2024, 'disponible', 6, 7),
('SKT-7-005', 'Boosted', 'Stealth', 2024, 'disponible', 6, 7),
('SKT-7-006', 'Boosted', 'Stealth', 2024, 'disponible', 6, 7),
('SKT-7-007', 'Boosted', 'Stealth', 2024, 'disponible', 6, 7),
('SKT-7-008', 'Boosted', 'Stealth', 2024, 'disponible', 6, 7),
('SKT-7-009', 'Boosted', 'Stealth', 2024, 'disponible', 6, 7),
('SKT-7-010', 'Boosted', 'Stealth', 2024, 'disponible', 6, 7),

-- Agence 8
('SKT-8-001', 'Boosted', 'Stealth', 2024, 'disponible', 6, 8),
('SKT-8-002', 'Boosted', 'Stealth', 2024, 'disponible', 6, 8),
('SKT-8-003', 'Boosted', 'Stealth', 2024, 'disponible', 6, 8),
('SKT-8-004', 'Boosted', 'Stealth', 2024, 'disponible', 6, 8),
('SKT-8-005', 'Boosted', 'Stealth', 2024, 'disponible', 6, 8),
('SKT-8-006', 'Boosted', 'Stealth', 2024, 'disponible', 6, 8),
('SKT-8-007', 'Boosted', 'Stealth', 2024, 'disponible', 6, 8),
('SKT-8-008', 'Boosted', 'Stealth', 2024, 'disponible', 6, 8),
('SKT-8-009', 'Boosted', 'Stealth', 2024, 'disponible', 6, 8),
('SKT-8-010', 'Boosted', 'Stealth', 2024, 'disponible', 6, 8),

-- Agence 9
('SKT-9-001', 'Boosted', 'Stealth', 2024, 'disponible', 6, 9),
('SKT-9-002', 'Boosted', 'Stealth', 2024, 'disponible', 6, 9),
('SKT-9-003', 'Boosted', 'Stealth', 2024, 'disponible', 6, 9),
('SKT-9-004', 'Boosted', 'Stealth', 2024, 'disponible', 6, 9),
('SKT-9-005', 'Boosted', 'Stealth', 2024, 'disponible', 6, 9),
('SKT-9-006', 'Boosted', 'Stealth', 2024, 'disponible', 6, 9),
('SKT-9-007', 'Boosted', 'Stealth', 2024, 'disponible', 6, 9),
('SKT-9-008', 'Boosted', 'Stealth', 2024, 'disponible', 6, 9),
('SKT-9-009', 'Boosted', 'Stealth', 2024, 'disponible', 6, 9),
('SKT-9-010', 'Boosted', 'Stealth', 2024, 'disponible', 6, 9);