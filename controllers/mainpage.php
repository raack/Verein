<?php

class Mainpage extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Rendert Indexseite.
     */
    public function index()
    {
        Session::set('csrf_token', uniqid('', true));
        $data['title'] = '&Uuml;bersicht';       
        $this->_view->render('header', $data);
        $this->_view->render('public/login', $data);
        $data['sportarten'] = $this->_common->getSportarten();
        $this->_view->render('public/navigation', $data);
        $data['vorstand'] = $this->_common->getVorstand();
        $this->_view->render('public/content', $data);
        $this->_view->render('footer');
    }
    
    /**
     * Rendert Angebotsseite.
     */
    public function angebot()
    {
        Session::set('csrf_token', uniqid('', true));
        $data['title'] = '&Uuml;bersicht';
        $this->_view->render('header', $data);
        $this->_view->render('public/login', $data);
        $data['sportarten'] = $this->_common->getSportarten();
        $this->_view->render('public/navigation', $data);
        $data['vorstand'] = $this->_common->getVorstand();
        $data['kurse'] = $this->_common->getKurse();
        $kursleiter = $this->_model->getKursleiter($data['kurse'][0]['mitglied_id']);
        $data['kurse'][0]['kursleiter'] = $kursleiter[0]['vorname'] . ' ' . $kursleiter[0]['nachname'];
        $sportart =  $this->_model->getSportart($data['sportarten'][0]['sportart_id']);
        $data['kurse'][0]['sportart'] = $sportart[0]['sportart'];
        $this->_view->render('public/angebot', $data);
        $this->_view->render('footer');
    }
    
    /**
     * Rendert Seite fuer Impressum.
     */
    public function impressum()
    {
        $url = explode("/", $_GET['url']);
        
        $data['vorstand'] = $this->_common->getVorstand();
        $this->_view->render('header', $data);
        $this->_view->render('public/login', $data);
        $data['sportarten'] = $this->_common->getSportarten();
        $this->_view->render('public/navigation', $data);
        
        switch (array_pop($url)) {
            case 'vorstand':
                $data['vorstand'] = $this->_common->getVorstand();
                $this->_view->render('public/vorstand', $data);
                break;
            case 'mitglieder':
                $this->_view->render('public/content', $data);
                break;
            case 'kontakt':
                $this->_view->render('public/content', $data);
                break;
            default:
                $this->_view->render('public/content', $data);
                break;
        }
        $this->_view->render('footer');
    }

    /**
     * Rendert Seite fuer Registrierung.
     */
    public function register()
    {
        $data['title'] = 'Registrierung';
        //$data['content_title'] = 'Registrierung';       
        $this->_view->render('header', $data);
        $this->_view->render('public/login', $data);
        $data['sportarten'] = $this->_common->getSportarten();
        $this->_view->render('public/navigation', $data);
        $this->_view->render('public/registration', $data);
        $this->_view->render('footer');
    }
    
    /**
     * Rendert Seite fuer Loginfehler.
     */
    public function loginerror()
    {
        Session::set('csrf_token', uniqid('', true));
        $data['title'] = '&Uuml;bersicht';
        $this->_view->render('header', $data);
        $this->_view->render('public/login', $data);
        $data['sportarten'] = $this->_common->getSportarten();
        $this->_view->render('public/navigation', $data);
        $this->_view->render('error/login', $data);
        $this->_view->render('footer');
    }
    
    /**
     * Rendert Seite fuer Registrierungsfehler.
     * Fallunterscheidung bei Fehlern!
     */
    public function registrationerror()
    {
        $data['title'] = 'Registrierung';
        $this->_view->render('header', $data);
        $this->_view->render('public/login', $data);
        $data['sportarten'] = $this->_common->getSportarten();
        $this->_view->render('public/navigation', $data);
 
        switch (substr($_GET['url'], -1)) {
            case 0:
                Message::set('Fehler bei der &Uuml;bertragung der Daten!');
                break;
            case 1:
                Message::set('Angaben sind unvollst&auml;ndig!');
                break;
            case 2:
                Message::set('Die Mailadresse wird bereits verwendet!');
                break;
            case 3:
                Message::set('Passw�rter stimmen nicht �berein!');
                break;
            case 4:
                Message::set('Die Mailadresse ist ung&uuml;ltig!');
                break;
           default:
               Message::set('Unbekannter Fehler!');
               break;
        }
        
        $this->_view->render('error/registration', $data);
        $this->_view->render('footer');
    }
 
    /**
     * Rendert Seite fuer erfolgreiche Registration.
     */
    public function registrationsuccess()
    {
        $this->_view->render('header', $data);
        $this->_view->render('public/login', $data);
        $data['sportarten'] = $this->_common->getSportarten();
        $this->_view->render('public/navigation', $data);
        $this->_view->render('public/success', $data);
        $this->_view->render('footer');
    }
    
    /**
     * Weiterleitung zur Indexseite bei ung�ltiger Session.
     * Schutz vor csfr -  Website-�bergreifende Anfragenf�lschung.
     */
    public function safety()
    {
        //Session::destroy();
        //Session::set('csrf_token', uniqid('', true));
        Message::set('Sie sind ausgeloggt!');
        header("Location: " . DIR . "mainpage/index/" . Session::get('csrf_token'));
    }
    
    /**
     * Weiterleitung zur Indexseite nach Logut.
     */
    public function logout()
    {
        if(end(explode("/", $_GET['url'])) !== Session::get('csrf_token')) {
            Session::destroy();
            self::loginerror();
        }
        else {
            Session::destroy();
            //self::index();
            header("Location: " . DIR . "mainpage/index/" . Session::get('csrf_token'));
        }
    }
}

?>