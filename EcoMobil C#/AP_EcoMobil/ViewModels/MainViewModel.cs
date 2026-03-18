using System;
using System.Collections.ObjectModel;
using System.ComponentModel;
using System.Linq;
using System.Runtime.CompilerServices;
using System.Collections.Generic;
using AP_EcoMobil.Models;
using MySql.Data.MySqlClient;

namespace AP_EcoMobil.ViewModels
{
    public class MainViewModel : INotifyPropertyChanged
    {
        // ======================================================================
        // 1. CONFIGURATION ET CONNEXION
        // ======================================================================

        // Définit la chaîne de connexion pour accéder à la base MySQL locale 'ecomobil'.
        private string connectionString = "server=localhost;user=root;database=ecomobil;port=3306;password=''";

        // ======================================================================
        // 2. VARIABLES ET PROPRIÉTÉS LIÉES À L'INTERFACE
        // ======================================================================

        // Stockage interne du Chiffre d'Affaires total.
        private double _chiffreAffairesTotal;
        public double ChiffreAffairesTotal
        {
            get { return _chiffreAffairesTotal; }
            // Met à jour la valeur et notifie l'interface graphique du changement.
            set { _chiffreAffairesTotal = value; OnPropertyChanged(); }
        }

        // Stockage interne du nombre total de dossiers de location.
        private int _nombreLocations;
        public int NombreLocations
        {
            get { return _nombreLocations; }
            // Notifie l'interface pour rafraîchir le compteur de locations.
            set { _nombreLocations = value; OnPropertyChanged(); }
        }

        // Collections d'objets pour remplir automatiquement les tableaux (DataGrids) de la vue.
        public ObservableCollection<ReservationDetail> Reservations { get; set; }
        public ObservableCollection<StatistiqueVehicule> StatsVehicules { get; set; }
        public ObservableCollection<StatistiqueDuree> StatsDurees { get; set; }

        public MainViewModel()
        {
            // Initialisation des listes à l'instanciation du ViewModel.
            Reservations = new ObservableCollection<ReservationDetail>();
            StatsVehicules = new ObservableCollection<StatistiqueVehicule>();
            StatsDurees = new ObservableCollection<StatistiqueDuree>();
        }

        // ======================================================================
        // 3. LOGIQUE MÉTIER : RÉCUPÉRATION ET TRAITEMENT DES DONNÉES
        // ======================================================================

