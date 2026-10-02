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
    'plugin_name'           => 'Karten',
    'plugin_conf'           => 'Plugin-Konfiguration',
    'map'                   => 'Karte',
    'need_google_api'       => 'Es ist kein Google-Maps-API-Schlüssel für den Browser konfiguriert. Google Maps können erst angezeigt werden, wenn dieser Schlüssel hinzugefügt wurde.',
    'api_status_title' => 'Google-Maps-API-Status',
    'api_status_missing' => 'Es ist kein Google-Maps-API-Schlüssel für den Browser konfiguriert.',
    'api_status_testing' => 'Google Maps JavaScript API wird getestet…',
    'api_status_key' => 'Browser-Schlüssel',
    'api_status_ok' => 'Die Google Maps JavaScript API wurde erfolgreich geladen: Der Browser-Schlüssel wird für diese Seite akzeptiert.',
    'api_status_auth' => 'Google Maps hat den Browser-Schlüssel oder dessen Konfiguration abgelehnt. Prüfe in der Browser-Konsole den genauen Google-Fehlercode und kontrollieren Sie anschließend HTTP-Referrer-Beschränkungen, aktivierte APIs und die Google-Cloud-Abrechnung.',
    'api_status_load' => 'Das Skript der Google Maps JavaScript API konnte nicht geladen werden. Prüfe Netzwerk, CSP-Richtlinie, Inhaltsblocker und Browser-Konsole.',
    'api_status_timeout' => 'Die Google Maps JavaScript API hat nicht geantwortet. Prüfe die Browser-Konsole und das Netzwerk-Panel.',
    'admin_help_title'      => 'Erste Schritte mit Karten',
    'admin_help_intro'      => 'Mit Karten kannst du mehrere Karten erstellen, Marker hinzufügen und bei Bedarf eigene Symbole oder Overlays verwenden.',
    'admin_help_google'     => 'Google Maps konfigurieren',
    'admin_help_google_1'   => 'Öffne die Google Cloud Console, erstellen oder wählen Sie ein Projekt und verknüpfen Sie für den Produktivbetrieb ein Abrechnungskonto.',
    'admin_help_google_2'   => 'Aktiviere mindestens die Maps JavaScript API. Aktiviere zusätzlich die Geocoding API, wenn Adressen automatisch in Breiten-/Längengrade umgewandelt werden sollen.',
    'admin_help_google_3'   => 'Erstelle einen API-Schlüssel für die Browser-Nutzung. Beschränke ihn auf autorisierte Websites (HTTP-Referrer), z. B. https://www.example.com/*, und anschließend auf die Maps JavaScript API.',
    'admin_help_google_4'   => 'Für serverseitiges Geocoding wird ein zweiter Schlüssel empfohlen. Beschränke ihn auf die IP-Adresse deines Servers und ausschließlich auf die Geocoding API.',
    'admin_help_google_5'   => 'Kopiere den Browser-Schlüssel in den Google-Maps-API-Schlüssel und gegebenenfalls den Server-Schlüssel in den Google-Maps-Server-API-Schlüssel der Geeklog-Karten-Konfiguration.',
    'admin_help_security'   => 'Lass einen Google-Maps-Schlüssel im Produktivbetrieb niemals uneingeschränkt. Getrennte Browser- und Server-Schlüssel verringern das Risiko unbefugter Nutzung.',
    'admin_help_create'     => 'Erstelle Ihre erste Karte',
    'admin_help_create_1'   => 'Klicke auf Neue Karte erstellen, vergeben Sie einen Namen und wählen Sie Mittelpunkt, Zoomstufe und Darstellungsart.',
    'admin_help_create_2'   => 'Speichere die Karte und fügen Sie anschließend Marker in der Karten-Verwaltung hinzu. Jeder Marker kann eine Adresse oder genaue Koordinaten verwenden.',
    'admin_help_create_3'   => 'Symbole und Overlays sind optional. Beginne mit einer einfachen Karte und einigen Markern, um deine Google-Maps-Konfiguration zu prüfen.',
    'admin_help_trouble'    => 'Wenn eine Karte ausgegraut ist oder „Nur zu Entwicklungszwecken“ anzeigt, prüfen Sie Google-Cloud-Abrechnung, aktivierte APIs und API-Schlüssel-Beschränkungen.',
    'admin_help_official'   => 'Offizielle Dokumentation der Google Maps Platform',
    'admin_help_geo_title'  => 'Was bewirkt „Benutzer-Geolokalisierung prüfen“?',
    'admin_help_geo_intro'  => 'Dieser Befehl durchsucht Mitglieder, die im Profil das Feld Standort ausgefüllt haben, und bereitet Koordinaten für die Benutzerkarte vor.',
    'admin_help_geo_1'      => 'Karten sendet jeden noch nicht aufgelösten Textstandort an die Google Geocoding API, zum Beispiel „Nantes, Frankreich“.',
    'admin_help_geo_2'      => "Die zurückgegebenen Breiten- und Längengrade werden in der Geocoding-Tabelle von Karten zwischengespeichert. Das Geeklog-Profil des Mitglieds wird nicht verändert.",
    'admin_help_geo_3'      => 'Dies ist besonders nach Installation oder Migration nützlich oder wenn viele Mitglieder ihren Standort hinzugefügt oder geändert haben. Erforderlich sind die Geocoding API und ein für serverseitiges Geocoding zugelassener Schlüssel.',
    'admin_help_overlays_title' => 'Wozu dienen Overlays?',
    'admin_help_overlays_intro' => 'Ein Overlay ist ein georeferenziertes Bild, das zwischen Südwest- und Nordost-Koordinaten über die Google-Karte gelegt wird. Es ergänzt visuelle Informationen, die nicht zur Google-Maps-Basisebene gehören.',
    'admin_help_overlays_1' => 'Zeige einen Lageplan für Gelände, Campingplatz, Park, Anwesen, Gebäude oder Festival über der realen Karte an.',
    'admin_help_overlays_2' => 'Überlagere zum Vergleich eine historische, Kataster-, geologische oder touristische Karte oder einen alten Plan.',
    'admin_help_overlays_3' => 'Zeige einen Themenbereich wie eine illustrierte Route, Arbeitszone, Naturfläche, Projektfläche oder andere grafische Informationen.',
    'admin_help_overlays_4' => 'Lege minimale und maximale Zoomstufen fest, damit das Overlay nur dann angezeigt wird, wenn es sinnvoll ist.',
    'admin_help_overlays_how' => 'So erstellen Sie ein Overlay: Bereite ein geeignetes Bild vor, öffnen Sie Overlays, geben Sie die Südwest- und Nordost-Grenzen ein und ordnen Sie es anschließend im Karteneditor über die Registerkarte Overlays einer Karte zu. Overlays sind optional; für eine normale Karte mit Punkten reichen Marker aus.',
    'admin_help_concepts_title' => 'Karten-Konzepte im Überblick',
    'admin_help_concept_map' => 'Karte',
    'admin_help_concept_map_text' => 'Der Hauptcontainer: Mittelpunkt, Zoom, Darstellungsart, Abmessungen, Berechtigungen und allgemeine Optionen.',
    'admin_help_concept_marker' => 'Marker',
    'admin_help_concept_marker_text' => 'Ein geografischer Punkt auf einer Karte mit Name, Beschreibung, Adresse und optionalen Zusatzinformationen.',
    'admin_help_concept_icon' => 'Symbol',
    'admin_help_concept_icon_text' => 'Ein optionales Bild, das den standardmäßigen Google-Marker ersetzt, um Punktkategorien zu unterscheiden.',
    'admin_help_concept_users' => 'Benutzerkarte',
    'admin_help_concept_users_text' => 'Eine Karte, die aus dem Feld Standort des Geeklog-Profils erzeugt wird. Koordinaten werden vom Geocoding-System von Karten aufgelöst und zwischengespeichert.',
    'admin_help_trouble_title' => 'Schnelle Fehlerbehebung',
    'profile_title'         => 'Geolokalisierung',
    'buy_marker'            => 'Marker kaufen',
    'menu_label'            => 'Karten-Verwaltung',
    'admin_home'            => 'Startseite', // In admin menu
    'user_home'             => 'Alle Karten', //In user menu
    'maps'                  => 'Karten',
    'markers'               => 'Marker',
    'maps_label'            => 'Karten', // For user  menu
    'create_map'            => 'Neue Karte erstellen',
    'create_marker'         => 'Neuen Marker erstellen',
    'map_edit'              => 'Karte bearbeiten',
    'marker_edit'           => 'Marker bearbeiten',
    'deletion_succes'       => 'Löschen erfolgreich',
    'deletion_fail'         => 'Löschen fehlgeschlagen',
    'error'                 => 'Fehler',
    'save_fail'             => 'Speichern fehlgeschlagen',
    'save_success'          => 'Speichern erfolgreich',
    'missing_field'         => 'Pflichtfeld fehlt…',
    'geocoder'              => 'Geocoder',
    'geocoder_text'         => 'Gib eine Adresse ein und ziehen Sie anschließend den Marker, um die Position anzupassen. Breiten-/Längengrad werden nach jedem Geocoding bzw. Verschieben im Infofenster angezeigt.',
    'geocode_failed'         => 'Die Adresse konnte nicht geocodiert werden. Prüfe den Google-Maps-API-Schlüssel, die Aktivierung der Geocoding API und die Adresse und versuchen Sie es erneut.',
    'go'                    => 'Los!',
    'name_label'            => 'Kartenname: ',
    'marker_name_label'     => 'Markername: ',
    'description_label'     => 'Beschreibung:',
    'ok_button'             => 'OK',
    'edit_button'           => 'Bearbeiten',
    'save_button'           => 'Speichern',
    'delete_button'         => 'Löschen',
    'yes'                   => 'Ja',
    'no'                    => 'Nein',
    'required_field'        => 'Kennzeichnet ein Pflichtfeld',
    'address_label'         => 'Adresse: ',
    'message'               => 'Nachricht',
    'general_settings'      => 'Allgemeine Einstellungen',
    'map_width'             => 'Kartenbreite (% oder px, mindestens 550 px): ',
    'map_height'             => 'Kartenhöhe (nur px, mindestens 350 px): ',
    'map_zoom'              => 'Kartenzoom (0-21): ',
    'map_type'              => 'Kartentyp: ',
    'active'                => 'Karte ist aktiv: ',
    'hidden'                => 'Karte ist verborgen: ',
    'marker_active'         => 'Marker ist aktiv: ',
    'marker_hidden'         => 'Marker ist verborgen: ',
    'free_marker'           => 'Karte akzeptiert kostenlose Marker: ',
    'paid_marker'           => 'Karte akzeptiert kostenpflichtige Marker: ',
    'error_address_empty'   => 'Bitte geben Sie zuerst eine gültige Adresse ein.',
    'error_invalid_address' => 'Diese Adresse ist ungültig. Stelle sicher, dass du auch Hausnummer und Ort eingeben.',
    'error_google_error'    => 'Bei der Verarbeitung Ihrer Anfrage ist ein Problem aufgetreten. Bitte versuchen Sie es erneut.',
    'error_no_map_info'     => 'Für diese Adresse sind leider keine Karteninformationen verfügbar.',
    'need_directions'       => 'Benötigen Sie eine Wegbeschreibung? Gib deine Adresse ein:',
    'directions_title'     => 'Route planen',
    'directions_start'     => 'Startpunkt',
    'get_directions'        => '  Route berechnen  ',
    'maps_list'             => 'Kartenliste',
    'you_can'               => 'Du kannst ',
    'user_maps_list'        => 'Unsere Karten entdecken',
    'markers_list'          => 'Markerliste',
    'map_markers_heading'   => 'Marker auf dieser Karte',
    'marker_singular'      => 'Marker',
    'marker_plural'        => 'Marker',
    'views_label'          => 'Aufrufe',
    'no_map'                => 'In der Datenbank ist keine Karte vorhanden. Du musst eine erstellen, um Marker hinzuzufügen.',
    'no_map_user'           => 'Ups… In der Datenbank ist keine aktive Karte vorhanden.',
    'value_directions'      => 'z. B. Hausnummer Straße, Ort, Land', // No quote here please
    'id'                    => 'ID',
    'name'                  => 'Name',
    'description'           => 'Beschreibung',
    'active_field'          => 'Aktiv',
    'hidden_field'          => 'Verborgen',
    'marker_count'          => 'Marker',
    'status_active'         => 'Aktiv',
    'status_inactive'       => 'Inaktiv',
    'status_visible'        => 'Sichtbar',
    'status_hidden'         => 'Verborgen',
    'title_display'         => 'Kartenseite anzeigen',
    'map_header_label'      => 'Optionaler Kartenkopf',
    'map_footer_label'      => 'Optionaler Kartenfuß',
    'header_footer'         => 'Kopf- und Fußbereich',
    'informations'          => 'Informationen',
    'must_belong_to'        => 'Um auf diese Karte zuzugreifen, musst du Mitglied folgender Gruppe sein:',
    'private_access'        => 'Privater Zugriff',
    'marker_label'          => 'Marker',
    'primary_color_label'   => 'Primärfarbe',
    'stroke_color_label'    => 'Konturfarbe',
    'label'                 => 'Beschriftung',
    'label_color'           => 'Beschriftungsfarbe',
    'black'                 => 'Schwarz',
    'white'                 => 'Weiß',
    'payed'                 => 'Kostenpflichtiger Marker:',
    'lat'                   => 'Breitengrad:',
    'lng'                   => 'Längengrad:',
    'ressources_tab'        => 'Registerkarte Ressourcen',
    'presentation'          => 'Darstellung',
    'ressources'            => 'Ressourcen',
    'presentation_tab'      => 'Registerkarte Darstellung',
    'empty_ressources'      => 'Die Ressourcenbeschriftungen sind leer. Du musst mindestens eine festlegen, um Ressourcen zu verwenden. Siehe Konfiguration.',
    'empty_for_geo'         => 'Lass Breiten- und Längengrad leer, wenn die Adresse oben automatisch geolokalisiert werden soll.',
    'select_marker_map'     => 'Wähle die Karte, auf der der Marker erscheinen soll.',
    'remark'                => 'Notizen',
    'marker_created'        => 'Marker erstellt am:',
    'map_created'           => 'Karte erstellt am:',
    'modified'              => 'Letzte Änderung:',
    'marker_validity'       => 'Gültigkeitsdatum verwenden:',
    'maps_empty'            => 'Bitte erstellen Sie zuerst eine Karte.',
    'from'                  => 'Von:',
    'to'                    => 'Bis:',
    'date_issue'            => 'Das Ende der Gültigkeit liegt vor dem Beginn. Bitte prüfen Sie die Angaben.',
    'max_char'              => 'maximale Zeichenanzahl.',
    'street_label'          => 'Straße:',
    'code_label'            => 'Postleitzahl:',
    'city_label'            => 'Ort:',
    'state_label'           => 'Bundesland/Region:',
    'country_label'         => 'Land:',
    'tel_label'             => 'Tel.:',
    'fax_label'             => 'Zusätzlicher Kontakt:',
    'web_label'             => 'Web:',
    'not_use_see_config'    => 'Nicht verwenden. Siehe Konfiguration',
    //global maps
    'global_map'            => 'Globale Karte',
    'info_global_map'       => 'Alle Karten in einer Karte.',
    'users_map'             => 'Karte der Website-Benutzer',
    'info_users_map'        => 'Dies ist die Karte der Website-Benutzer. Du kannst sich hinzufügen, indem Sie deinen Standort im Profil eintragen.',
    //Submission
    'address'               => 'Adresse',
    'created'               => 'Datum',
    'submit_marker'         => 'Marker einreichen',
    'submit_marker_text'    => '<p><ol><li>Markerposition festlegen<li>Alle Felder ausfüllen<li>Bestätigen</ol></p>',
    'markers_submissions'   => 'Marker-Einreichungen',
    'submission_disabled'   => 'Einreichungswarteschlange für Marker deaktiviert',
    'go'                    => 'Diese Adresse anzeigen',
    //date and hits
    'last_modification'     => 'Letzte Änderung:',
    'hits'                  => 'Aufrufe',
    //user marker
    'member'                => 'Mitglied',
    'location'              => 'Standort: ',
    'regdate'               => 'Mitglied seit: ',
    'about'                 => 'Über',
    'my_markers'            => 'Meine Marker',
    'payed_label'           => 'Kostenpflichtig',
    'from_label'            => 'Gültig ab',
    'to_label'              => 'Gültig bis',
    'no_marker'             => 'Du hast keine Marker oder sie wurden noch nicht genehmigt. Wenn Sie dies für einen Fehler halten, wende dich an den Website-Administrator.',
    'marker_detail'         => 'Markerdetails',
    'admin_can'             => 'Als Kartenadministrator kannst du',
    'create_map'            => 'Neue Karte erstellen',
    'set_user_geo'          => 'Benutzer-Geodaten setzen',
    'set_geo_location'      => 'Das System prüft und setzt alle Geolokalisierungen.',
    'records'               => 'Datensätze',
    'report'                => 'Diesen Marker melden',
    'report_subject'        => 'Meldung zu Marker ',
    'edit_marker_text'      => '<p><ol><li>Markerposition festlegen<li>Alle Pflichtfelder ausfüllen<li>Dann bestätigen</ol></p>',
    'admin'                 => 'Administration',
    'category_label'        => 'Kategorie:',
    'choose_category'       => '-- Kategorie wählen --',
    'categories'            => 'Kategorien',
    'categories_list'       => 'Kategorieliste',
    'cat_edit'              => 'Kategorie bearbeiten:',
    'cat_name_label'        => 'Kategoriename:',
    'create_cat'            => 'neue Kategorie erstellen',
    'field_list'            => 'Feldliste',
    'addfield'              => 'Feld hinzufügen',
    'field_name'            => 'Feldname',
    'field_order'           => 'Reihenfolge',
    'field_autotag'         => 'Autotag',
    'field_rights'          => 'Berechtigungen',
    'field_edit'            => 'Bearbeiten',
    'valid'                 => 'Gültig',
    'editing_field'         => 'Feld bearbeiten',
    'category'              => 'Kategorie',
    'map_label'             => 'Karte',
    'colon'                 => ':', //Add space before and after if needed
    'view_map'              => 'Karte anzeigen',
    'view_markers'          => 'Markerliste anzeigen',
    'code'                  => 'Postleitzahl',
    'city'                  => 'Ort',
    'viewing_markers'       => 'Liste der Marker anzeigen',
    'details'               => 'Details',
    'view_details'          => 'Details anzeigen',
    'print'                 => 'Drucken',
	'to_complete'           => 'Zu vervollständigen',
	'autotag_desc_maps'     => '[maps: xx zoom:ZZ location] - Zeigt die Karte mit id=XX. Optionen sind die Zoomstufe (0 bis 21) und das Zentrieren der Karte auf location.',
	'autotag_desc_geo'      => '[geo: Paris, France zoom:12] - Zeigt eine Karte, die auf einen Ortsnamen oder eine Adresse zentriert ist. Optionale Parameter: zoom, width und height. Die historische Syntax [geo: map ...] bleibt unterstützt.',
	'autotag_desc_marker'   => '[marker: xx] - Zeigt den Marker mit id=XX',
	//v1.1
	'marker_customisation'  => 'Marker-Anpassung',
	'mk_default'            => 'Standardmarker verwenden',
	'overlays'              => 'Overlays',
	'overlays_list'         => 'Liste der Overlays',
	'create_overlay'        => 'Neues Overlay erstellen',
	'edit_overlay_text'     => 'Overlay bearbeiten:',
	'overlay_edit'          => 'Overlay bearbeiten',
	'overlay_name_label'    => 'Overlay-Name:',
	'overlay_presentation'  => 'Overlays sind Objekte auf der Karte, die an Breiten-/Längengrad-Koordinaten gebunden sind und sich beim Verschieben oder Zoomen der Karte mitbewegen. Sie kennzeichnen Punkte, Linien oder Flächen. Hier kannst du ein Bild als Overlay hinzufügen.',
	'overlay_active'        => 'Dieses Overlay ist aktiv:',
	'zoom_min_label'        => 'Minimaler Zoom:',
	'zoom_max_label'        => 'Maximaler Zoom:',
	'image_message'         => 'Wähle ein Bild von deiner Festplatte.',
	'image_replace'         => 'Beim Hochladen eines neuen Bildes wird dieses ersetzt:',
	'image'                 => 'Bild',
	'sw_lat'                => 'SW-Breitengrad:',
    'sw_lng'                => 'SW-Längengrad:',
	'ne_lat'                => 'NO-Breitengrad:',
    'ne_lng'                => 'NO-Längengrad:',
	'overlay_not_writable'  => 'Der Overlay-Ordner ist nicht beschreibbar. Erstelle den Ordner und machen Sie ihn beschreibbar, bevor Sie diese Funktion verwenden.',
	'map_tab'               => 'Karte',
	'overlays_tab'          => 'Overlays',
	'add_overlay'           => 'Overlay hinzufügen',
	'remove_overlay'        => 'Overlay entfernen',
	'overlay_label'         => 'Overlay',
	'import_export'         => 'Import/Export',
	'import'                => 'Importieren',
	'export'                => 'Exportieren',
	'select_file'           => 'CSV-Datei auswählen',
	'import_message'        => 'Wähle die Karte, der Marker hinzugefügt werden sollen, die CSV-Datei von deiner Festplatte, das Datentrennzeichen und die zu importierenden Felder.',
	'markers_added'         => 'Zur Karte hinzugefügte Marker:',
	'export_message'        => 'Wähle die Karte, deren Marker exportiert werden sollen, das Datentrennzeichen und die zu exportierenden Felder.',
	'no_marker_to_export'   => 'Auf dieser Karte gibt es leider keine Marker zum Exportieren.',
	'icons'                 => 'Symbole',
	'icons_not_writable'    => 'Der Symbolordner ist nicht beschreibbar. Erstelle den Ordner und machen Sie ihn beschreibbar, bevor Sie diese Funktion verwenden.',
	'icons_list'            => 'Symbolliste',
	'create_icon'           => 'Neues Symbol erstellen',
	'icon_edit'             => 'Symbol bearbeiten',
	'icon_presentation'     => 'Hier kannst du ein neues Symbol für Marker hochladen', 
	'icon_name_label'       => 'Symbolname',
	'xmarkers'              => 'Marker',
	'1marker'               => 'Marker',
	'choose_icon'           => 'Du kannst für diesen Marker ein Symbol auswählen. Prioritätssymbole stehen über den Farben.',
	'no_icon'               => 'Kein Symbol',
	'no_custom_icons'        => 'Noch kein eigenes Symbol registriert.',
	'manage_icons'           => 'Symbole verwalten',
	'separator'             => 'Trennzeichen wählen',
	'markers_to_add'        => 'Prüfe alle Feld-/Wert-Paare und bestätigen Sie, dass du alle folgenden Marker zur Karte hinzufügen möchten:',
	'choose_fields_import'  => 'Importfelder wählen',
	'choose_fields_export'  => 'Exportfelder wählen',
	'checkall'              => 'Alle auswählen',
    'import_step_1' => 'Import vorbereiten',
    'import_step_1_text' => 'Wähle Zielkarte, CSV-Datei, Trennzeichen und Spaltenreihenfolge.',
    'import_step_2' => 'Daten prüfen',
    'import_step_2_text' => 'Karten prüft, normalisiert und geocodiert die Zeilen, bevor Daten geschrieben werden.',
    'import_step_3' => 'Import bestätigen',
    'import_step_3_text' => 'Prüfe Ziel, Eigentümer und Berechtigungen und bestätigen Sie anschließend den Stapel.',
    'import_minimum' => 'Mindestfelder',
    'import_minimum_help' => 'name + address oder name + lat + lng. Die Voreinstellung Minimum verwendet die adressbasierte Option.',
    'import_recommended' => 'Empfohlene Felder',
    'import_recommended_help' => 'name, address, lat, lng, description, street, code, city, state, country, tel und web.',
    'import_order_help' => 'Die CSV-Spalten müssen der gleichen Reihenfolge wie die unten ausgewählten Felder entsprechen.',
    'import_select_minimum' => 'Mindestfelder',
    'import_select_recommended' => 'Empfohlene Felder',
    'import_clear_fields' => 'Auswahl löschen',
    'import_preview_title' => 'Daten prüfen',
    'import_preview_text' => 'Dies sind die normalisierten Werte, die bei Bestätigung des Imports geschrieben werden.',
    'import_summary_rows' => 'Zeilen bereit',
    'import_summary_coordinates' => 'Koordinaten vorhanden',
    'import_summary_geocoded' => 'automatisch geocodiert',
    'import_summary_partial' => 'mit unvollständigen Adressangaben',
    'import_status' => 'Status',
    'import_status_ready' => 'Bereit',
    'import_status_partial' => 'Bereit · unvollständige Angaben',
    'import_status_geocoded' => 'geocodiert',
    'import_confirm_title' => 'Import bestätigen',
    'import_confirm_text' => 'Prüfe die Stapeleinstellungen, bevor die Marker erstellt werden.',
    'import_confirm_button' => '%d Marker importieren',
    'import_cancel_button' => 'Abbrechen',
	'order'                 => 'Reihenfolge',
	'move'                  => 'Verschieben',
	'name_missing'          => 'Mindestens ein Name fehlt. Prüfe deine CSV-Datei.',
	'need_address'          => 'Für einen Marker wird mindestens eine Adresse oder Koordinaten benötigt. Prüfe deine CSV-Datei; eine Angabe fehlt.',
	'manage_groups'         => 'Overlay-Gruppen verwalten',
	'create_group'          => 'Neue Overlay-Gruppe erstellen',
	'group_edit'            => 'Overlay-Gruppe bearbeiten',
	'group_overlay_presentation' => 'Hier kannst du den Namen Ihrer Overlay-Gruppe wählen oder bearbeiten',
	'group_overlay_name_label'   => 'Name der Gruppe',
	'group_label'           => 'Gruppe (optional)',
	'choose_group'          => 'Gruppe auswählen',
	'group'                 => 'Gruppe',
	
	//v1.3
	'geo_fail'              => 'Die eingegebene Adresse scheint ungültig zu sein',
	'on_map'                => 'Auf der Karte',
	'read_more'             => 'Mehr lesen',
	'from_map'              => 'Karte:',
	'show_hide_overlays'    => 'Overlays anzeigen / ausblenden',
	'fields_presentation'   => 'Bearbeiten Sie eine bestehende Kategorie, um ein Feld hinzuzufügen oder zu bearbeiten.',
	'overlays_added'        => 'Overlays auf dieser Karte',
	'overlays_to_add'       => 'Overlays, die Sie dieser Karte hinzufügen können',
	'marker_modification'   => 'Marker-Änderung',
	'from_owner'            => 'Hinzugefügt von:',
	'marker_limited'        => 'Der Zugriff auf diesen Marker ist leider eingeschränkt…',
	'events_map'            => 'Karte der nächsten Veranstaltungen',
	'info_events_map'       => '',
	'from_cal'              => 'Von',
	'to_cal'                => 'bis',
	'on_cal'                => 'Ein',
    //v1.4
    'admin_menu_maps' => 'Karten',
    'admin_menu_markers' => 'Marker',
    'admin_menu_icons' => 'Symbole',
    'admin_menu_overlays' => 'Overlays',
    'admin_menu_import_export' => 'Import/Export',
    'admin_menu_geocoder' => 'Geocoder',
    'admin_menu_geolocation' => 'Geolokalisierung',
    'admin_menu_configuration' => 'Konfiguration',
    'section_location' => 'Standort',
    'section_content_contact' => 'Inhalt und Kontakt',
    'section_appearance' => 'Darstellung',
    'section_publication' => 'Veröffentlichung',
    'section_resources' => 'Ressourcen',
    'section_ownership' => 'Eigentümer',
    'section_permissions' => 'Berechtigungen',
    'delete_confirm' => 'Diesen Marker dauerhaft löschen?',
    'marker_not_found' => 'Marker nicht gefunden oder Du hast keine Berechtigung, ihn anzuzeigen.',
    'delete_map_confirm' => 'Diese Karte dauerhaft löschen?',
    'technical_coordinates' => 'Technische Koordinaten',
    'configuration'         => 'Konfiguration',
    // Maps 1.5.6 map editor
    'map_section_display' => 'Anzeige',
    'map_section_center' => 'Mittelpunkt und Zoom',
    'map_section_markers' => 'Marker',
    'map_section_advanced' => 'Erweiterte Optionen',
    'map_center_search' => 'Adresse suchen',
    'map_center_search_button' => 'Lokalisieren',
    'map_center_use_button' => 'Angezeigten Mittelpunkt verwenden',
    'map_center_help' => 'Suche eine Adresse, klicken Sie auf die Karte oder ziehen Sie den Marker, um den Kartenmittelpunkt genau festzulegen.',
    'latitude_label' => 'Breitengrad',
    'longitude_label' => 'Längengrad',
    'map_center_marker' => 'Kartenmittelpunkt',

);

