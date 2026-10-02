<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.6.0                                                           |
// +---------------------------------------------------------------------------+
// | english.php                                                               |
// |                                                                           |
// | English language file                                                     |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2010-2026 by the following authors:                         |
// |                                                                           |
// | Authors: ::Ben                                                            |
// +---------------------------------------------------------------------------+
// | Created with the Geeklog Plugin Toolkit.                                  |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+

/**
* @package Maps
*/

/**
* Import Geeklog plugin messages for reuse
*
* @global array $LANG32
*/
global $LANG32;

// +---------------------------------------------------------------------------+
// | Array Format:                                                             |
// | $LANGXX[YY]:  $LANG - variable name                                       |
// |               XX    - specific array name                                 |
// |               YY    - phrase id or number                                 |
// +---------------------------------------------------------------------------+

$LANG_MAPS_1 = array(
    'plugin_name'           => 'Mappe',
    'plugin_conf'           => 'Configurazione del plugin',
    'mappa'                   => 'mappa',
    'need_google_api'       => 'Non è configurata alcuna chiave API Google Maps per il browser. Le mappe Google non possono essere visualizzate finché non viene aggiunta questa chiave.',
    'api_status_title' => 'Stato API Google Maps',
    'api_status_missing' => 'Non è configurata alcuna chiave API Google Maps per il browser.',
    'api_status_testing' => 'Test dell\'API JavaScript di Google Maps…',
    'api_status_key' => 'Chiave browser',
    'api_status_ok' => 'API JavaScript di Google Maps caricata correttamente: la chiave browser è accettata per questa pagina.',
    'api_status_auth' => 'Google Maps ha rifiutato la chiave browser o la sua configurazione. Controlla la console del browser per il codice di errore esatto di Google, quindi verifica le restrizioni dei referrer HTTP, le API abilitate e la fatturazione Google Cloud.',
    'api_status_load' => 'Impossibile caricare lo script dell\'API JavaScript di Google Maps. Controlla la rete, la policy CSP, i blocchi dei contenuti e la console del browser.',
    'api_status_timeout' => 'L\'API JavaScript di Google Maps non ha risposto. Controlla la console del browser e il pannello Rete.',
    'admin_help_title'      => 'Introduzione a Mappe',
    'admin_help_intro'      => 'Mappe consente di creare più mappe, aggiungere indicatori e, quando necessario, usare icone personalizzate o sovrapposizioni.',
    'admin_help_google'     => 'Configura Google Maps',
    'admin_help_google_1'   => 'Apri Google Cloud Console, crea o seleziona un progetto e collega un account di fatturazione per l\'uso in produzione.',
    'admin_help_google_2'   => 'Abilita almeno Maps JavaScript API. Abilita anche Geocoding API se utilizzi la conversione automatica degli indirizzi in latitudine/longitudine.',
    'admin_help_google_3'   => 'Crea una chiave API per il browser. Limitane l\'uso ai siti autorizzati (referrer HTTP), ad esempio https://www.example.com/*, quindi limita la chiave a Maps JavaScript API.',
    'admin_help_google_4'   => 'Per la geocodifica lato server è consigliata una seconda chiave. Limitane l\'uso all\'indirizzo IP del server e alla sola Geocoding API.',
    'admin_help_google_5'   => 'Copia la chiave browser nella chiave API Google Maps e, se utilizzata, la chiave server nella chiave API server Google Maps nella configurazione Mappe di Geeklog.',
    'admin_help_security'   => 'Non lasciare mai una chiave Google Maps senza restrizioni in produzione. Chiavi separate per browser e server riducono il rischio di uso non autorizzato.',
    'admin_help_create'     => 'Crea la prima mappa',
    'admin_help_create_1'   => 'Fai clic su Crea una nuova mappa, assegnale un nome e scegli centro, livello di zoom e tipo di visualizzazione.',
    'admin_help_create_2'   => 'Salva la mappa, quindi aggiungi indicatori dall\'amministrazione Mappe. Ogni indicatore può usare un indirizzo o coordinate precise.',
    'admin_help_create_3'   => 'Icone e sovrapposizioni sono facoltative. Inizia con una mappa semplice e alcuni indicatori per verificare la configurazione di Google Maps.',
    'admin_help_trouble'    => 'Se una mappa è disattivata o mostra «Solo a scopo di sviluppo», controlla la fatturazione Google Cloud, le API abilitate e le restrizioni della chiave API.',
    'admin_help_official'   => 'Documentazione ufficiale Google Maps Platform',
    'admin_help_geo_title'  => 'Che cosa fa «Controlla geolocalizzazione utenti»?',
    'admin_help_geo_intro'  => 'Questo comando analizza i membri che hanno compilato il campo Posizione nel profilo e prepara le coordinate per la mappa utenti.',
    'admin_help_geo_1'      => 'Mappe invia ogni posizione testuale non risolta a Google Geocoding API, ad esempio «Nantes, Francia».',
    'admin_help_geo_2'      => "Latitudine e longitudine restituite vengono memorizzate nella tabella di geocodifica di Mappe. Il profilo Geeklog del membro non viene modificato.",
    'admin_help_geo_3'      => 'È utile soprattutto dopo installazione o migrazione, oppure quando molti membri hanno aggiunto o modificato la posizione. Richiede Geocoding API e una chiave autorizzata per la geocodifica lato server.',
    'admin_help_overlays_title' => 'A cosa servono le sovrapposizioni?',
    'admin_help_overlays_intro' => 'Una sovrapposizione è un\'immagine georeferenziata posizionata sulla mappa Google tra coordinate sud-ovest e nord-est. Aggiunge informazioni visive che non fanno parte del livello base di Google Maps.',
    'admin_help_overlays_1' => 'Mostra la planimetria di un sito, campeggio, parco, proprietà, edificio o festival sopra la mappa reale.',
    'admin_help_overlays_2' => 'Sovrapponi una mappa storica, catastale, geologica o turistica, oppure una vecchia planimetria, per il confronto.',
    'admin_help_overlays_3' => 'Mostra un\'area tematica, come un percorso illustrato, una zona di lavoro, un\'area naturale, l\'impronta di un progetto o altre informazioni grafiche.',
    'admin_help_overlays_4' => 'Imposta i livelli di zoom minimo e massimo affinché la sovrapposizione venga mostrata solo quando è utile.',
    'admin_help_overlays_how' => 'Per crearne una: prepara un\'immagine adatta, apri Sovrapposizioni, inserisci i limiti sud-ovest e nord-est, quindi collegala a una mappa dalla scheda Sovrapposizioni nell\'editor. Le sovrapposizioni sono facoltative: per una normale mappa con punti, bastano gli indicatori.',
    'admin_help_concepts_title' => 'Concetti di Mappe in breve',
    'admin_help_concept_map' => 'Mappa',
    'admin_help_concept_map_text' => 'Il contenitore principale: centro, zoom, tipo di visualizzazione, dimensioni, permessi e opzioni generali.',
    'admin_help_concept_marker' => 'Indicatore',
    'admin_help_concept_marker_text' => 'Un punto geografico sulla mappa con nome, descrizione, indirizzo e informazioni aggiuntive facoltative.',
    'admin_help_concept_icon' => 'Icona',
    'admin_help_concept_icon_text' => 'Un\'immagine facoltativa che sostituisce l\'indicatore standard di Google per distinguere le categorie di punti.',
    'admin_help_concept_users' => 'Mappa utenti',
    'admin_help_concept_users_text' => 'Una mappa generata dal campo Posizione del profilo Geeklog. Le coordinate vengono risolte e memorizzate dal sistema di geocodifica di Mappe.',
    'admin_help_trouble_title' => 'Risoluzione rapida dei problemi',
    'profile_title'         => 'Geolocalizzazione',
    'buy_marker'            => 'Acquista un indicatore',
    'menu_label'            => 'Amministrazione Mappe',
    'admin_home'            => 'Home', // In admin menu
    'user_home'             => 'Tutte le mappe', //In user menu
    'maps'                  => 'Mappe',
    'indicatori'               => 'Indicatori',
    'maps_label'            => 'Mappe', // For user  menu
    'create_map'            => 'Crea una nuova mappa',
    'create_marker'         => 'Crea un nuovo indicatore',
    'map_edit'              => 'Modifica mappa',
    'marker_edit'           => 'Modifica indicatore',
    'deletion_succes'       => 'Eliminazione riuscita',
    'deletion_fail'         => 'Eliminazione non riuscita',
    'error'                 => 'Errore',
    'save_fail'             => 'Salvataggio non riuscito',
    'save_success'          => 'Salvataggio riuscito',
    'missing_field'         => 'Campo obbligatorio mancante…',
    'geocoder'              => 'Geocodificatore',
    'geocoder_text'         => 'Inserisci un indirizzo, quindi trascina l\'indicatore per regolare la posizione. Latitudine e longitudine appariranno nella finestra informativa dopo ogni geocodifica/spostamento.',
    'geocode_failed'         => 'Impossibile geocodificare l\'indirizzo. Controlla la chiave API Google Maps, l\'attivazione di Geocoding API e l\'indirizzo, quindi riprova.',
    'go'                    => 'Vai!',
    'name_label'            => 'Nome mappa: ',
    'marker_name_label'     => 'Nome indicatore: ',
    'description_label'     => 'Descrizione:',
    'ok_button'             => 'OK',
    'edit_button'           => 'Modifica',
    'save_button'           => 'Salva',
    'delete_button'         => 'Elimina',
    'yes'                   => 'Sì',
    'no'                    => 'No',
    'required_field'        => 'Indica un campo obbligatorio',
    'address_label'         => 'Indirizzo: ',
    'message'               => 'Messaggio',
    'general_settings'      => 'Impostazioni generali',
    'map_width'             => 'Larghezza mappa (% o px, minimo 550 px): ',
    'map_height'             => 'Altezza mappa (solo px, minimo 350 px): ',
    'map_zoom'              => 'Zoom mappa (0-21): ',
    'map_type'              => 'Tipo mappa: ',
    'active'                => 'La mappa è attiva: ',
    'hidden'                => 'La mappa è nascosta: ',
    'marker_active'         => 'L\'indicatore è attivo: ',
    'marker_hidden'         => 'L\'indicatore è nascosto: ',
    'free_marker'           => 'La mappa accetta indicatori gratuiti: ',
    'paid_marker'           => 'La mappa accetta indicatori a pagamento: ',
    'error_address_empty'   => 'Inserisci prima un indirizzo valido.',
    'error_invalid_address' => 'Questo indirizzo non è valido. Assicurati di inserire anche numero civico e città.',
    'error_google_error'    => 'Si è verificato un problema durante l\'elaborazione della richiesta. Riprova.',
    'error_no_map_info'     => 'Spiacenti, le informazioni della mappa non sono disponibili per questo indirizzo.',
    'need_directions'       => 'Servono indicazioni? Inserisci il tuo indirizzo:',
    'directions_title'     => 'Pianifica il percorso',
    'directions_start'     => 'Punto di partenza',
    'get_directions'        => '  Ottieni indicazioni  ',
    'maps_list'             => 'Elenco mappe',
    'you_can'               => 'Puoi ',
    'user_maps_list'        => 'Esplora le nostre mappe',
    'markers_list'          => 'Elenco indicatori',
    'map_markers_heading'   => 'Indicatori su questa mappa',
    'marker_singular'      => 'indicatore',
    'marker_plural'        => 'indicatori',
    'views_label'          => 'visualizzazioni',
    'no_map'                => 'Non ci sono mappe nel database. Devi crearne una per aggiungere indicatori.',
    'no_map_user'           => 'Ops… Non ci sono mappe attive nel database.',
    'value_directions'      => 'es. numero e via, città, paese', // No quote here please
    'id'                    => 'ID',
    'name'                  => 'Nome',
    'description'           => 'Descrizione',
    'active_field'          => 'Attivo',
    'hidden_field'          => 'Nascosto',
    'marker_count'          => 'Indicatori',
    'status_active'         => 'Attivo',
    'status_inactive'       => 'Inattivo',
    'status_visible'        => 'Visibile',
    'status_hidden'         => 'Nascosto',
    'title_display'         => 'Visualizza pagina della mappa',
    'map_header_label'      => 'Intestazione facoltativa della mappa',
    'map_footer_label'      => 'Piè di pagina facoltativo della mappa',
    'header_footer'         => 'Intestazione e piè di pagina',
    'informations'          => 'Informazioni',
    'must_belong_to'        => 'Per accedere a questa mappa devi appartenere al gruppo:',
    'private_access'        => 'Accesso privato',
    'marker_label'          => 'Indicatore',
    'primary_color_label'   => 'Colore principale',
    'stroke_color_label'    => 'Colore bordo',
    'label'                 => 'Etichetta',
    'label_color'           => 'Colore etichetta',
    'black'                 => 'Nero',
    'white'                 => 'Bianco',
    'payed'                 => 'Indicatore a pagamento:',
    'lat'                   => 'Latitudine:',
    'lng'                   => 'Longitudine:',
    'ressources_tab'        => 'Scheda Risorse',
    'presentation'          => 'Presentazione',
    'ressources'            => 'Risorse',
    'presentation_tab'      => 'Scheda Presentazione',
    'empty_ressources'      => 'Le etichette delle risorse sono vuote. Devi definirne almeno una per usare le risorse. Consulta la configurazione.',
    'empty_for_geo'         => 'Lascia vuote latitudine e longitudine se vuoi la geolocalizzazione automatica dall\'indirizzo sopra.',
    'select_marker_map'     => 'Seleziona la mappa sulla quale vuoi far apparire l\'indicatore.',
    'remark'                => 'Note',
    'marker_created'        => 'Indicatore creato il:',
    'map_created'           => 'Mappa creata il:',
    'modified'              => 'Ultima modifica:',
    'marker_validity'       => 'Usa data di validità:',
    'maps_empty'            => 'Crea prima una mappa.',
    'from'                  => 'Da:',
    'a'                    => 'A:',
    'date_issue'            => 'La fine della validità precede l\'inizio. Controlla i dati.',
    'max_char'              => 'caratteri massimi.',
    'street_label'          => 'Via:',
    'code_label'            => 'CAP:',
    'city_label'            => 'Città:',
    'state_label'           => 'Stato/Regione:',
    'country_label'         => 'Paese:',
    'tel_label'             => 'Tel:',
    'fax_label'             => 'Contatto aggiuntivo:',
    'web_label'             => 'Web:',
    'not_use_see_config'    => 'Non usare. Vedi configurazione',
    //global maps
    'global_map'            => 'Mappa globale',
    'info_global_map'       => 'Tutte le mappe riunite in una.',
    'users_map'             => 'Mappa degli utenti del sito',
    'info_users_map'        => 'Questa è la mappa degli utenti del sito. Puoi aggiungerti impostando la posizione nel profilo.',
    //Submission
    'address'               => 'Indirizzo',
    'created'               => 'Data',
    'submit_marker'         => 'Invia un indicatore',
    'submit_marker_text'    => '<p><ol><li>Imposta la posizione dell\'indicatore<li>Compila tutti i campi<li>Conferma</ol></p>',
    'markers_submissions'   => 'Invii di indicatori',
    'submission_disabled'   => 'Coda di invio disabilitata per gli indicatori',
    'go'                    => 'Mostra questo indirizzo',
    //date and hits
    'last_modification'     => 'Ultima modifica:',
    'visite'                  => 'visite',
    //user marker
    'member'                => 'Membro',
    'location'              => 'Posizione: ',
    'regdate'               => 'Membro dal: ',
    'about'                 => 'Informazioni',
    'my_markers'            => 'I miei indicatori',
    'payed_label'           => 'A pagamento',
    'from_label'            => 'Validità dal',
    'to_label'              => 'Validità fino al',
    'no_marker'             => 'Non hai indicatori oppure non sono ancora stati approvati. Se pensi che sia un errore, contatta l\'amministratore del sito.',
    'marker_detail'         => 'Dettaglio indicatore',
    'admin_can'             => 'Come amministratore delle mappe puoi',
    'create_map'            => 'Crea una nuova mappa',
    'set_user_geo'          => 'Imposta geolocalizzazione utenti',
    'set_geo_location'      => 'Il sistema controllerà e imposterà tutte le geolocalizzazioni.',
    'record'               => 'record',
    'report'                => 'Segnala questo indicatore',
    'report_subject'        => 'Segnalazione sull\'indicatore ',
    'edit_marker_text'      => '<p><ol><li>Imposta la posizione dell\'indicatore<li>Compila tutti i campi obbligatori<li>Conferma</ol></p>',
    'admin'                 => 'Amministrazione',
    'category_label'        => 'Categoria:',
    'choose_category'       => '-- Scegli categoria --',
    'categories'            => 'Categorie',
    'categories_list'       => 'Elenco categorie',
    'cat_edit'              => 'Modifica categoria:',
    'cat_name_label'        => 'Nome categoria:',
    'create_cat'            => 'crea una nuova categoria',
    'field_list'            => 'Elenco campi',
    'addfield'              => 'Aggiungi un campo',
    'field_name'            => 'Nome campo',
    'field_order'           => 'Ordine',
    'field_autotag'         => 'Autotag',
    'field_rights'          => 'Permessi',
    'field_edit'            => 'Modifica',
    'valid'                 => 'Valido',
    'editing_field'         => 'Modifica campo',
    'category'              => 'Categoria',
    'map_label'             => 'Mappa',
    'colon'                 => ':', //Add space before and after if needed
    'view_map'              => 'Visualizza mappa',
    'view_markers'          => 'Visualizza elenco indicatori',
    'code'                  => 'CAP',
    'city'                  => 'Città',
    'viewing_markers'       => 'Visualizza l\'elenco degli indicatori',
    'details'               => 'Dettagli',
    'view_details'          => 'Visualizza dettagli',
    'print'                 => 'Stampa',
	'to_complete'           => 'Da completare',
	'autotag_desc_maps'     => '[maps: xx zoom:ZZ location] - Visualizza la mappa con id=XX. Le opzioni sono il livello di zoom (da 0 a 21) e il centro della mappa su location.',
	'autotag_desc_geo'      => '[geo: Paris, France zoom:12] - Visualizza una mappa centrata su un luogo o indirizzo. Parametri facoltativi: zoom, width e height. La sintassi storica [geo: map ...] resta supportata.',
	'autotag_desc_marker'   => '[marker: xx] - Visualizza l\'indicatore con id=XX',
	//v1.1
	'marker_customisation'  => 'Personalizzazione indicatore',
	'mk_default'            => 'Usa indicatore predefinito',
	'overlays'              => 'Sovrapposizioni',
	'overlays_list'         => 'Elenco sovrapposizioni',
	'create_overlay'        => 'Crea una nuova sovrapposizione',
	'edit_overlay_text'     => 'Modifica sovrapposizione:',
	'overlay_edit'          => 'Modifica sovrapposizione',
	'overlay_name_label'    => 'Nome sovrapposizione:',
	'overlay_presentation'  => 'Le sovrapposizioni sono oggetti legati a coordinate latitudine/longitudine, quindi si muovono quando trascini o ingrandisci la mappa. Rappresentano oggetti aggiunti alla mappa per indicare punti, linee o aree. Qui puoi aggiungere un\'immagine come sovrapposizione.',
	'overlay_active'        => 'Questa sovrapposizione è attiva:',
	'zoom_min_label'        => 'Zoom min:',
	'zoom_max_label'        => 'Zoom max:',
	'image_message'         => 'Seleziona un\'immagine dal disco.',
	'image_replace'         => 'Il caricamento di una nuova immagine sostituirà questa:',
	'image'                 => 'Immagine',
	'sw_lat'                => 'Latitudine SO:',
    'sw_lng'                => 'Longitudine SO:',
	'ne_lat'                => 'Latitudine NE:',
    'ne_lng'                => 'Longitudine NE:',
	'overlay_not_writable'  => 'La cartella delle sovrapposizioni non è scrivibile. Creala e rendila scrivibile prima di usare questa funzione.',
	'map_tab'               => 'Mappa',
	'overlays_tab'          => 'Sovrapposizioni',
	'add_overlay'           => 'Aggiungi sovrapposizione',
	'remove_overlay'        => 'Rimuovi sovrapposizione',
	'overlay_label'         => 'Sovrapposizione',
	'import_export'         => 'Importazione/Esportazione',
	'import'                => 'Importa',
	'export'                => 'Esporta',
	'select_file'           => 'Seleziona un file .csv',
	'import_message'        => 'Seleziona la mappa a cui aggiungere gli indicatori, il file CSV dal disco, il delimitatore dei dati e i campi da importare.',
	'markers_added'         => 'Indicatori aggiunti alla mappa:',
	'export_message'        => 'Scegli la mappa da cui esportare gli indicatori, il delimitatore dei dati e i campi da esportare.',
	'no_marker_to_export'   => 'Spiacenti, non ci sono indicatori da esportare da questa mappa.',
	'icons'                 => 'Icone',
	'icons_not_writable'    => 'La cartella delle icone non è scrivibile. Creala e rendila scrivibile prima di usare questa funzione.',
	'icons_list'            => 'Elenco icone',
	'create_icon'           => 'Crea una nuova icona',
	'icon_edit'             => 'Modifica icona',
	'icon_presentation'     => 'Qui puoi caricare una nuova icona da usare con gli indicatori', 
	'icon_name_label'       => 'Nome icona',
	'xmarkers'              => 'indicatori',
	'1marker'               => 'indicatore',
	'choose_icon'           => 'Puoi scegliere un\'icona per questo indicatore. Le icone prioritarie sono sopra i colori.',
	'no_icon'               => 'Nessuna icona',
	'no_custom_icons'        => 'Non è ancora registrata alcuna icona personalizzata.',
	'manage_icons'           => 'Gestisci icone',
	'separator'             => 'Scegli delimitatore',
	'markers_to_add'        => 'Controlla tutte le coppie campo/valore e conferma di voler aggiungere alla mappa tutti gli indicatori seguenti:',
	'choose_fields_import'  => 'Scegli campi da importare',
	'choose_fields_export'  => 'Scegli campi da esportare',
	'checkall'              => 'Seleziona tutto',
    'import_step_1' => 'Prepara l\'importazione',
    'import_step_1_text' => 'Scegli la mappa di destinazione, il file CSV, il delimitatore e l\'ordine delle colonne.',
    'import_step_2' => 'Controlla i dati',
    'import_step_2_text' => 'Mappe valida, normalizza e geocodifica le righe prima che venga scritto qualsiasi dato.',
    'import_step_3' => 'Conferma l\'importazione',
    'import_step_3_text' => 'Controlla destinazione, proprietario e permessi, quindi conferma il lotto.',
    'import_minimum' => 'Campi minimi',
    'import_minimum_help' => 'name + address oppure name + lat + lng. Il preset Minimo usa l\'opzione basata sull\'indirizzo.',
    'import_recommended' => 'Campi consigliati',
    'import_recommended_help' => 'name, address, lat, lng, description, street, code, city, state, country, tel e web.',
    'import_order_help' => 'Le colonne CSV devono seguire lo stesso ordine dei campi selezionati mostrati sotto.',
    'import_select_minimum' => 'Campi minimi',
    'import_select_recommended' => 'Campi consigliati',
    'import_clear_fields' => 'Cancella selezione',
    'import_preview_title' => 'Controlla i dati',
    'import_preview_text' => 'Questi sono i valori normalizzati che saranno scritti se confermi l\'importazione.',
    'import_summary_rows' => 'righe pronte',
    'import_summary_coordinates' => 'coordinate fornite',
    'import_summary_geocoded' => 'geocodificati automaticamente',
    'import_summary_partial' => 'con dettagli parziali dell\'indirizzo',
    'import_status' => 'Stato',
    'import_status_ready' => 'Pronto',
    'import_status_partial' => 'Pronto · dettagli parziali',
    'import_status_geocoded' => 'geocodificato',
    'import_confirm_title' => 'Conferma l\'importazione',
    'import_confirm_text' => 'Controlla le impostazioni del lotto prima di creare gli indicatori.',
    'import_confirm_button' => 'Importa %d indicatori',
    'import_cancel_button' => 'Annulla',
	'order'                 => 'Ordine',
	'move'                  => 'Sposta',
	'name_missing'          => 'Manca almeno un nome. Controlla il file CSV.',
	'need_address'          => 'Serve almeno un indirizzo o delle coordinate per creare un indicatore. Controlla il file CSV: manca qualcosa.',
	'manage_groups'         => 'Gestisci gruppi di sovrapposizioni',
	'create_group'          => 'Crea un nuovo gruppo di sovrapposizioni',
	'group_edit'            => 'Modifica gruppo di sovrapposizioni',
	'group_overlay_presentation' => 'Qui puoi scegliere o modificare il nome del gruppo di sovrapposizioni',
	'group_overlay_name_label'   => 'Nome del gruppo',
	'group_label'           => 'Gruppo (facoltativo)',
	'choose_group'          => 'Scegli un gruppo',
	'group'                 => 'Gruppo',
	
	//v1.3
	'geo_fail'              => 'L\'indirizzo inserito non sembra valido',
	'on_map'                => 'Sulla mappa',
	'read_more'             => 'Leggi di più',
	'from_map'              => 'Mappa:',
	'show_hide_overlays'    => 'Mostra / nascondi sovrapposizioni',
	'fields_presentation'   => 'Modifica una categoria esistente per aggiungere o modificare un campo.',
	'overlays_added'        => 'Sovrapposizioni presenti su questa mappa',
	'overlays_to_add'       => 'Sovrapposizioni che puoi aggiungere a questa mappa',
	'marker_modification'   => 'Modifica indicatore',
	'from_owner'            => 'Aggiunto da:',
	'marker_limited'        => 'Spiacenti, l\'accesso a questo indicatore è limitato…',
	'events_map'            => 'Mappa dei prossimi eventi',
	'info_events_map'       => '',
	'from_cal'              => 'Da',
	'to_cal'                => 'a',
	'on_cal'                => 'Attivo',
    //v1.4
    'admin_menu_maps' => 'Mappe',
    'admin_menu_markers' => 'Indicatori',
    'admin_menu_icons' => 'Icone',
    'admin_menu_overlays' => 'Sovrapposizioni',
    'admin_menu_import_export' => 'Importazione/Esportazione',
    'admin_menu_geocoder' => 'Geocodificatore',
    'admin_menu_geolocation' => 'Geolocalizzazione',
    'admin_menu_configuration' => 'Configurazione',
    'section_location' => 'Posizione',
    'section_content_contact' => 'Contenuto e contatto',
    'section_appearance' => 'Aspetto',
    'section_publication' => 'Pubblicazione',
    'section_resources' => 'Risorse',
    'section_ownership' => 'Proprietario',
    'section_permissions' => 'Permessi',
    'delete_confirm' => 'Eliminare definitivamente questo indicatore?',
    'marker_not_found' => 'Indicatore non trovato oppure non hai il permesso di visualizzarlo.',
    'delete_map_confirm' => 'Eliminare definitivamente questa mappa?',
    'technical_coordinates' => 'Coordinate tecniche',
    'configuration'         => 'Configurazione',
    // Maps 1.5.6 map editor
    'map_section_display' => 'Visualizzazione',
    'map_section_center' => 'Centro e zoom',
    'map_section_markers' => 'Indicatori',
    'map_section_advanced' => 'Opzioni avanzate',
    'map_center_search' => 'Cerca un indirizzo',
    'map_center_search_button' => 'Localizza',
    'map_center_use_button' => 'Usa il centro visualizzato',
    'map_center_help' => 'Cerca un indirizzo, fai clic sulla mappa o trascina l\'indicatore per scegliere con precisione il centro della mappa.',
    'latitude_label' => 'Latitudine',
    'longitude_label' => 'Longitudine',
    'map_center_marker' => 'Centro mappa',

);

