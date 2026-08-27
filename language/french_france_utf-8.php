<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.6.0                                                           |
// +---------------------------------------------------------------------------+
// | english.php                                                               |
// |                                                                           |
// | English language file                                                     |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2010-2026_2014 by the following authors:                         |
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
    'plugin_name'           => 'Cartes',
    'plugin_conf'           => 'Configuration du plugin',
    'map'                   => 'carte',
    'need_google_api'       => 'Aucune clé API Google Maps pour navigateur n\'est configurée. Les cartes Google ne pourront pas être affichées tant que cette clé n\'aura pas été ajoutée.',
    'api_status_title' => 'État Google Maps API',
    'api_status_missing' => 'Aucune clé API Google Maps navigateur n’est configurée.',
    'api_status_testing' => 'Test de l’API Google Maps JavaScript en cours…',
    'api_status_key' => 'Clé navigateur',
    'api_status_ok' => 'API Google Maps JavaScript chargée correctement : la clé navigateur est acceptée pour cette page.',
    'api_status_auth' => 'Google Maps refuse la clé navigateur ou sa configuration. Vérifiez la console du navigateur pour le code d’erreur Google exact, puis contrôlez les restrictions de référent, les API activées et la facturation Google Cloud.',
    'api_status_load' => 'Le script Google Maps JavaScript API n’a pas pu être chargé. Vérifiez le réseau, la politique CSP, un éventuel bloqueur de contenu et la console du navigateur.',
    'api_status_timeout' => 'L’API Google Maps JavaScript ne répond pas. Vérifiez la console et l’onglet Réseau du navigateur.',
    'admin_help_title'      => 'Prise en main de Maps',
    'admin_help_intro'      => 'Maps permet de créer plusieurs cartes, d\'y ajouter des marqueurs et, si nécessaire, des icônes ou des overlays personnalisés.',
    'admin_help_google'     => 'Configurer Google Maps',
    'admin_help_google_1'   => 'Ouvrez Google Cloud Console, créez ou sélectionnez un projet et associez-lui un compte de facturation pour un usage en production.',
    'admin_help_google_2'   => 'Activez au minimum Maps JavaScript API. Activez également Geocoding API si vous utilisez la conversion automatique des adresses en latitude/longitude.',
    'admin_help_google_3'   => 'Créez une clé API destinée au navigateur. Restreignez-la aux sites Web (référents HTTP) autorisés, par exemple https://www.example.com/*, puis limitez cette clé à Maps JavaScript API.',
    'admin_help_google_4'   => 'Pour le géocodage côté serveur, il est recommandé de créer une seconde clé. Restreignez-la à l\'adresse IP de votre serveur et uniquement à Geocoding API.',
    'admin_help_google_5'   => 'Copiez ensuite la clé navigateur dans Google Maps API key et, si vous en utilisez une, la clé serveur dans Google Maps server API key dans la configuration Maps de Geeklog.',
    'admin_help_security'   => 'Ne laissez jamais une clé Google Maps sans restrictions en production. Une clé navigateur et une clé serveur séparées limitent les risques d\'utilisation abusive.',
    'admin_help_create'     => 'Créer votre première carte',
    'admin_help_create_1'   => 'Cliquez sur Créer une nouvelle carte, donnez-lui un nom et choisissez son centre, son niveau de zoom et son type d\'affichage.',
    'admin_help_create_2'   => 'Enregistrez la carte, puis ajoutez des marqueurs depuis l\'administration Maps. Chaque marqueur peut utiliser une adresse ou des coordonnées précises.',
    'admin_help_create_3'   => 'Les icônes et overlays sont facultatifs. Commencez par une carte simple et quelques marqueurs afin de valider votre configuration Google Maps.',
    'admin_help_trouble'    => 'Si une carte reste grisée ou affiche « For development purposes only », vérifiez la facturation Google Cloud, les API activées et les restrictions appliquées à la clé.',
    'admin_help_official'   => 'Documentation officielle Google Maps Platform',
    'admin_help_geo_title'  => 'À quoi sert « Vérifier la géolocalisation des membres » ?',
    'admin_help_geo_intro'  => 'Cette commande parcourt les membres qui ont renseigné le champ Localisation de leur profil et prépare leurs coordonnées pour la carte des membres.',
    'admin_help_geo_1'      => 'Maps envoie chaque localisation textuelle non encore résolue à Google Geocoding API, par exemple « Nantes, France ».',
    'admin_help_geo_2'      => "Les latitude et longitude obtenues sont mises en cache dans la table Maps de géocodage. Le profil Geeklog du membre n'est pas modifié.",
    'admin_help_geo_3'      => 'Cette opération est surtout utile après une installation, une migration ou lorsque de nombreux membres ont ajouté ou modifié leur localisation. Elle nécessite Geocoding API et une clé autorisée pour le géocodage côté serveur.',
    'admin_help_overlays_title' => 'À quoi servent les overlays (calques) ?',
    'admin_help_overlays_intro' => "Un overlay est une image géoréférencée placée par-dessus la carte Google, entre deux coordonnées sud-ouest et nord-est. Il permet d'ajouter une information visuelle qui n'existe pas dans le fond Google Maps.",
    'admin_help_overlays_1' => 'Afficher un plan de site, de camping, de parc, de domaine, de bâtiment ou de festival sur la carte réelle.',
    'admin_help_overlays_2' => 'Superposer une carte historique, cadastrale, géologique, touristique ou un ancien plan pour comparaison.',
    'admin_help_overlays_3' => "Présenter une zone thématique : itinéraire illustré, secteur de travail, zone naturelle, emprise d'un projet ou autre information graphique.",
    'admin_help_overlays_4' => "Définir des niveaux de zoom minimum et maximum afin que le calque ne s'affiche que lorsqu'il est pertinent.",
    'admin_help_overlays_how' => "Pour en créer un : préparez une image adaptée, ouvrez Calques, indiquez ses limites sud-ouest et nord-est, puis associez le calque à une carte depuis l'onglet Calques de l'éditeur de carte. Les overlays sont facultatifs : pour une carte classique avec des points, les marqueurs suffisent.",
    'admin_help_concepts_title' => 'Les éléments de Maps en bref',
    'admin_help_concept_map' => 'Carte',
    'admin_help_concept_map_text' => "Le conteneur principal : centre, zoom, type d'affichage, dimensions, permissions et options générales.",
    'admin_help_concept_marker' => 'Marqueur',
    'admin_help_concept_marker_text' => 'Un point géographique placé sur une carte, avec un nom, une description, une adresse et éventuellement des informations complémentaires.',
    'admin_help_concept_icon' => 'Icône',
    'admin_help_concept_icon_text' => 'Une image facultative qui remplace le marqueur Google standard pour distinguer certaines catégories de points.',
    'admin_help_concept_users' => 'Carte des membres',
    'admin_help_concept_users_text' => 'Une carte générée à partir du champ Localisation des profils Geeklog. Les coordonnées sont obtenues et mises en cache par le système de géocodage Maps.',
    'admin_help_trouble_title' => 'Dépannage rapide',
    'profile_title'         => 'Géolocalisation',
    'buy_marker'            => 'Acheter un  marqueur',
    'menu_label'            => 'Administration du plugin Maps',
    'admin_home'            => 'Home', // In admin menu
    'user_home'             => 'Toutes les cartes', //In user menu
    'maps'                  => 'Les cartes',
    'markers'               => 'Les marqueurs',
    'maps_label'            => 'Les cartes', // For user  menu
    'create_map'            => 'créer une nouvelle carte',
    'create_marker'         => 'créer un nouveau marqueur',
    'map_edit'              => 'Edition de la carte :',
    'marker_edit'           => 'Edition du marqueur :',
    'deletion_succes'       => 'Suppression réussie',
    'deletion_fail'         => 'Suppression impossible',
    'error'                 => 'Erreur',
    'save_fail'             => 'Sauvegarde impossible',
    'save_success'          => 'Sauvegarde réussie',
    'missing_field'         => 'Il manque un champ requis...',
    'geocoder'              => 'Géocoder',
    'geocoder_text'         => 'Saisir une adresse, valider puis déplacer le marqueur si nécessaire. La latitude et la longitude apparaîtront dans la fenêtre d\'information.',
    'geocode_failed'         => 'L’adresse n’a pas pu être géocodée. Vérifiez la clé Google Maps, l’activation de l’API Geocoding et l’adresse, puis réessayez.',
    'go'                    => 'Go!',
    'name_label'            => 'Nom de la carte : ',
    'marker_name_label'     => 'Nom du marqueur : ',
    'description_label'     => 'Description :',
    'ok_button'             => 'Ok',
    'edit_button'           => 'Editer',
    'save_button'           => 'Sauvegarder',
    'delete_button'         => 'Effacer',
    'yes'                   => 'Oui',
    'no'                    => 'Non',
    'required_field'        => 'Indique un champ requis',
    'address_label'         => 'Addresse : ',
    'message'               => 'Message',
    'general_settings'      => 'Paramètres généraux',
    'map_width'             => 'Largeur de la carte (% ou px, mini 550px): ',
    'map_height'             => 'Hauteur de la carte (px uniquement, mini 350px): ',
    'map_zoom'              => 'Zoom (0-21): ',
    'map_type'              => 'Type de carte : ',
    'active'                => 'La carte est active : ',
    'hidden'                => 'La carte est cachée : ',
    'marker_active'         => 'Le marqueur est actif : ',
    'marker_hidden'         => 'Le marqueur est caché : ',
    'free_marker'           => 'La carte accepte des marqueurs gratuits : ',
    'paid_marker'           => 'La carte accepte des marqueurs payants : ',
    'error_address_empty'   => 'Merci de saisir une adresse valide.',
    'error_invalid_address' => 'Cette adresse est invalide. Assurer vous de saisir votre numéro de rue ainsi que le nom de la commune.',
    'error_google_error'    => 'Il y a eut un problèque lors de votre requette. Essayez a nouveau.',
    'error_no_map_info'     => 'Désolé! Les informations cartographiques ne sont pas disponibles pour cette adresse.',
    'need_directions'       => 'Besoin de d\'indications? Saisissez votre point de départ :',
    'directions_title'     => 'Préparer votre itinéraire',
    'directions_start'     => 'Point de départ',
    'get_directions'        => '  Valider  ',
    'maps_list'             => 'Liste des cartes',
    'you_can'               => 'Vous pouvez ',
    'user_maps_list'        => 'Voici les cartes présentes dans notre base de données :',
    'markers_list'          => 'Liste des marqueurs',
    'no_map'                => 'Il n\'y a pas encore de carte dans notre base de données. Vous devez en créer une pour pouvoir ajouter des marqueurs.',
    'no_map_user'           => 'Oups... Il n\'y a pas de carte active.',
    'value_directions'      => 'Nom de la rue, commune, pays', // No quote here please
    'id'                    => 'ID',
    'name'                  => 'Nom',
    'description'           => 'Description',
    'active_field'          => 'Actif',
    'hidden_field'          => 'Caché',
    'marker_count'          => 'Marqueurs',
    'status_active'         => 'Actif',
    'status_inactive'       => 'Inactif',
    'status_visible'        => 'Visible',
    'status_hidden'         => 'Caché',
    'title_display'         => 'Display map page',
    'map_header_label'      => 'Entête facultatif',
    'map_footer_label'      => 'Pied de page facultatif',
    'header_footer'         => 'Entête et pied de page',
    'informations'          => 'Informations',
    'must_belong_to'        => 'Pour accéder à cette carte vous devez appartenir au groupe :',
    'private_access'        => 'Acces privé',
    'marker_label'          => 'Marqueur',
    'primary_color_label'   => 'Couleur principale',
    'stroke_color_label'    => 'Couleur du contour',
    'label'                 => 'Label',
    'label_color'           => 'Couleur du label',
    'black'                 => 'Noir',
    'white'                 => 'Blanc',
    'payed'                 => 'Marqueur payé :',
    'lat'                   => 'Latitude :',
    'lng'                   => 'Longitude :',
    'ressources_tab'        => 'Onglet ressources',
    'presentation'          => 'Présentation',
    'ressources'            => 'Ressources',
    'presentation_tab'      => 'Onglet présentation',
    'empty_ressources'      => 'Les labels des ressources sont vierges. Vous devez en spécifié un au moins pour utiliser les ressources. Veuillez vous reporter à l\'espace de configuration.',
    'empty_for_geo'         => 'Laissez vierge la latitude et la longitude si vous souhaitez la géolocalisation de l\'adresse ci-dessus.',
    'select_marker_map'     => 'Sélectionner la carte sur laquelle vous souhaitez que le marqueur apparaîsse.',
    'remark'                => 'Notes',
    'marker_created'        => 'Marqueur créée le :',
    'map_created'           => 'Carte créée le :',
    'modified'              => 'Dernière modification :',
    'marker_validity'       => 'Utilisé la validité temporaire :',
    'maps_empty'            => 'Merci de bien vouloir créer une carte en premier.',
    'from'                  => 'Du :',
    'to'                    => 'Au :',
    'date_issue'            => 'La fin de la validité est antérieur au début de la validité. Merci de bien vouloir vérifier.',
    'max_char'              => 'caractères maximum.',
    'street_label'          => 'Rue :',
    'code_label'            => 'Code postal:',
    'city_label'            => 'Commune :',
    'state_label'           => 'Etat/Province :',
    'country_label'         => 'Pays :',
    'tel_label'             => 'Tel :',
    'fax_label'             => 'Contact complémentaire :',
    'web_label'             => 'Web :',
    'not_use_see_config'    => 'Non utilisé. Voir la config.',
    //global maps
    'global_map'            => 'La carte globale',
    'info_global_map'       => 'Toutes les cartes sur une seule.',
    'users_map'             => 'La carte des membres',
    'info_users_map'        => 'C\'est la carte des membres du site. Si vous avez un compte sur ce site, vous pouvez vous y ajouter en renseignant le paramètre localisation de votre profil.',
    //Submission
    'address'               => 'Adresse',
    'created'               => 'Date',
    'submit_marker'         => 'Soumettre un marqueur',
    'submit_marker_text'    => '<p><ol><li>Paramètrez la localisation du marqueur<li>Renseignez tous les champs nécessaires<li>Validez</ol></p>',
    'markers_submissions'   => 'Soumissions des marqueurs',
    'submission_disabled'   => 'La soumission des marqueurs est désactivée.',
    'go'                    => 'Montrer moi cette adresse.',
    //date and hits
    'last_modification'     => 'Mise à jour :',
    'hits'                  => 'visites',
    //user marker
    'member'                => 'Membre',
    'location'              => 'Localisation : ',
    'regdate'               => 'Membre depuis : ',
    'about'                 => 'A propos',
    'my_markers'            => 'Mes marqueurs',
    'payed_label'           => 'Payé',
    'from_label'            => 'Valide du',
    'to_label'              => 'Valide jusqu\'au',
    'no_marker'             => 'Vous n\'avez pas de marqueur ou ils n\'ont pas encore été approuvés. Si vous pensez que c\'est une erreur, vous pouvez contacter l\'administrateur du site.',
    'marker_detail'         => 'Détails du marqueur',
    'admin_can'             => 'En tant qu\'administrateur des cartes vous pouvez',
    'create_map'            => 'créer une nouvelle carte',
    'set_user_geo'          => 'Vérifier la géolocalisation des membres',
    'set_geo_location'      => 'Ok, le système à vérifié et paramètré toutes les géolocalisations.',
    'records'               => 'enregistrements',
    'report'                => 'Signaler ce marqueur',
    'report_subject'        => 'Rapport concernant le marqueur ',
    'edit_marker_text'      => '<p><ol><li>Déterminez la position du marqueur<li>Complétez les champs nécessaires<li>Puis validez</ol></p>',
    'admin'                 => 'Admin',
    'category_label'        => 'Catégorie :',
    'choose_category'       => '-- Choisir une catégorie --',
    'categories'            => 'Catégories',
    'categories_list'       => 'Liste des catégories',
    'cat_edit'              => 'Edition de la catégorie :',
    'cat_name_label'        => 'Catégorie :',
    'create_cat'            => 'créer une nouvelle catégorie',
    'field_list'            => 'Liste des champs',
    'addfield'              => 'Ajouter un champ',
    'field_name'            => 'Nom du champ',
    'field_order'           => 'Ordre',
    'field_autotag'         => 'Autotag',
    'field_rights'          => 'Permissions',
    'field_edit'            => 'Edit',
    'valid'                 => 'Valider',
    'editing_field'         => 'Edition du champ',
    'category'              => 'Catégorie',
    'map_label'             => 'carte',
    'colon'                 => ' :', //Add space before if needed
    'view_map'              => 'Voir la carte',
    'view_markers'          => 'Liste des marqueurs',
    'code'                  => 'Code postal',
    'city'                  => 'Commune',
    'viewing_markers'       => 'Afficher la liste des marqueurs',
    'details'               => 'Détails',
    'view_details'          => 'Voir la fiche',
    'print'                 => 'Imprimer',
	'to_complete'           => 'A compléter',
	'autotag_desc_maps'     => '[maps: xx zoom:ZZ lieu] - Affiche la carte dont l\'id=XX. Options niveau de zoom (0 à 21) et centre la carte sur le lieu.',
	'autotag_desc_geo'      => '[geo: map width:XX height:YY zoom:ZZ location] - Affiche une carte centrée sur le point location (rue, nom de ville, pays). Les options sont width (largeur) et height (largeur) en pixels (par exemple 400px) et le zoom (entre 0 et 21).',
	'autotag_desc_marker'   => '[marker: xx] - Affiche le marqueur dont l\'id=XX',
	//v1.1
	'marker_customisation'  => 'Personnalisation du marqueur',
	'mk_default'            => 'Utiliser le marqueur par défaut',
	'overlays'              => 'Calques',
	'overlays_list'         => 'Liste des calques',
	'create_overlay'        => 'Créer un nouveau calque',
	'edit_overlay_text'     => 'Edition du calque',
	'overlay_edit'          => 'Édition de l\'overlay :',
	'overlay_name_label'    => 'Nom du calque',
	'overlay_presentation'  => 'Overlays (les calques) are objects on the map that are tied to latitude/longitude coordinates, so they move when you drag or zoom the map. Overlays reflect objects that you "add" to the map to designate points, lines, or areas. Here you can add an image as an overlay',
	'overlay_active'        => 'Ce calque est activé',
	'zoom_min_label'        => 'Zoom min',
	'zoom_max_label'        => 'Zoom max',
	'image_message'         => 'Select an image from your hard drive.',
	'image_replace'         => 'Uploading a new image will replace this one:',
	'image'                 => 'Image',
	'sw_lat'                => 'SW Latitude:',
    'sw_lng'                => 'SW Longitude:',
	'ne_lat'                => 'NE Latitude:',
    'ne_lng'                => 'NE Longitude:',
	'overlay_not_writable'  => 'Overlays folder is not writable: Please create this folder first and make it writable before using this feature.', 
	'map_tab'               => 'Carte',
	'overlays_tab'          => 'Calques',
	'add_overlay'           => 'Ajouter l\'overlay',
	'remove_overlay'        => 'Supprimer ce calque',
	'overlay_label'         => 'Calque',
	'import_export'         => 'Import/Export',
	'import'                => 'Import',
	'export'                => 'Export',
	'select_file'           => 'Select a .csv file',
	'import_message'        => 'Sélectionner la carte sur laquelle vous souhaitez ajouter des marqueurs, choisir le fichier csv pour importer vos markers à partir de votre disque dur, et chosir le delimiteur pour les données.',
	'markers_added'          => 'Marqueurs ajoutés sur la carte :',
	'export_message'        => 'Choisir la carte dont vous souhaitez exporter les marqueurs et chosir le delimiteur pour les données.',
	'no_marker_to_export'   => 'Il n\'y a pas de marqueur à exporter pour cette carte.',
	'icons'                 => 'Icons',
	'icons_not_writable'    => 'Icons folder is not writable: Please create this folder first and make it writable before using this feature.',
	'icons_list'            => 'Liste des icônes',
	'create_icon'           => 'créer une nouvelle icône',
	'icon_edit'             => 'Edition d\'icon',
	'icon_presentation'        => 'Ici vous pouvez télécharger un nouvel icon à utiliser avec les marqueurs', 
	'icon_name_label'       => 'Nom de l\'icon',
	'xmarkers'              => 'marqueurs',
	'1marker'               => 'marqueur',
	'choose_icon'           => 'Vous pouvez choisir une icône pour ce marqueur. Les icônes sont prioritaires sur les couleurs.',
	'no_icon'               => 'Pas d\'icône',
	'no_custom_icons'        => 'Aucune icône personnalisée n’est enregistrée.',
	'manage_icons'           => 'Gérer les icônes',
	'separator'             => 'Choisir le délimiteur',
	'markers_to_add'        => 'Merci de vérifier que les valeurs correspondent aux champs et de confirmer l\'ajout des marqueurs suivants sur la carte :',
	'choose_fields_import'  => 'Choisir les champs à importer',
	'choose_fields_export'  => 'Choisir les champs à exporter',
	'checkall'              => 'Tout cocher',
	'order'                 => 'Ordre',
	'move'                  => 'Move',
	'name_missing'          => 'A least a name is missing. Please check your csv file.',
	'need_address'          => 'We need at least an adresse or coordinates to build a marker. Please check your csv file something is missing.',
	'manage_groups'         => 'Gérer les groupes de calques',
	'create_group'          => 'Créer un nouveau groupe de claques',
	'group_edit'            => 'Edition du groupe :',
	'group_overlay_presentation' => 'Vous pouvez choisir ou modifier le nom de votre groupe de calques.',
	'group_overlay_name_label'   => 'Nom du groupe',
	'group_label'           => 'Groupe (optionnel)',
	'choose_group'          => 'Choisir un group',
	'group'                 => 'Groupe',
	'from_owner'            => 'de',
	
	//v1.3
	'geo_fail'              => 'L\'adresse saisie de semble pas être valide',
	'on_map'                => 'Sur la carte',
	'read_more'             => 'Lire la suite',
	'from_map'              => 'Sur la carte',
	'show_hide_overlays'    => 'Afficher / masquer les calques',
	'fields_presentation'   => 'Editez une catégorie existante pour ajouter ou éditer un champ.',
	'overlays_added'        => 'Overlays présents sur cette carte',
	'overlays_to_add'       => 'Overlays que vous pouvez ajouter sur cette carte',
	'marker_modification'   => 'Modification du marqueur',
	'marker_limited'        => 'Nous sommes désolés mais l\'accès à ce marqueur est limité...',
	'events_map'            => 'Carte des prochains évènements',
	'info_events_map'       => '',
	'from_cal'              => 'Du',
	'to_cal'                => 'au',
	'on_cal'                => 'Le',
    //v1.4
    'admin_menu_maps' => 'Cartes',
    'admin_menu_markers' => 'Marqueurs',
    'admin_menu_icons' => 'Icônes',
    'admin_menu_overlays' => 'Calques',
    'admin_menu_import_export' => 'Import/Export',
    'admin_menu_geocoder' => 'Géocoder',
    'admin_menu_geolocation' => 'Géolocalisation',
    'admin_menu_configuration' => 'Configuration',
    'section_location' => 'Localisation',
    'section_content_contact' => 'Contenu et contact',
    'section_appearance' => 'Apparence',
    'section_publication' => 'Publication',
    'section_resources' => 'Ressources',
    'section_ownership' => 'Propriétaire',
    'section_permissions' => 'Permissions',
    'delete_confirm' => 'Supprimer définitivement ce marqueur ?',
    'marker_not_found' => 'Marqueur introuvable ou vous n\'avez pas l\'autorisation de le consulter.',
    'delete_map_confirm' => 'Supprimer définitivement cette carte ?',
    'technical_coordinates' => 'Coordonnées techniques',
    'configuration'         => 'Configuration',
    // Maps 1.5.6 map editor
    'map_section_display' => 'Affichage',
    'map_section_center' => 'Centre et zoom',
    'map_section_markers' => 'Marqueurs',
    'map_section_advanced' => 'Options avancées',
    'map_center_search' => 'Rechercher une adresse',
    'map_center_search_button' => 'Localiser',
    'map_center_use_button' => 'Utiliser le centre affiché',
    'map_center_help' => 'Recherchez une adresse, cliquez sur la carte ou déplacez le marqueur pour choisir précisément le centre de la carte.',
    'latitude_label' => 'Latitude',
    'longitude_label' => 'Longitude',
    'map_center_marker' => 'Centre de la carte',

);