$LANG_MAPS_MESSAGE = array(
    'message'               => 'Systemnachricht',
    'add_new_field'         => 'Dein neues Feld wurde erfolgreich erstellt',
    'save_field'            => 'Dein Feld wurde erfolgreich gespeichert',
    'delete_field'          => 'Dein Feld wurde erfolgreich gelöscht'
);

$LANG_MAPS_EMAIL = array(
    'hello_admin'           => 'Hallo Administrator,',
    'new_marker'            => 'Ein neuer Marker wartet auf Freigabe.',
    'name'                  => 'Name:',
    'on_map'                => 'Auf Karte:',
    'submissions'           => 'Einreichungen: ',
    'marker_submissions'    => 'Marker-Einreichungen',
	'marker_modification'   => 'Marker-Änderung',
	'description'           => 'Beschreibung:',
);

// Messages for the plugin upgrade
$PLG_maps_MESSAGE3002 = $LANG32[9]; // "requires a newer version of Geeklog"

$PLG_maps_MESSAGE1  = "Vielen Dank für das Einreichen eines Markers bei {$_CONF['site_name']}. Er wurde unserem Team zur Freigabe übermittelt.";
$PLG_maps_MESSAGE2  = "Die Marker-Einreichung ist geschlossen.";
$PLG_maps_MESSAGE3  = "Ups… Es ist ein Fehler aufgetreten. Der Marker konnte nicht gespeichert werden.";