        public void ChargerDonnees(DateTime? dateDebut, DateTime? dateFin, string agence)
        {
            // Vide les listes actuelles pour garantir la fraîcheur des nouvelles données.
            Reservations.Clear();
            StatsVehicules.Clear();
            StatsDurees.Clear();
            double totalCA = 0;
            int totalLoc = 0;

            // Établit la connexion avec le serveur MySQL.
            using (MySqlConnection conn = new MySqlConnection(connectionString))
            {
                conn.Open();

                // Requête SQL complexe utilisant GROUP_CONCAT pour fusionner les participants
                // et LEFT JOIN pour inclure les données même si elles sont incomplètes.
                string query = @"
            SELECT 
                r.id_Reservation, 
                MAX(IFNULL(tv.libelle_Type, 'Inconnu')) as libelle_Type, 
                r.Duree, 
                MAX(IFNULL(r.montant_totale, 0)) as montant_totale, 
                r.date_debut_location, 
                MAX(IFNULL(a.nom_Agence, 'Non affectée')) as nom_Agence, 
                GROUP_CONCAT(CONCAT(p.Prenom, ' ', p.Nom) SEPARATOR ', ') as ListeParticipants
            FROM Reservation r
            LEFT JOIN Participants p ON r.id_Reservation = p.id_reservation
            LEFT JOIN Vehicule v ON p.id_vehicule = v.id_Vehicule
            LEFT JOIN Type_Vehicule tv ON v.id_type_vehicule = tv.id_Type_Vehicule
            LEFT JOIN Agence_location a ON v.id_Agence = a.id_Agence
            WHERE 1=1";

                // Ajout dynamique des filtres de date et d'agence selon la saisie utilisateur.
                if (dateDebut.HasValue) query += " AND r.date_debut_location >= @debut";
                if (dateFin.HasValue) query += " AND r.date_debut_location <= @fin";
                if (!string.IsNullOrEmpty(agence) && agence != "Consolidée (Groupe)") query += " AND a.nom_Agence = @agence";

                // Regroupe les résultats par ID de réservation pour éviter les lignes doublons.
                query += " GROUP BY r.id_Reservation";

                using (MySqlCommand cmd = new MySqlCommand(query, conn))
                {
                    // Injection sécurisée des paramètres pour éviter les attaques par injection SQL.
                    if (dateDebut.HasValue) cmd.Parameters.AddWithValue("@debut", dateDebut.Value);
                    if (dateFin.HasValue) cmd.Parameters.AddWithValue("@fin", dateFin.Value);
                    if (!string.IsNullOrEmpty(agence) && agence != "Consolidée (Groupe)") cmd.Parameters.AddWithValue("@agence", agence);

                    using (MySqlDataReader reader = cmd.ExecuteReader())
                    {
                        // Parcourt chaque ligne renvoyée par la base de données.
                        while (reader.Read())
                        {
                            double montant = Convert.ToDouble(reader["montant_totale"]);
                            // Crée un objet de détail et l'ajoute à la collection liée à l'interface.
                            Reservations.Add(new ReservationDetail
                            {
                                Id = Convert.ToInt32(reader["id_Reservation"]),
                                TypeVehicule = reader["libelle_Type"].ToString(),
                                DureeEnHeures = Convert.ToInt32(reader["Duree"]),
                                Montant = montant,
                                DateLocation = Convert.ToDateTime(reader["date_debut_location"]),
                                Agence = reader["nom_Agence"].ToString(),
                                Participants = reader["ListeParticipants"].ToString()
                            });
                            // Cumule le montant pour le calcul du Chiffre d'Affaires global.
                            totalCA += montant;
                            totalLoc++;
                        }
                    }
                }
            }

            // Met à jour les indicateurs globaux de l'interface.
            ChiffreAffairesTotal = totalCA;
            NombreLocations = totalLoc;

            if (totalLoc > 0)
            {
                // Regroupe les réservations par type de véhicule pour générer les statistiques.
                var groupesVehicule = Reservations.GroupBy(r => r.TypeVehicule);
                foreach (var g in groupesVehicule)
                {
                    double caGroupe = g.Sum(r => r.Montant);
                    StatsVehicules.Add(new StatistiqueVehicule
                    {
                        TypeVehicule = g.Key,
                        NombreLocations = g.Count(),
                        ChiffreAffaires = caGroupe,
                        // Calcule la part en pourcentage du type de véhicule dans le CA total.
                        PourcentageCA = Math.Round((caGroupe / totalCA) * 100, 2)
                    });
                }
                // Lance le calcul de la répartition par durée de location.
                CalculerStatsDuree(Reservations.ToList());
            }
        }

        public void CalculerStatsDuree(List<ReservationDetail> liste)
        {
            // Réinitialise les statistiques de durée.
            StatsDurees.Clear();
            int total = liste.Count;
            if (total == 0) return;

            // Transforme les durées brutes en catégories métier (Heure, Demi-journée, Journée).
            var groupesFusionnés = liste
                .Select(r => new {
                    NomCategorie = r.DureeEnHeures == 4 ? "1/2 Journée (4h)" :
                                   r.DureeEnHeures >= 8 ? "Journée ou +" :
                                   r.DureeEnHeures + " heure(s)"
                })
                // Regroupe les réservations par le libellé de leur catégorie.
                .GroupBy(x => x.NomCategorie)
                .Select(g => new StatistiqueDuree
                {
                    Duree = g.Key,
                    NombreLocations = g.Count(),
                    // Calcule le pourcentage de chaque tranche de durée.
                    Pourcentage = Math.Round(((double)g.Count() / total) * 100, 2)
                });

            // Remplit la collection pour l'affichage dans le graphique/tableau.
            foreach (var stat in groupesFusionnés)
            {
                StatsDurees.Add(stat);
            }
        }

        public void FiltrerDetailDureeParType(string typeChoisi)
        {
            // Filtre la liste globale pour n'extraire que les réservations du type sélectionné.
            var filtre = Reservations.Where(r => r.TypeVehicule == typeChoisi).ToList();
            // Recalcule les statistiques de durée uniquement pour ce type de véhicule.
            CalculerStatsDuree(filtre);
        }

        // ======================================================================
        // 4. SYSTÈME DE NOTIFICATION UI
        // ======================================================================

        // Événement permettant de signaler à la Vue WPF qu'une valeur a été modifiée.
        public event PropertyChangedEventHandler PropertyChanged;
        protected void OnPropertyChanged([CallerMemberName] string name = null)
        {
            // Déclenche la mise à jour automatique des éléments graphiques liés (Bindings).
            PropertyChanged?.Invoke(this, new PropertyChangedEventArgs(name));
        }
    }
}