$LANG_MAPS_MESSAGE = array(
    'message'               => 'Message du système',
    'add_new_field'         => 'Votre nouveau champ a bien été créé.',
    'save_field'            => 'Votre champ a bien été sauvegardé.',
    'delete_field'          => 'Votre champ a bien été effacé.'
);

$LANG_MAPS_EMAIL = array(
    'hello_admin'           => 'Hello admin,',
    'new_marker'            => 'Vous avez un nouveau marqueur qui attend votre approbation.',
    'name'                  => 'Nom :',
    'on_map'                => 'Sur la carte :',
    'submissions'           => 'Soumissions : ',
    'marker_submissions'    => 'Soumission d\'un nouveau marqueur',
	'marker_modification'   => 'Modification d\'un marqueur',
	'description'           => 'Description :',
);

// Messages for the plugin upgrade
$PLG_maps_MESSAGE3002 = $LANG32[9]; // "requires a newer version of Geeklog"

$PLG_maps_MESSAGE1  = "Merci d'avoir soumis un marqueur sur le site {$_CONF['site_name']}.  Il va être validé par notre équipe avant d'être affiché en ligne.";
$PLG_maps_MESSAGE2  = "La soumission des markers est suspendue.";
$PLG_maps_MESSAGE3  = "Oups... Une erreur s'est produite. Je n'ai pas réussi à sauvegarder votre marqueur.";