$LANG_MAPS_MESSAGE = array(
    'message'               => 'Messaggio dal sistema',
    'add_new_field'         => 'Il nuovo campo è stato creato correttamente',
    'save_field'            => 'Il campo è stato salvato correttamente',
    'delete_field'          => 'Il campo è stato eliminato correttamente'
);

$LANG_MAPS_EMAIL = array(
    'hello_admin'           => 'Ciao amministratore,',
    'new_marker'            => 'C\'è un nuovo indicatore in attesa di approvazione.',
    'name'                  => 'Nome:',
    'on_map'                => 'Sulla mappa:',
    'submissions'           => 'Invii: ',
    'marker_submissions'    => 'Invii di indicatori',
	'marker_modification'   => 'Modifica indicatore',
	'description'           => 'Descrizione:',
);

// Messages for the plugin upgrade
$PLG_maps_MESSAGE3002 = $LANG32[9]; // "requires a newer version of Geeklog"

$PLG_maps_MESSAGE1  = "Grazie per aver inviato un indicatore a {$_CONF['site_name']}. È stato inoltrato al personale per l'approvazione.";
$PLG_maps_MESSAGE2  = "L'invio degli indicatori è chiuso.";
$PLG_maps_MESSAGE3  = "Ops… Si è verificato un errore. Non posso salvare il tuo indicatore.";

