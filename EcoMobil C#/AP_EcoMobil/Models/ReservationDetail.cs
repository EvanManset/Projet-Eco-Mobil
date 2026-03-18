using System;

namespace AP_EcoMobil.Models
{
    public class ReservationDetail
    {
        public int Id { get; set; }
        public string TypeVehicule { get; set; }
        public int DureeEnHeures { get; set; }
        public double Montant { get; set; }
        public DateTime DateLocation { get; set; }
        public string Agence { get; set; }
        public string Participants { get; set; }
    }
}