/**
*   Localization of the Admin Configuration UI
*   @global array $LANG_configsections['maps']
*/
$LANG_configsections['maps'] = array(
    'label' => 'Maps',
    'title' => 'Maps Configuration'
);

/**
*   Configuration system prompt strings
*   @global array $LANG_confignames['maps']
*/
$LANG_confignames['maps'] = array(
    'hide_maps_menu'        => 'Hide Maps menu',
    'maps_login_required'   => 'Maps login required',
    'autofill_coord'        => 'Automatically fill undefined coordinates',
    'display_geo_profile'   => 'Profile geolocalisation',
    'map_type_profile'      => 'Profile map type',
    'map_type_geotag'       => 'geo autotag map type',
    'show_directions_geo'   => 'geo autotag show directions',
    'show_directions_profile' => 'Profile show directions',
    'map_width_geotag'      => 'geo autotag map width (with % or px)',
    'map_height_geotag'     => 'geo autotag map height (with px only)',
    'map_zoom_geotag'       => 'geo autotag zoom (0-21)',
    'map_width_profile'     => 'Profile map width (with % or px)',
    'map_height_profile'    => 'Profile map height (with px only)',
    'show_map'              => 'Show Google Map',
    'google_api_key'        => 'Google Maps API Key',
    'url_geocode'           => 'URL to Google Geocoding Service',
    'map_width'             => 'Maps width by default(with % or px)',
    'map_height'            => 'Maps height by default(with px only)',
    'map_zoom'              => 'Maps zoom by default(0-21)',
    'map_type'              => 'Maps type by default',
    'default_permissions'   => 'Permissions by default',
    'map_main_header'       => 'Main page header, autotag welcome',
    'map_main_footer'       => 'Main page footer, autotag welcome too',
    'map_geo'               => 'Create a map with all profiles',
    'map_markers'           => 'Create a map with all markers',
    'map_active'            => 'Map is active',
    'map_hidden'            => 'Map is hidden',
    'free_markers'          => 'Map accept free markers',
    'paid_markers'          => 'Map accept paid markers (need paypal plugin)',
    'street'                => 'Use street info',
    'code'                  => 'Use code info',
    'city'                  => 'Use city info',
    'state'                 => 'Use state info',
    'country'               => 'Use country info',
    'tel'                   => 'Use tel info',
    'fax'                   => 'Utiliser le contact complémentaire',
    'web'                   => 'Use web info',
    'item_1'                => 'Libellé du champ personnalisé 1',
    'item_2'                => 'Libellé du champ personnalisé 2',
    'item_3'                => 'Libellé du champ personnalisé 3',
    'item_4'                => 'Libellé du champ personnalisé 4',
    'item_5'                => 'Libellé du champ personnalisé 5',
    'item_6'                => 'Libellé du champ personnalisé 6',
    'item_7'                => 'Libellé du champ personnalisé 7',
    'item_8'                => 'Libellé du champ personnalisé 8',
    'item_9'                => 'Libellé du champ personnalisé 9',
    'item_10'               => 'Libellé du champ personnalisé 10',
    'label_color'           => 'Label color',
    'star_primary_color'    => 'Star primary color',
    'star_stroke_color'     => 'Star stroke color',
    'marker_active'         => 'Marker is active by default',
    'marker_hidden'         => 'Marker is hidden by default',
    'marker_payed'          => 'Marker payed by default',
    'marker_validity'       => 'Marker validy by default',
    'marker_submission'     => 'Allow markers submission',
    'users_map'             => 'Active map of site users',
    'global_map' 	        => 'Active global map',
    'global_type'           => 'Global map type',	
    'global_width'  	    => 'Global map width',
    'global_height' 	    => 'Global map height',
    'global_zoom'           => 'Global map zoom (0-21)',
    'detail_zoom'           => 'Marker detail zoom (0-21)',
    'submit_login_required' => 'Login require for markers submissions',
    'marker_edition'        => 'Marker edition',
	'use_cluster'           => 'Use markers cluster',
	'zoom_profile'          => 'Zoom carte profil du membre (0-21)',
	'display_events_map'    => 'Afficher la carte des événements',
);