/**
*   Localization of the Admin Configuration UI
*   @global array $LANG_configsections['maps']
*/
$LANG_configsections['maps'] = array(
    'label' => 'Karten',
    'title' => 'Karten-Konfiguration'
);

/**
*   Configuration system prompt strings
*   @global array $LANG_confignames['maps']
*/
$LANG_confignames['maps'] = array(
    'hide_maps_menu'        => 'Karten-Menü ausblenden',
    'maps_login_required'   => 'Anmeldung für Karten erforderlich',
    'autofill_coord'        => 'Nicht definierte Koordinaten automatisch ausfüllen',
    'display_geo_profile'   => 'Profil-Geolokalisierung',
    'map_type_profile'      => 'Kartentyp im Profil',
    'map_type_geotag'       => 'Kartentyp des Geo-Autotags',
    'show_directions_geo'   => 'Wegbeschreibung im Geo-Autotag anzeigen',
    'show_directions_profile' => 'Wegbeschreibung im Profil anzeigen',
    'map_width_geotag'      => 'Kartenbreite des Geo-Autotags (mit % oder px)',
    'map_height_geotag'     => 'Kartenhöhe des Geo-Autotags (nur px)',
    'map_zoom_geotag'       => 'Zoom des Geo-Autotags (0-21)',
    'map_width_profile'     => 'Kartenbreite im Profil (mit % oder px)',
    'map_height_profile'    => 'Kartenhöhe im Profil (nur px)',
    'show_map'              => 'Google-Karte anzeigen',
    'google_api_key'        => 'Google-Maps-API-Schlüssel',
    'url_geocode'           => 'URL des Google-Geocoding-Dienstes',
    'map_width'             => 'Standardbreite der Karten (mit % oder px)',
    'map_height'            => 'Standardhöhe der Karten (nur px)',
    'map_zoom'              => 'Standardzoom der Karten (0-21)',
    'map_type'              => 'Standard-Kartentyp',
    'default_permissions'   => 'Standardberechtigungen',
    'map_main_header'       => 'Kopfbereich der Hauptseite, Autotag welcome',
    'map_main_footer'       => 'Fußbereich der Hauptseite, ebenfalls Autotag welcome',
    'map_geo'               => 'Karte mit allen Profilen erstellen',
    'map_markers'           => 'Karte mit allen Markern erstellen',
    'map_active'            => 'Karte ist aktiv',
    'map_hidden'            => 'Karte ist verborgen',
    'free_markers'          => 'Karte akzeptiert kostenlose Marker',
    'paid_markers'          => 'Karte akzeptiert kostenpflichtige Marker (PayPal-Plugin erforderlich)',
    'street'                => 'Straßenangabe verwenden',
    'code'                  => 'Postleitzahl verwenden',
    'city'                  => 'Ort verwenden',
    'state'                 => 'Bundesland/Region verwenden',
    'country'               => 'Land verwenden',
    'tel'                   => 'Telefon verwenden',
    'fax'                   => 'Zusätzlichen Kontakt verwenden',
    'web'                   => 'Web-Angabe verwenden',
    'item_1'                => 'Beschriftung benutzerdefiniertes Feld 1',
    'item_2'                => 'Beschriftung benutzerdefiniertes Feld 2',
    'item_3'                => 'Beschriftung benutzerdefiniertes Feld 3',
    'item_4'                => 'Beschriftung benutzerdefiniertes Feld 4',
    'item_5'                => 'Beschriftung benutzerdefiniertes Feld 5',
    'item_6'                => 'Beschriftung benutzerdefiniertes Feld 6',
    'item_7'                => 'Beschriftung benutzerdefiniertes Feld 7',
    'item_8'                => 'Beschriftung benutzerdefiniertes Feld 8',
    'item_9'                => 'Beschriftung benutzerdefiniertes Feld 9',
    'item_10'               => 'Beschriftung benutzerdefiniertes Feld 10',
    'label_color'           => 'Beschriftungsfarbe',
    'star_primary_color'    => 'Primärfarbe Stern',
    'star_stroke_color'     => 'Konturfarbe Stern',
    'marker_active'         => 'Marker ist standardmäßig aktiv',
    'marker_hidden'         => 'Marker ist standardmäßig verborgen',
    'marker_payed'          => 'Marker standardmäßig kostenpflichtig',
    'marker_validity'       => 'Standardgültigkeit des Markers',
    'marker_submission'     => 'Marker-Einreichungen erlauben',
    'users_map'             => 'Aktive Karte der Website-Benutzer',
    'global_map' 	        => 'Aktive globale Karte',
    'global_type'           => 'Globaler Kartentyp',	
    'global_width'  	    => 'Breite der globalen Karte',
    'global_height' 	    => 'Höhe der globalen Karte',
    'global_zoom'           => 'Zoom der globalen Karte (0-21)',
    'detail_zoom'           => 'Zoom der Markerdetails (0-21)',
    'submit_login_required' => 'Anmeldung für Marker-Einreichungen erforderlich',
    'marker_edition'        => 'Marker-Bearbeitung',
	'use_cluster'           => 'Marker-Cluster verwenden',
	'zoom_profile'          => 'Zoom der Karte im Benutzerprofil (0-21)',
	'display_events_map'    => 'Veranstaltungskarte anzeigen',
);

