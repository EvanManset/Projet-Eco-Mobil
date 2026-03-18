using System;
using System.Windows;
using System.Windows.Controls;
using AP_EcoMobil.ViewModels;
using AP_EcoMobil.Models;

namespace AP_EcoMobil
{
    public partial class MainWindow : Window
    {
        // ======================================================================
        // 1. INITIALISATION DE LA FENÊTRE
        // ======================================================================

        // Déclare le ViewModel qui fera le lien entre la base de données et l'interface.
        private MainViewModel _viewModel;

        public MainWindow()
        {
            // Initialise tous les composants visuels définis dans le fichier XAML.
            InitializeComponent();

            // Instancie le ViewModel pour gérer la logique de calcul et les données.
            _viewModel = new MainViewModel();

            // Définit le contexte de données de la fenêtre pour permettre le Binding (liaison de données).
            this.DataContext = _viewModel;
        }

        // ======================================================================
        // 2. GESTION DES ÉVÉNEMENTS (LOGIQUE INTERFACE)
        // ======================================================================

        private void BtnActualiser_Click(object sender, RoutedEventArgs e)
        {
            // Définit la logique de mise à jour des données lors du clic sur le bouton "Actualiser".
            try
            {
                // Récupère les dates sélectionnées dans les calendriers (peut être null).
                DateTime? debut = DpDebut.SelectedDate;
                DateTime? fin = DpFin.SelectedDate;

                // Récupère le nom de l'agence sélectionnée dans la liste déroulante (ComboBox).
                string agence = (CmbAgences.SelectedItem as ComboBoxItem).Content.ToString();

                // Appelle la méthode du ViewModel pour charger les données filtrées depuis la base MySQL.
                _viewModel.ChargerDonnees(debut, fin, agence);
            }
            catch (Exception ex)
            {
                // Affiche un message d'alerte en cas d'erreur technique (connexion SQL, etc.).
                MessageBox.Show("Erreur lors de l'actualisation : " + ex.Message);
            }
        }

        private void DataGrid_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            // Définit l'interactivité lorsqu'on clique sur une ligne du tableau financier.

            // Vérifie si l'élément sélectionné est bien une statistique de véhicule.
            if (sender is DataGrid dg && dg.SelectedItem is StatistiqueVehicule stat)
            {
                // Demande au ViewModel de filtrer le détail des durées pour le type de véhicule choisi.
                _viewModel.FiltrerDetailDureeParType(stat.TypeVehicule);

                // Force l'interface à basculer sur le premier onglet (Quantitatif) pour afficher le résultat.
                MainTabs.SelectedIndex = 0;
            }
        }
    }
}