/**
*   Configuration system subgroup strings
*   @global array $LANG_configsubgroups['maps']
*/
$LANG_configsubgroups['maps'] = array(
    'sg_main' => 'Main Settings',
    'sg_display' => 'Display Settings'
);

/**
*   Configuration system tab names
*   @global array $LANG_configtabs['maps']
*/
$LANG_configtabs['maps'] = array(
    'tab_general' => 'Général',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'Cartes',
    'tab_markers' => 'Marqueurs',
    'tab_fields' => 'Champs des marqueurs',
);

/** Geeklog configuration tab labels (used by config::_UI_get_tab). */
$LANG_tab['maps'] = array(
    'tab_general' => 'Général',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'Cartes',
    'tab_markers' => 'Marqueurs',
    'tab_fields' => 'Champs des marqueurs',
);

/**
*   Configuration system fieldset names
*   @global array $LANG_fs['maps']
*/
$LANG_fs['maps'] = array(
    'fs_main'            => 'General Settings',
    'fs_ads'             => 'Google Ads Settings',
    'fs_google'          => 'Google API Settings',
    'fs_permissions'     => 'Default Permissions',
    'fs_display'         => 'Maps',
    'fs_global_map'      => 'Global Maps',
    'fs_display_profile' => 'Profile',
    'fs_display_geo'     => 'geo autotag',
    'fs_map_default'     => 'Map default settings',
    'fs_marker_default'  => 'Marker default settings',
 );