/**
*   Localization of the Admin Configuration UI
*   @global array $LANG_configsections['maps']
*/
$LANG_configsections['maps'] = array(
    'label' => 'Mappe',
    'title' => 'Configurazione Mappe'
);

/**
*   Configuration system prompt strings
*   @global array $LANG_confignames['maps']
*/
$LANG_confignames['maps'] = array(
    'hide_maps_menu'        => 'Nascondi menu Mappe',
    'maps_login_required'   => 'Accesso richiesto per Mappe',
    'autofill_coord'        => 'Compila automaticamente le coordinate non definite',
    'display_geo_profile'   => 'Geolocalizzazione profilo',
    'map_type_profile'      => 'Tipo di mappa del profilo',
    'map_type_geotag'       => 'Tipo di mappa dell\'autotag geo',
    'show_directions_geo'   => 'Mostra indicazioni nell\'autotag geo',
    'show_directions_profile' => 'Mostra indicazioni nel profilo',
    'map_width_geotag'      => 'Larghezza mappa autotag geo (con % o px)',
    'map_height_geotag'     => 'Altezza mappa autotag geo (solo px)',
    'map_zoom_geotag'       => 'Zoom autotag geo (0-21)',
    'map_width_profile'     => 'Larghezza mappa profilo (con % o px)',
    'map_height_profile'    => 'Altezza mappa profilo (solo px)',
    'show_map'              => 'Mostra mappa Google',
    'google_api_key'        => 'Chiave API Google Maps',
    'url_geocode'           => 'URL del servizio di geocodifica Google',
    'map_width'             => 'Larghezza predefinita mappe (con % o px)',
    'map_height'            => 'Altezza predefinita mappe (solo px)',
    'map_zoom'              => 'Zoom predefinito mappe (0-21)',
    'map_type'              => 'Tipo di mappa predefinito',
    'default_permissions'   => 'Permessi predefiniti',
    'map_main_header'       => 'Intestazione pagina principale, autotag welcome',
    'map_main_footer'       => 'Piè di pagina principale, anche autotag welcome',
    'map_geo'               => 'Crea una mappa con tutti i profili',
    'map_markers'           => 'Crea una mappa con tutti gli indicatori',
    'map_active'            => 'La mappa è attiva',
    'map_hidden'            => 'La mappa è nascosta',
    'free_markers'          => 'La mappa accetta indicatori gratuiti',
    'paid_markers'          => 'La mappa accetta indicatori a pagamento (richiede il plugin PayPal)',
    'street'                => 'Usa informazioni sulla via',
    'code'                  => 'Usa CAP',
    'city'                  => 'Usa città',
    'state'                 => 'Usa stato/regione',
    'country'               => 'Usa paese',
    'tel'                   => 'Usa telefono',
    'fax'                   => 'Usa contatto aggiuntivo',
    'web'                   => 'Usa web',
    'item_1'                => 'Etichetta campo personalizzato 1',
    'item_2'                => 'Etichetta campo personalizzato 2',
    'item_3'                => 'Etichetta campo personalizzato 3',
    'item_4'                => 'Etichetta campo personalizzato 4',
    'item_5'                => 'Etichetta campo personalizzato 5',
    'item_6'                => 'Etichetta campo personalizzato 6',
    'item_7'                => 'Etichetta campo personalizzato 7',
    'item_8'                => 'Etichetta campo personalizzato 8',
    'item_9'                => 'Etichetta campo personalizzato 9',
    'item_10'               => 'Etichetta campo personalizzato 10',
    'label_color'           => 'Colore etichetta',
    'star_primary_color'    => 'Colore principale stella',
    'star_stroke_color'     => 'Colore bordo stella',
    'marker_active'         => 'L\'indicatore è attivo per impostazione predefinita',
    'marker_hidden'         => 'L\'indicatore è nascosto per impostazione predefinita',
    'marker_payed'          => 'Indicatore a pagamento per impostazione predefinita',
    'marker_validity'       => 'Validità indicatore predefinita',
    'marker_submission'     => 'Consenti invio indicatori',
    'users_map'             => 'Mappa attiva degli utenti del sito',
    'global_map' 	        => 'Mappa globale attiva',
    'global_type'           => 'Tipo mappa globale',	
    'global_width'  	    => 'Larghezza mappa globale',
    'global_height' 	    => 'Altezza mappa globale',
    'global_zoom'           => 'Zoom mappa globale (0-21)',
    'detail_zoom'           => 'Zoom dettaglio indicatore (0-21)',
    'submit_login_required' => 'Accesso richiesto per inviare indicatori',
    'marker_edition'        => 'Modifica indicatore',
	'use_cluster'           => 'Usa raggruppamento indicatori',
	'zoom_profile'          => 'Zoom mappa nel profilo utente (0-21)',
	'display_events_map'    => 'Mostra mappa eventi',
);