/**
*   Configuration system subgroup strings
*   @global array $LANG_configsubgroups['maps']
*/
$LANG_configsubgroups['maps'] = array(
    'sg_main' => 'Haupteinstellungen',
    'sg_display' => 'Anzeigeeinstellungen'
);

/**
*   Configuration system tab names
*   @global array $LANG_configtabs['maps']
*/
$LANG_configtabs['maps'] = array(
    'tab_general' => 'Allgemein',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'Karten',
    'tab_markers' => 'Marker',
    'tab_fields' => 'Markerfelder',
);

/** Geeklog configuration tab labels (used by config::_UI_get_tab). */
$LANG_tab['maps'] = array(
    'tab_general' => 'Allgemein',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'Karten',
    'tab_markers' => 'Marker',
    'tab_fields' => 'Markerfelder',
);

/**
*   Configuration system fieldset names
*   @global array $LANG_fs['maps']
*/
$LANG_fs['maps'] = array(
    'fs_main'            => 'Allgemeine Einstellungen',
    'fs_ads'             => 'Google-Ads-Einstellungen',
    'fs_google'          => 'Google-API-Einstellungen',
    'fs_permissions'     => 'Standardberechtigungen',
    'fs_display'         => 'Karten',
    'fs_global_map'      => 'Globale Karten',
    'fs_display_profile' => 'Profil',
    'fs_display_geo'     => 'Geo-Autotag',
    'fs_map_default'     => 'Standard-Karteneinstellungen',
    'fs_marker_default'  => 'Standard-Markereinstellungen',
 );