/**
*   Configuration system selection strings
*   Note: entries 0, 1, and 12 are the same as in 
*   $LANG_configselects['Core']
*
*   @global array $LANG_configselects['maps']
*/
$LANG_configselects['maps'] = array(
    0 => array('True' => 1, 'False' => 0),
    1 => array('True' => TRUE, 'False' => FALSE),
    3 => array('Yes' => 1, 'No' => 0),
    4 => array('On' => 1, 'Off' => 0),
    5 => array('Top of Page' => 1, 'Below Featured Article' => 2, 'Bottom of Page' => 3),
    10 => array('5' => 5, '10' => 10, '25' => 25, '50' => 50),
    11 => array('Miles' => 'miles', 'Kilometres' => 'km'),
    12 => array('No access' => 0, 'Read-Only' => 2, 'Read-Write' => 3),
    20 => array('Normal street map' => 'ROADMAP', 'Satellite images' => 'SATELLITE', 'Terrain map' => 'TERRAIN', 'Transparent layer of major streets on satellite images' => 'HYBRID'),
    30 => array('White' => 1, 'Black' => 0),
    31 => array('Temporary' => 1, 'Permanent' => 0),
);

$LANG_MAPS_1['location_search_label'] = 'Rechercher une adresse';
$LANG_MAPS_1['location_search_help'] = 'Recherchez une adresse, cliquez sur la carte ou déplacez le marqueur pour ajuster précisément sa position.';
$LANG_MAPS_1['use_map_click_help'] = 'Cliquez sur la carte pour déplacer le marqueur.';