/**
*   Configuration system subgroup strings
*   @global array $LANG_configsubgroups['maps']
*/
$LANG_configsubgroups['maps'] = array(
    'sg_main' => 'Impostazioni principali',
    'sg_display' => 'Impostazioni visualizzazione'
);

/**
*   Configuration system tab names
*   @global array $LANG_configtabs['maps']
*/
$LANG_configtabs['maps'] = array(
    'tab_general' => 'Generale',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'Mappe',
    'tab_markers' => 'Indicatori',
    'tab_fields' => 'Campi indicatore',
);

/** Geeklog configuration tab labels (used by config::_UI_get_tab). */
$LANG_tab['maps'] = array(
    'tab_general' => 'Generale',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'Mappe',
    'tab_markers' => 'Indicatori',
    'tab_fields' => 'Campi indicatore',
);

/**
*   Configuration system fieldset names
*   @global array $LANG_fs['maps']
*/
$LANG_fs['maps'] = array(
    'fs_main'            => 'Impostazioni generali',
    'fs_ads'             => 'Impostazioni Google Ads',
    'fs_google'          => 'Impostazioni API Google',
    'fs_permissions'     => 'Permessi predefiniti',
    'fs_display'         => 'Mappe',
    'fs_global_map'      => 'Mappe globali',
    'fs_display_profile' => 'Profilo',
    'fs_display_geo'     => 'autotag geo',
    'fs_map_default'     => 'Impostazioni predefinite mappa',
    'fs_marker_default'  => 'Impostazioni predefinite indicatore',
 );