/**
*   Configuration system selection strings
*   Note: entries 0, 1, and 12 are the same as in 
*   $LANG_configselects['Core']
*
*   @global array $LANG_configselects['maps']
*/
$LANG_configselects['maps'] = array(
    0 => array('Wahr' => 1, 'Falsch' => 0),
    1 => array('Wahr' => TRUE, 'Falsch' => FALSE),
    3 => array('Ja' => 1, 'Nein' => 0),
    4 => array('Ein' => 1, 'Aus' => 0),
    5 => array('Seitenanfang' => 1, 'Unter hervorgehobenem Artikel' => 2, 'Seitenende' => 3),
    10 => array('5' => 5, '10' => 10, '25' => 25, '50' => 50),
    11 => array('Meilen' => 'Meilen', 'Kilometer' => 'km'),
    12 => array('Kein Zugriff' => 0, 'Nur Lesen' => 2, 'Lesen/Schreiben' => 3),
	// changed in v1.3
    20 => array('Normale Straßenkarte' => 'ROADMAP', 'Satellitenbilder' => 'SATELLITE', 'Geländekarte' => 'TERRAIN', 'Transparente Ebene wichtiger Straßen über Satellitenbildern' => 'HYBRID'),
    30 => array('Weiß' => 1, 'Schwarz' => 0),
    31 => array('Temporär' => 1, 'Dauerhaft' => 0),
);