/* Maps 1.5.7 configuration labels. */
/* Configuration subgroup and tabs */
$LANG_configsubgroups['maps']['sg_main'] = 'Principaux';
$LANG_configtabs['maps']['tab_general'] = 'Général';
$LANG_tab['maps']['tab_general'] = 'Général';
$LANG_configtabs['maps']['tab_google'] = 'Google Maps';
$LANG_tab['maps']['tab_google'] = 'Google Maps';
$LANG_configtabs['maps']['tab_maps'] = 'Cartes';
$LANG_tab['maps']['tab_maps'] = 'Cartes';
$LANG_configtabs['maps']['tab_markers'] = 'Marqueurs';
$LANG_tab['maps']['tab_markers'] = 'Marqueurs';
$LANG_configtabs['maps']['tab_fields'] = 'Champs des marqueurs';
$LANG_tab['maps']['tab_fields'] = 'Champs des marqueurs';

/* Fieldsets */
$LANG_fs['maps']['fs_main'] = 'Accès et fonctions';
$LANG_fs['maps']['fs_permissions'] = 'Permissions par défaut';
$LANG_fs['maps']['fs_uploads'] = 'Images et uploads';
$LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
$LANG_fs['maps']['fs_display'] = 'Affichage général';
$LANG_fs['maps']['fs_global_map'] = 'Carte globale et carte des membres';
$LANG_fs['maps']['fs_display_profile'] = 'Carte du profil des membres';
$LANG_fs['maps']['fs_display_geo'] = 'Autotag geo';
$LANG_fs['maps']['fs_map_defaults'] = 'Valeurs par défaut des nouvelles cartes';
$LANG_fs['maps']['fs_events_map'] = 'Carte des événements';
$LANG_fs['maps']['fs_marker_defaults'] = 'Valeurs par défaut des marqueurs';
$LANG_fs['maps']['fs_marker_editor'] = 'Carte de saisie / déplacement d’un marqueur';
$LANG_fs['maps']['fs_marker_detail'] = 'Carte de détail d’un marqueur';
$LANG_fs['maps']['fs_marker_popup'] = 'Infobulles des marqueurs';
$LANG_fs['maps']['fs_marker_fields'] = 'Champs et libellés des marqueurs';