/**
*   Configuration system selection strings
*   Note: entries 0, 1, and 12 are the same as in 
*   $LANG_configselects['Core']
*
*   @global array $LANG_configselects['maps']
*/
$LANG_configselects['maps'] = array(
    0 => array('Vero' => 1, 'Falso' => 0),
    1 => array('Vero' => TRUE, 'Falso' => FALSE),
    3 => array('Sì' => 1, 'No' => 0),
    4 => array('Attivo' => 1, 'Disattivo' => 0),
    5 => array('Inizio pagina' => 1, 'Sotto l\'articolo in evidenza' => 2, 'Fine pagina' => 3),
    10 => array('5' => 5, '10' => 10, '25' => 25, '50' => 50),
    11 => array('Miglia' => 'miglia', 'Chilometri' => 'km'),
    12 => array('Nessun accesso' => 0, 'Sola lettura' => 2, 'Lettura-scrittura' => 3),
	// changed in v1.3
    20 => array('Mappa stradale normale' => 'ROADMAP', 'Immagini satellitari' => 'SATELLITE', 'Mappa del terreno' => 'TERRAIN', 'Livello trasparente delle strade principali sulle immagini satellitari' => 'HYBRID'),
    30 => array('Bianco' => 1, 'Nero' => 0),
    31 => array('Temporaneo' => 1, 'Permanente' => 0),
);