$LANG_MAPS_1['location_search_label'] = 'Nach Adresse suchen';
$LANG_MAPS_1['location_search_help'] = 'Suche nach einer Adresse, klicken Sie auf die Karte oder ziehen Sie den Marker, um seine Position genau anzupassen.';
$LANG_MAPS_1['use_map_click_help'] = 'Klicke auf die Karte, um den Marker zu verschieben.';

/* Maps 1.5.7 configuration labels. */
$LANG_configsubgroups['maps']['sg_main'] = 'Hauptbereich';
$LANG_configtabs['maps']['tab_general'] = 'Allgemein';
$LANG_tab['maps']['tab_general'] = 'Allgemein';
$LANG_configtabs['maps']['tab_google'] = 'Google Maps';
$LANG_tab['maps']['tab_google'] = 'Google Maps';
$LANG_configtabs['maps']['tab_maps'] = 'Karten';
$LANG_tab['maps']['tab_maps'] = 'Karten';
$LANG_configtabs['maps']['tab_markers'] = 'Marker';
$LANG_tab['maps']['tab_markers'] = 'Marker';
$LANG_configtabs['maps']['tab_fields'] = 'Markerfelder';
$LANG_tab['maps']['tab_fields'] = 'Markerfelder';

$LANG_fs['maps']['fs_main'] = 'Zugriff und Funktionen';
$LANG_fs['maps']['fs_permissions'] = 'Standardberechtigungen';
$LANG_fs['maps']['fs_uploads'] = 'Bilder und Uploads';
$LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
$LANG_fs['maps']['fs_display'] = 'Allgemeine Anzeige';
$LANG_fs['maps']['fs_global_map'] = 'Globale und Benutzerkarte';
$LANG_fs['maps']['fs_display_profile'] = 'Benutzerprofilkarte';
$LANG_fs['maps']['fs_display_geo'] = 'Geo-Autotag';
$LANG_fs['maps']['fs_map_defaults'] = 'Voreinstellungen für neue Karten';
$LANG_fs['maps']['fs_events_map'] = 'Veranstaltungskarte';
$LANG_fs['maps']['fs_marker_defaults'] = 'Marker-Voreinstellungen';
$LANG_fs['maps']['fs_marker_editor'] = 'Karte im Marker-Editor';
$LANG_fs['maps']['fs_marker_detail'] = 'Karte der Markerdetails';
$LANG_fs['maps']['fs_marker_popup'] = 'Marker-Infofenster';
$LANG_fs['maps']['fs_marker_fields'] = 'Markerfelder und Beschriftungen';