/* General */
$LANG_confignames['maps']['maps_login_required'] = 'Connexion requise pour consulter Maps';
$LANG_confignames['maps']['hide_maps_menu'] = 'Masquer Maps dans le menu';
$LANG_confignames['maps']['marker_submission'] = 'Autoriser la soumission de marqueurs';
$LANG_confignames['maps']['submit_login_required'] = 'Connexion requise pour soumettre un marqueur';
$LANG_confignames['maps']['marker_edition'] = 'Autoriser l’édition des marqueurs';
$LANG_confignames['maps']['default_permissions'] = 'Permissions par défaut';
$LANG_confignames['maps']['max_image_width'] = 'Largeur maximale des images (px)';
$LANG_confignames['maps']['max_image_height'] = 'Hauteur maximale des images (px)';
$LANG_confignames['maps']['max_image_size'] = 'Taille maximale d’une image (octets)';

/* Google */
$LANG_confignames['maps']['autofill_coord'] = 'Compléter automatiquement les coordonnées manquantes';
$LANG_confignames['maps']['google_api_key'] = 'Clé API Google Maps (navigateur)';
$LANG_confignames['maps']['google_server_api_key'] = 'Clé API Google Geocoding (serveur, optionnelle)';
$LANG_confignames['maps']['google_map_id'] = 'Google Map ID (préparation Advanced Markers)';
$LANG_confignames['maps']['google_language'] = 'Langue Google Maps (optionnelle, ex. fr)';
$LANG_confignames['maps']['google_region'] = 'Région Google Maps (optionnelle, ex. FR)';
$LANG_confignames['maps']['url_geocode'] = 'URL du service Google Geocoding';

