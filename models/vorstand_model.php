<?php

class Vorstand_Model extends Model {
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * Gibt einzelnen Vorstand zur�ck.
     * @param string $email e-mail des Vorstands
     * @return array Liste des Mitglieds
     */
    public function getVorstand($email) {
        return $this->_db->select('SELECT * FROM mitglied WHERE email = :email AND rang = :rang', array('email' =>  $email, 'rang' =>  'vorstand'));
    }
    
    /**
     * Gibt alle Vorstaende zur�ck.
     * @return array Liste der Vorstaende
     */
    public function getAllVorstaende() {
        return $this->_db->select('SELECT * FROM mitglied WHERE rang = :rang', array('rang' =>  'vorstand'));
    }

    /**
     * Gibt f�r alle Mitglieder alle Daten + komplette Adresse zur�ck
     * @return array Liste mit ID in Form von array[0][daten],
     * Inner Join �ber Tabelle mitglied, adresse und postleitzahl
     */
    public function joinMitglieder() {
        return $this->_db->select('SELECT mitglied.*, adresse.strasse, adresse.hausnummer, adresse.postleitzahl, postleitzahl.ort FROM mitglied JOIN adresse USING (adresse_id) JOIN postleitzahl USING (postleitzahl)',
            array('email' => $data['email']));
    }
    
    /**
     * Gibt alle Sportarten zur�ck.
     * @return array Liste der Vorstaende
     */
    public function getSportarten() {
        return $this->_db->select('SELECT * FROM sportarten ORDER BY sportart ASC');
    }
    
    /**
     * Setzt Sportart.
     * @return array Liste der Vorstaende
     */
    public function setSportart($sportart) {
        return  $this->_db->insert('sportarten', array('sportart' => $sportart));
    }
    
    /**
     * Setzt Sportart.
     * @return array Liste der Vorstaende
     */
    public function delSportart($sportart) {
        return  $this->_db->delete('sportarten', array('sportart' => $sportart));
    }
}

?>