$LANG_confignames['maps']['max_image_width'] = 'Maximale Bildbreite (px)';
$LANG_confignames['maps']['max_image_height'] = 'Maximale Bildhöhe (px)';
$LANG_confignames['maps']['max_image_size'] = 'Maximale Bildgröße (Byte)';
$LANG_confignames['maps']['google_api_key'] = 'Google-Maps-Browser-API-Schlüssel';
$LANG_confignames['maps']['google_server_api_key'] = 'Google-Geocoding-Server-API-Schlüssel';
$LANG_confignames['maps']['google_map_id'] = 'Google Map ID (Vorbereitung für Advanced Markers)';
$LANG_confignames['maps']['google_language'] = 'Google-Maps-Sprache (optional, z. B. de)';
$LANG_confignames['maps']['google_region'] = 'Google-Maps-Region (optional, z. B. DE)';
$LANG_confignames['maps']['url_geocode'] = 'URL des Google-Geocoding-Dienstes';
$LANG_confignames['maps']['map_primary_color'] = 'Standard-Primärfarbe der Karte';
$LANG_confignames['maps']['map_stroke_color'] = 'Standard-Konturfarbe der Karte';
$LANG_confignames['maps']['map_label'] = 'Standardbeschriftung des Kartenmarkers';
$LANG_confignames['maps']['map_label_color'] = 'Standardfarbe der Kartenbeschriftung';
$LANG_confignames['maps']['events_map_zoom'] = 'Zoom der Veranstaltungskarte';
$LANG_confignames['maps']['events_map_height'] = 'Höhe der Veranstaltungskarte';
$LANG_confignames['maps']['users_map_lat'] = 'Breitengrad des Benutzerkarten-Mittelpunkts (leer = automatisch)';
$LANG_confignames['maps']['users_map_lng'] = 'Längengrad des Benutzerkarten-Mittelpunkts (leer = automatisch)';
$LANG_confignames['maps']['users_map_zoom'] = 'Zoom der Benutzerkarte (leer = globale Karte)';
$LANG_confignames['maps']['users_map_type'] = 'Typ der Benutzerkarte (leer = globale Karte)';
$LANG_confignames['maps']['users_map_width'] = 'Breite der Benutzerkarte (leer = globale Karte)';
$LANG_confignames['maps']['users_map_height'] = 'Höhe der Benutzerkarte (leer = globale Karte)';
$LANG_confignames['maps']['marker_editor_type'] = 'Kartentyp im Marker-Editor';
$LANG_confignames['maps']['marker_editor_zoom'] = 'Anfangszoom im Marker-Editor';
$LANG_confignames['maps']['marker_editor_width'] = 'Kartenbreite im Marker-Editor';
$LANG_confignames['maps']['marker_editor_height'] = 'Kartenhöhe im Marker-Editor';
$LANG_confignames['maps']['detail_width'] = 'Breite der Markerdetail-Karte';
$LANG_confignames['maps']['detail_height'] = 'Höhe der Markerdetail-Karte';
$LANG_confignames['maps']['detail_zoom'] = 'Zoom der Markerdetail-Karte';
$LANG_confignames['maps']['popup_width'] = 'Breite des Infofensters';
$LANG_confignames['maps']['popup_height'] = 'Höhe des Infofensters';