/* New map/display values */
$LANG_confignames['maps']['map_primary_color'] = 'Couleur principale par défaut';
$LANG_confignames['maps']['map_stroke_color'] = 'Couleur de contour par défaut';
$LANG_confignames['maps']['map_label'] = 'Libellé de marqueur par défaut';
$LANG_confignames['maps']['map_label_color'] = 'Couleur de libellé par défaut';
$LANG_confignames['maps']['events_map_zoom'] = 'Zoom de la carte des événements';
$LANG_confignames['maps']['events_map_height'] = 'Hauteur de la carte des événements';

/* Markers */
$LANG_confignames['maps']['marker_editor_type'] = 'Type de carte pour l’éditeur';
$LANG_confignames['maps']['marker_editor_zoom'] = 'Zoom initial de l’éditeur';
$LANG_confignames['maps']['marker_editor_width'] = 'Largeur de la carte de l’éditeur';
$LANG_confignames['maps']['marker_editor_height'] = 'Hauteur de la carte de l’éditeur';
$LANG_confignames['maps']['detail_width'] = 'Largeur de la carte de détail';
$LANG_confignames['maps']['detail_height'] = 'Hauteur de la carte de détail';
$LANG_confignames['maps']['detail_zoom'] = 'Zoom de la carte de détail';
$LANG_confignames['maps']['popup_width'] = 'Largeur des infobulles';
$LANG_confignames['maps']['popup_height'] = 'Hauteur des infobulles';

/* Marker fields */
$LANG_confignames['maps']['street'] = 'Afficher la rue';
$LANG_confignames['maps']['code'] = 'Afficher le code postal';
$LANG_confignames['maps']['city'] = 'Afficher la ville';
$LANG_confignames['maps']['state'] = 'Afficher la région / l’État';
$LANG_confignames['maps']['country'] = 'Afficher le pays';
$LANG_confignames['maps']['tel'] = 'Afficher le téléphone';
$LANG_confignames['maps']['fax'] = 'Afficher le contact complémentaire';
$LANG_confignames['maps']['web'] = 'Afficher le site web';

/* Maps 1.5.10 landing-page SEO configuration. */
$LANG_fs['maps']['fs_seo'] = 'SEO de la page des cartes';
$LANG_confignames['maps']['maps_page_title'] = 'Titre SEO de la page des cartes';
$LANG_confignames['maps']['maps_page_h1'] = 'Titre H1 de la page des cartes';
$LANG_confignames['maps']['maps_meta_description'] = 'Meta description de la page des cartes';
$LANG_confignames['maps']['map_main_header'] = 'Contenu introductif de la page Maps (autotags acceptés)';
?>