$LANG_MAPS_1['location_search_label'] = 'Cerca un indirizzo';
$LANG_MAPS_1['location_search_help'] = 'Cerca un indirizzo, fai clic sulla mappa o trascina l\'indicatore per regolarne con precisione la posizione.';
$LANG_MAPS_1['use_map_click_help'] = 'Fai clic sulla mappa per spostare l\'indicatore.';

/* Maps 1.5.7 configuration labels. */
$LANG_configsubgroups['maps']['sg_main'] = 'Principale';
$LANG_configtabs['maps']['tab_general'] = 'Generale';
$LANG_tab['maps']['tab_general'] = 'Generale';
$LANG_configtabs['maps']['tab_google'] = 'Google Maps';
$LANG_tab['maps']['tab_google'] = 'Google Maps';
$LANG_configtabs['maps']['tab_maps'] = 'Mappe';
$LANG_tab['maps']['tab_maps'] = 'Mappe';
$LANG_configtabs['maps']['tab_markers'] = 'Indicatori';
$LANG_tab['maps']['tab_markers'] = 'Indicatori';
$LANG_configtabs['maps']['tab_fields'] = 'Campi indicatore';
$LANG_tab['maps']['tab_fields'] = 'Campi indicatore';

$LANG_fs['maps']['fs_main'] = 'Accesso e funzioni';
$LANG_fs['maps']['fs_permissions'] = 'Permessi predefiniti';
$LANG_fs['maps']['fs_uploads'] = 'Immagini e caricamenti';
$LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
$LANG_fs['maps']['fs_display'] = 'Visualizzazione generale';
$LANG_fs['maps']['fs_global_map'] = 'Mappa globale e utenti';
$LANG_fs['maps']['fs_display_profile'] = 'Mappa profilo utente';
$LANG_fs['maps']['fs_display_geo'] = 'Autotag geo';
$LANG_fs['maps']['fs_map_defaults'] = 'Valori predefiniti nuove mappe';
$LANG_fs['maps']['fs_events_map'] = 'Mappa eventi';
$LANG_fs['maps']['fs_marker_defaults'] = 'Valori predefiniti indicatori';
$LANG_fs['maps']['fs_marker_editor'] = 'Mappa editor indicatore';
$LANG_fs['maps']['fs_marker_detail'] = 'Mappa dettaglio indicatore';
$LANG_fs['maps']['fs_marker_popup'] = 'Finestre informative indicatore';
$LANG_fs['maps']['fs_marker_fields'] = 'Campi ed etichette indicatore';