/* Maps 1.5.10 landing-page SEO configuration. */
$LANG_fs['maps']['fs_seo'] = 'SEO der Einstiegsseite';
$LANG_confignames['maps']['maps_page_title'] = 'SEO-Titel der Karten-Einstiegsseite';
$LANG_confignames['maps']['maps_page_h1'] = 'H1-Überschrift der Karten-Einstiegsseite';
$LANG_confignames['maps']['maps_meta_description'] = 'Meta-Beschreibung der Karten-Einstiegsseite';
$LANG_confignames['maps']['map_main_header'] = 'Einleitungsinhalt für die Karten-Einstiegsseite (Autotags unterstützt)';

$LANG_MAPS_1['server_geocode_key_missing'] = 'Die serverseitige Koordinatensuche ist aktiviert, aber es ist kein eigener Google-Geocoding-Server-API-Schlüssel konfiguriert. Der Browser-Schlüssel wird niemals für serverseitiges Geocoding verwendet.';
$LANG_MAPS_1['api_diag_title'] = 'Konfiguration der Google Maps Platform';
$LANG_MAPS_1['api_diag_maps_js'] = 'Maps JavaScript API';
$LANG_MAPS_1['api_diag_geocoding'] = 'Geocoding API';
$LANG_MAPS_1['api_diag_directions'] = 'Directions API';
$LANG_MAPS_1['api_diag_browser_key'] = 'Browser-API-Schlüssel';
$LANG_MAPS_1['api_diag_server_key'] = 'Server-API-Schlüssel';
$LANG_MAPS_1['api_diag_map_id'] = 'Map ID';
$LANG_MAPS_1['api_diag_configured'] = 'Schlüssel konfiguriert — API nicht geprüft';
$LANG_MAPS_1['api_diag_browser_verify'] = 'Schlüssel konfiguriert — mit dem Browser-Test unten prüfen';
$LANG_MAPS_1['api_diag_referrer_hint'] = 'Autorisiere für HTTP-Referrer-Beschränkungen des Browser-Schlüssels diese Website (zum Beispiel: %s/*).';
$LANG_MAPS_1['api_diag_missing'] = 'Fehlt';
$LANG_MAPS_1['api_diag_optional'] = 'Optional / nicht konfiguriert';
$LANG_MAPS_1['integrations_title'] = 'Integrationen';
$LANG_MAPS_1['integrations_intro'] = 'Karten verwendet Geeklog-APIs und -Dienste, damit verwandte Plugins Karten ohne direkte Datenbankkopplung erkennen können.';
$LANG_MAPS_1['integration_active'] = 'Aktiv';
$LANG_MAPS_1['integration_missing'] = 'Plugin fehlt';
$LANG_MAPS_1['integration_native'] = 'Native Unterstützung';
$LANG_MAPS_1['integration_xmlsitemap'] = 'XML-Sitemap';
$LANG_MAPS_1['integration_documents'] = 'Dokumente';
$LANG_MAPS_1['integration_indexnow'] = 'IndexNow';
$LANG_MAPS_1['integration_rss'] = 'RSS-/Atom-Feeds';
$LANG_MAPS_1['use_my_location'] = 'Meinen Standort verwenden';
$LANG_MAPS_1['geolocation_unavailable'] = 'Die Browser-Geolokalisierung ist nicht verfügbar. Gib eine Startadresse manuell ein.';
$LANG_MAPS_1['geolocation_denied'] = 'Deine Position konnte nicht ermittelt werden. Erlaube den Standortzugriff oder geben Sie eine Startadresse manuell ein.';
$LANG_MAPS_1['geolocation_https'] = 'Browser-Geolokalisierung erfordert normalerweise HTTPS.';
?>