$LANG_confignames['maps']['max_image_width'] = 'Larghezza massima immagine (px)';
$LANG_confignames['maps']['max_image_height'] = 'Altezza massima immagine (px)';
$LANG_confignames['maps']['max_image_size'] = 'Dimensione massima immagine (byte)';
$LANG_confignames['maps']['google_api_key'] = 'Chiave API Google Maps per browser';
$LANG_confignames['maps']['google_server_api_key'] = 'Chiave API server Google Geocoding';
$LANG_confignames['maps']['google_map_id'] = 'ID mappa Google (preparazione Advanced Markers)';
$LANG_confignames['maps']['google_language'] = 'Lingua Google Maps (facoltativa, es. it)';
$LANG_confignames['maps']['google_region'] = 'Regione Google Maps (facoltativa, es. IT)';
$LANG_confignames['maps']['url_geocode'] = 'URL servizio Google Geocoding';
$LANG_confignames['maps']['map_primary_color'] = 'Colore principale predefinito della mappa';
$LANG_confignames['maps']['map_stroke_color'] = 'Colore bordo predefinito della mappa';
$LANG_confignames['maps']['map_label'] = 'Etichetta predefinita indicatore mappa';
$LANG_confignames['maps']['map_label_color'] = 'Colore predefinito etichetta mappa';
$LANG_confignames['maps']['events_map_zoom'] = 'Zoom mappa eventi';
$LANG_confignames['maps']['events_map_height'] = 'Altezza mappa eventi';
$LANG_confignames['maps']['users_map_lat'] = 'Latitudine centro mappa utenti (vuoto = automatico)';
$LANG_confignames['maps']['users_map_lng'] = 'Longitudine centro mappa utenti (vuoto = automatico)';
$LANG_confignames['maps']['users_map_zoom'] = 'Zoom mappa utenti (vuoto = mappa globale)';
$LANG_confignames['maps']['users_map_type'] = 'Tipo mappa utenti (vuoto = mappa globale)';
$LANG_confignames['maps']['users_map_width'] = 'Larghezza mappa utenti (vuoto = mappa globale)';
$LANG_confignames['maps']['users_map_height'] = 'Altezza mappa utenti (vuoto = mappa globale)';
$LANG_confignames['maps']['marker_editor_type'] = 'Tipo mappa editor indicatore';
$LANG_confignames['maps']['marker_editor_zoom'] = 'Zoom iniziale editor indicatore';
$LANG_confignames['maps']['marker_editor_width'] = 'Larghezza mappa editor indicatore';
$LANG_confignames['maps']['marker_editor_height'] = 'Altezza mappa editor indicatore';
$LANG_confignames['maps']['detail_width'] = 'Larghezza mappa dettaglio indicatore';
$LANG_confignames['maps']['detail_height'] = 'Altezza mappa dettaglio indicatore';
$LANG_confignames['maps']['detail_zoom'] = 'Zoom mappa dettaglio indicatore';
$LANG_confignames['maps']['popup_width'] = 'Larghezza finestra informativa';
$LANG_confignames['maps']['popup_height'] = 'Altezza finestra informativa';

/* Maps 1.5.10 landing-page SEO configuration. */
$LANG_fs['maps']['fs_seo'] = 'SEO pagina di destinazione';
$LANG_confignames['maps']['maps_page_title'] = 'Titolo SEO della pagina di destinazione Mappe';
$LANG_confignames['maps']['maps_page_h1'] = 'Titolo H1 della pagina di destinazione Mappe';
$LANG_confignames['maps']['maps_meta_description'] = 'Meta descrizione della pagina di destinazione Mappe';
$LANG_confignames['maps']['map_main_header'] = 'Contenuto introduttivo della pagina di destinazione Mappe (autotag supportati)';

$LANG_MAPS_1['server_geocode_key_missing'] = 'La ricerca delle coordinate lato server è abilitata, ma non è configurata una chiave API server Google Geocoding dedicata. La chiave browser non viene mai usata per la geocodifica lato server.';
$LANG_MAPS_1['api_diag_title'] = 'Configurazione Google Maps Platform';
$LANG_MAPS_1['api_diag_maps_js'] = 'API JavaScript Maps';
$LANG_MAPS_1['api_diag_geocoding'] = 'API Geocoding';
$LANG_MAPS_1['api_diag_directions'] = 'API indicazioni';
$LANG_MAPS_1['api_diag_browser_key'] = 'Chiave API browser';
$LANG_MAPS_1['api_diag_server_key'] = 'Chiave API server';
$LANG_MAPS_1['api_diag_map_id'] = 'ID mappa';
$LANG_MAPS_1['api_diag_configured'] = 'Chiave configurata — API non verificata';
$LANG_MAPS_1['api_diag_browser_verify'] = 'Chiave configurata — verifica con il test del browser qui sotto';
$LANG_MAPS_1['api_diag_referrer_hint'] = 'Per le restrizioni dei referrer HTTP della chiave browser, autorizza questo sito (ad esempio: %s/*).';
$LANG_MAPS_1['api_diag_missing'] = 'Mancante';
$LANG_MAPS_1['api_diag_optional'] = 'Facoltativo / non configurato';
$LANG_MAPS_1['integrations_title'] = 'Integrazioni';
$LANG_MAPS_1['integrations_intro'] = 'Mappe usa API e servizi Geeklog affinché i plugin correlati possano rilevare Mappe senza collegamento diretto al database.';
$LANG_MAPS_1['integration_active'] = 'Attivo';
$LANG_MAPS_1['integration_missing'] = 'Plugin mancante';
$LANG_MAPS_1['integration_native'] = 'Supporto nativo';
$LANG_MAPS_1['integration_xmlsitemap'] = 'Sitemap XML';
$LANG_MAPS_1['integration_documents'] = 'Documenti';
$LANG_MAPS_1['integration_indexnow'] = 'IndexNow';
$LANG_MAPS_1['integration_rss'] = 'Feed RSS / Atom';
$LANG_MAPS_1['use_my_location'] = 'Usa la mia posizione';
$LANG_MAPS_1['geolocation_unavailable'] = 'La geolocalizzazione del browser non è disponibile. Inserisci manualmente un indirizzo di partenza.';
$LANG_MAPS_1['geolocation_denied'] = 'Impossibile ottenere la tua posizione. Consenti l\'accesso alla posizione oppure inserisci manualmente un indirizzo di partenza.';
$LANG_MAPS_1['geolocation_https'] = 'La geolocalizzazione del browser richiede normalmente HTTPS.';
?>
