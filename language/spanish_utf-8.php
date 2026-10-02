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
    'plugin_name'           => 'Mapas',
    'plugin_conf'           => 'Configuración del plugin',
    'mapa'                   => 'mapa',
    'need_google_api'       => 'No hay configurada ninguna clave de API de Google Maps para el navegador. Los mapas de Google no pueden mostrarse hasta que se añada esta clave.',
    'api_status_title' => 'Estado de la API de Google Maps',
    'api_status_missing' => 'No hay configurada ninguna clave de API de Google Maps para el navegador.',
    'api_status_testing' => 'Probando la API JavaScript de Google Maps…',
    'api_status_key' => 'Clave del navegador',
    'api_status_ok' => 'La API JavaScript de Google Maps se cargó correctamente: la clave del navegador es aceptada para esta página.',
    'api_status_auth' => 'Google Maps rechazó la clave del navegador o su configuración. Consulte la consola del navegador para ver el código de error exacto de Google y verifique las restricciones de referentes HTTP, las API habilitadas y la facturación de Google Cloud.',
    'api_status_load' => 'No se pudo cargar el script de la API JavaScript de Google Maps. Compruebe la red, la política CSP, los bloqueadores de contenido y la consola del navegador.',
    'api_status_timeout' => 'La API JavaScript de Google Maps no respondió. Compruebe la consola del navegador y el panel Red.',
    'admin_help_title'      => 'Primeros pasos con Mapas',
    'admin_help_intro'      => 'Mapas permite crear varios mapas, añadir marcadores y, cuando sea necesario, utilizar iconos personalizados o superposiciones.',
    'admin_help_google'     => 'Configurar Google Maps',
    'admin_help_google_1'   => 'Abra Google Cloud Console, cree o seleccione un proyecto y vincule una cuenta de facturación para el uso en producción.',
    'admin_help_google_2'   => 'Habilite al menos la API JavaScript de Maps. Habilite también la API de Geocoding si utiliza la conversión automática de direcciones a latitud/longitud.',
    'admin_help_google_3'   => 'Cree una clave de API para el navegador. Restrínjala a sitios web autorizados (referentes HTTP), por ejemplo https://www.example.com/*, y después restrinja la clave a la API JavaScript de Maps.',
    'admin_help_google_4'   => 'Para la geocodificación del lado del servidor se recomienda crear una segunda clave. Restrínjala a la dirección IP del servidor y únicamente a la API de Geocoding.',
    'admin_help_google_5'   => 'Copie la clave del navegador en la clave API de Google Maps y, si se utiliza, la clave del servidor en la clave API de servidor de Google Maps dentro de la configuración de Mapas de Geeklog.',
    'admin_help_security'   => 'Nunca deje una clave de Google Maps sin restricciones en producción. Separar las claves del navegador y del servidor reduce el riesgo de uso no autorizado.',
    'admin_help_create'     => 'Crear el primer mapa',
    'admin_help_create_1'   => 'Haga clic en Crear un mapa nuevo, asígnele un nombre y elija su centro, nivel de zoom y tipo de visualización.',
    'admin_help_create_2'   => 'Guarde el mapa y añada marcadores desde la administración de Mapas. Cada marcador puede utilizar una dirección o coordenadas precisas.',
    'admin_help_create_3'   => 'Los iconos y las superposiciones son opcionales. Empiece con un mapa sencillo y algunos marcadores para validar la configuración de Google Maps.',
    'admin_help_trouble'    => 'Si un mapa aparece atenuado o muestra «Solo para fines de desarrollo», compruebe la facturación de Google Cloud, las API habilitadas y las restricciones de la clave API.',
    'admin_help_official'   => 'Documentación oficial de Google Maps Platform',
    'admin_help_geo_title'  => '¿Qué hace «Comprobar geolocalización de usuarios»?',
    'admin_help_geo_intro'  => 'Este comando examina los miembros que rellenaron el campo Ubicación de su perfil y prepara las coordenadas para el mapa de usuarios.',
    'admin_help_geo_1'      => 'Mapas envía cada ubicación de texto no resuelta a la API de Geocoding de Google, por ejemplo «Nantes, Francia».',
    'admin_help_geo_2'      => "La latitud y longitud devueltas se almacenan en caché en la tabla de geocodificación de Mapas. El perfil de Geeklog del miembro no se modifica.",
    'admin_help_geo_3'      => 'Esto resulta especialmente útil después de una instalación o migración, o cuando muchos miembros han añadido o cambiado su ubicación. Requiere la API de Geocoding y una clave autorizada para geocodificación del lado del servidor.',
    'admin_help_overlays_title' => '¿Para qué sirven las superposiciones?',
    'admin_help_overlays_intro' => 'Una superposición es una imagen georreferenciada colocada sobre el mapa de Google entre coordenadas suroeste y noreste. Añade información visual que no forma parte de la capa base de Google Maps.',
    'admin_help_overlays_1' => 'Mostrar el plano de un sitio, camping, parque, finca, edificio o festival sobre el mapa real.',
    'admin_help_overlays_2' => 'Superponer un mapa histórico, catastral, geológico o turístico, o un plano antiguo, para compararlo.',
    'admin_help_overlays_3' => 'Mostrar una zona temática, como una ruta ilustrada, una zona de obras, un espacio natural, la huella de un proyecto u otra información gráfica.',
    'admin_help_overlays_4' => 'Defina niveles de zoom mínimo y máximo para que la superposición solo se muestre cuando sea útil.',
    'admin_help_overlays_how' => 'Para crear una: prepare una imagen adecuada, abra Superposiciones, introduzca sus límites suroeste y noreste y adjúntela a un mapa desde la pestaña Superposiciones del editor. Las superposiciones son opcionales: para un mapa normal con puntos, los marcadores son suficientes.',
    'admin_help_concepts_title' => 'Conceptos de Mapas de un vistazo',
    'admin_help_concept_map' => 'Mapa',
    'admin_help_concept_map_text' => 'El contenedor principal: centro, zoom, tipo de visualización, dimensiones, permisos y opciones generales.',
    'admin_help_concept_marker' => 'Marcador',
    'admin_help_concept_marker_text' => 'Un punto geográfico situado en un mapa, con nombre, descripción, dirección e información adicional opcional.',
    'admin_help_concept_icon' => 'Icono',
    'admin_help_concept_icon_text' => 'Una imagen opcional que sustituye al marcador estándar de Google para distinguir categorías de puntos.',
    'admin_help_concept_users' => 'Mapa de usuarios',
    'admin_help_concept_users_text' => 'Un mapa generado a partir del campo Ubicación del perfil de Geeklog. Las coordenadas se resuelven y almacenan en caché mediante el sistema de geocodificación de Mapas.',
    'admin_help_trouble_title' => 'Solución rápida de problemas',
    'profile_title'         => 'Geolocalización',
    'buy_marker'            => 'Comprar un marcador',
    'menu_label'            => 'Administración de Mapas',
    'admin_home'            => 'Inicio', // In admin menu
    'user_home'             => 'Todos los mapas', //In user menu
    'maps'                  => 'Mapas',
    'marcadores'               => 'Marcadores',
    'maps_label'            => 'Mapas', // For user  menu
    'create_map'            => 'Crear un mapa nuevo',
    'create_marker'         => 'Crear un marcador nuevo',
    'map_edit'              => 'Editar mapa',
    'marker_edit'           => 'Editar marcador',
    'deletion_succes'       => 'Eliminación correcta',
    'deletion_fail'         => 'Error al eliminar',
    'error'                 => 'Error',
    'save_fail'             => 'Error al guardar',
    'save_success'          => 'Guardado correctamente',
    'missing_field'         => 'Falta un campo obligatorio…',
    'geocoder'              => 'Geocodificador',
    'geocoder_text'         => 'Introduzca una dirección y arrastre el marcador para ajustar la ubicación. La latitud/longitud aparecerá en la ventana de información después de cada geocodificación o desplazamiento.',
    'geocode_failed'         => 'No se pudo geocodificar la dirección. Compruebe la clave API de Google Maps, la activación de la API de Geocoding y la dirección, y vuelva a intentarlo.',
    'go'                    => '¡Ir!',
    'name_label'            => 'Nombre del mapa: ',
    'marker_name_label'     => 'Nombre del marcador: ',
    'description_label'     => 'Descripción:',
    'ok_button'             => 'Aceptar',
    'edit_button'           => 'Editar',
    'save_button'           => 'Guardar',
    'delete_button'         => 'Eliminar',
    'yes'                   => 'Sí',
    'no'                    => 'No',
    'required_field'        => 'Indica un campo obligatorio',
    'address_label'         => 'Dirección: ',
    'message'               => 'Mensaje',
    'general_settings'      => 'Configuración general',
    'map_width'             => 'Anchura del mapa (% o px, mín. 550 px): ',
    'map_height'             => 'Altura del mapa (solo px, mín. 350 px): ',
    'map_zoom'              => 'Zoom del mapa (0-21): ',
    'map_type'              => 'Tipo de mapa: ',
    'active'                => 'El mapa está activo: ',
    'hidden'                => 'El mapa está oculto: ',
    'marker_active'         => 'El marcador está activo: ',
    'marker_hidden'         => 'El marcador está oculto: ',
    'free_marker'           => 'El mapa acepta marcadores gratuitos: ',
    'paid_marker'           => 'El mapa acepta marcadores de pago: ',
    'error_address_empty'   => 'Introduzca primero una dirección válida.',
    'error_invalid_address' => 'Esta dirección no es válida. Asegúrese de indicar también el número de calle y la ciudad.',
    'error_google_error'    => 'Se produjo un problema al procesar la solicitud. Vuelva a intentarlo.',
    'error_no_map_info'     => 'Lo sentimos. No hay información cartográfica disponible para esta dirección.',
    'need_directions'       => '¿Necesita indicaciones? Introduzca su dirección:',
    'directions_title'     => 'Planifique su ruta',
    'directions_start'     => 'Punto de partida',
    'get_directions'        => '  Obtener indicaciones  ',
    'maps_list'             => 'Lista de mapas',
    'you_can'               => 'Puede ',
    'user_maps_list'        => 'Explore nuestros mapas',
    'markers_list'          => 'Lista de marcadores',
    'map_markers_heading'   => 'Marcadores en este mapa',
    'marker_singular'      => 'marcador',
    'marker_plural'        => 'marcadores',
    'views_label'          => 'vistas',
    'no_map'                => 'No hay ningún mapa en la base de datos. Debe crear uno para añadir marcadores.',
    'no_map_user'           => 'Vaya… No hay ningún mapa activo en la base de datos.',
    'value_directions'      => 'p. ej., número y calle, ciudad, país', // No quote here please
    'id'                    => 'ID',
    'name'                  => 'Nombre',
    'description'           => 'Descripción',
    'active_field'          => 'Activo',
    'hidden_field'          => 'Oculto',
    'marker_count'          => 'Marcadores',
    'status_active'         => 'Activo',
    'status_inactive'       => 'Inactivo',
    'status_visible'        => 'Visible',
    'status_hidden'         => 'Oculto',
    'title_display'         => 'Mostrar página del mapa',
    'map_header_label'      => 'Encabezado opcional del mapa',
    'map_footer_label'      => 'Pie opcional del mapa',
    'header_footer'         => 'Encabezado y pie',
    'informations'          => 'Información',
    'must_belong_to'        => 'Para acceder a este mapa debe pertenecer al grupo:',
    'private_access'        => 'Acceso privado',
    'marker_label'          => 'Marcador',
    'primary_color_label'   => 'Color principal',
    'stroke_color_label'    => 'Color del contorno',
    'label'                 => 'Etiqueta',
    'label_color'           => 'Color de la etiqueta',
    'black'                 => 'Negro',
    'white'                 => 'Blanco',
    'payed'                 => 'Marcador de pago:',
    'lat'                   => 'Latitud:',
    'lng'                   => 'Longitud:',
    'ressources_tab'        => 'Pestaña Recursos',
    'presentation'          => 'Presentación',
    'ressources'            => 'Recursos',
    'presentation_tab'      => 'Pestaña Presentación',
    'empty_ressources'      => 'Las etiquetas de recursos están vacías. Debe definir al menos una para utilizar los recursos. Consulte la configuración.',
    'empty_for_geo'         => 'Deje latitud y longitud en blanco si necesita geolocalización automática a partir de la dirección indicada arriba.',
    'select_marker_map'     => 'Seleccione el mapa en el que desea que aparezca el marcador.',
    'remark'                => 'Notas',
    'marker_created'        => 'Marcador creado el:',
    'map_created'           => 'Mapa creado el:',
    'modified'              => 'Última modificación:',
    'marker_validity'       => 'Utilizar fecha de validez:',
    'maps_empty'            => 'Cree primero un mapa.',
    'from'                  => 'Desde:',
    'a'                    => 'Hasta:',
    'date_issue'            => 'La fecha final de validez es anterior a la inicial. Compruébelo.',
    'max_char'              => 'caracteres como máximo.',
    'street_label'          => 'Calle:',
    'code_label'            => 'Código postal:',
    'city_label'            => 'Ciudad:',
    'state_label'           => 'Estado/Región:',
    'country_label'         => 'País:',
    'tel_label'             => 'Tel.:',
    'fax_label'             => 'Contacto adicional:',
    'web_label'             => 'Web:',
    'not_use_see_config'    => 'No utilizar. Consulte la configuración',
    //global maps
    'global_map'            => 'Mapa global',
    'info_global_map'       => 'Todos los mapas reunidos en uno.',
    'users_map'             => 'Mapa de usuarios del sitio',
    'info_users_map'        => 'Este es el mapa de usuarios del sitio. Puede añadirse indicando su ubicación en su perfil.',
    //Submission
    'address'               => 'Dirección',
    'created'               => 'Fecha',
    'submit_marker'         => 'Enviar un marcador',
    'submit_marker_text'    => '<p><ol><li>Defina la ubicación del marcador<li>Rellene todos los campos<li>Valide</ol></p>',
    'markers_submissions'   => 'Envíos de marcadores',
    'submission_disabled'   => 'Cola de envíos deshabilitada para marcadores',
    'go'                    => 'Mostrar esta dirección',
    //date and hits
    'last_modification'     => 'Última modificación:',
    'visitas'                  => 'visitas',
    //user marker
    'member'                => 'Miembro',
    'location'              => 'Ubicación: ',
    'regdate'               => 'Miembro desde: ',
    'about'                 => 'Acerca de',
    'my_markers'            => 'Mis marcadores',
    'payed_label'           => 'De pago',
    'from_label'            => 'Validez desde',
    'to_label'              => 'Validez hasta',
    'no_marker'             => 'No tiene marcadores o todavía no han sido aprobados. Si cree que es un error, contacte con el administrador del sitio.',
    'marker_detail'         => 'Detalle del marcador',
    'admin_can'             => 'Como administrador de mapas puede',
    'create_map'            => 'Crear un mapa nuevo',
    'set_user_geo'          => 'Configurar geolocalización de usuarios',
    'set_geo_location'      => 'El sistema comprobará y establecerá todas las geolocalizaciones.',
    'registros'               => 'registros',
    'report'                => 'Informar sobre este marcador',
    'report_subject'        => 'Informe sobre el marcador ',
    'edit_marker_text'      => '<p><ol><li>Defina la ubicación del marcador<li>Rellene todos los campos obligatorios<li>Valide</ol></p>',
    'admin'                 => 'Administración',
    'category_label'        => 'Categoría:',
    'choose_category'       => '-- Elegir categoría --',
    'categories'            => 'Categorías',
    'categories_list'       => 'Lista de categorías',
    'cat_edit'              => 'Edición de categoría:',
    'cat_name_label'        => 'Nombre de categoría:',
    'create_cat'            => 'crear una categoría nueva',
    'field_list'            => 'Lista de campos',
    'addfield'              => 'Añadir un campo',
    'field_name'            => 'Nombre del campo',
    'field_order'           => 'Orden',
    'field_autotag'         => 'Autotag',
    'field_rights'          => 'Permisos',
    'field_edit'            => 'Editar',
    'valid'                 => 'Válido',
    'editing_field'         => 'Editar campo',
    'category'              => 'Categoría',
    'map_label'             => 'Mapa',
    'colon'                 => ':', //Add space before and after if needed
    'view_map'              => 'Ver mapa',
    'view_markers'          => 'Mostrar lista de marcadores',
    'code'                  => 'Código postal',
    'city'                  => 'Ciudad',
    'viewing_markers'       => 'Mostrar la lista de marcadores',
    'details'               => 'Detalles',
    'view_details'          => 'Ver detalles',
    'print'                 => 'Imprimir',
	'to_complete'           => 'Por completar',
	'autotag_desc_maps'     => '[maps: xx zoom:ZZ location] - Muestra el mapa con id=XX. Las opciones son el nivel de zoom (de 0 a 21) y centrar el mapa en location.',
	'autotag_desc_geo'      => '[geo: Paris, France zoom:12] - Muestra un mapa centrado en un nombre de lugar o dirección. Parámetros opcionales: zoom, width y height. La sintaxis histórica [geo: map ...] sigue siendo compatible.',
	'autotag_desc_marker'   => '[marker: xx] - Muestra el marcador con id=XX',
	//v1.1
	'marker_customisation'  => 'Personalización del marcador',
	'mk_default'            => 'Usar marcador predeterminado',
	'overlays'              => 'Superposiciones',
	'overlays_list'         => 'Lista de superposiciones',
	'create_overlay'        => 'Crear una superposición nueva',
	'edit_overlay_text'     => 'Editar superposición:',
	'overlay_edit'          => 'Editar superposición',
	'overlay_name_label'    => 'Nombre de la superposición:',
	'overlay_presentation'  => 'Las superposiciones son objetos del mapa vinculados a coordenadas de latitud/longitud, por lo que se desplazan al arrastrar o ampliar el mapa. Representan objetos que se «añaden» al mapa para indicar puntos, líneas o áreas. Aquí puede añadir una imagen como superposición.',
	'overlay_active'        => 'Esta superposición está activa:',
	'zoom_min_label'        => 'Zoom mín.:',
	'zoom_max_label'        => 'Zoom máx.:',
	'image_message'         => 'Seleccione una imagen de su disco duro.',
	'image_replace'         => 'Al cargar una imagen nueva se sustituirá esta:',
	'image'                 => 'Imagen',
	'sw_lat'                => 'Latitud SO:',
    'sw_lng'                => 'Longitud SO:',
	'ne_lat'                => 'Latitud NE:',
    'ne_lng'                => 'Longitud NE:',
	'overlay_not_writable'  => 'La carpeta de superposiciones no permite escritura. Créela y concédale permisos de escritura antes de utilizar esta función.',
	'map_tab'               => 'Mapa',
	'overlays_tab'          => 'Superposiciones',
	'add_overlay'           => 'Añadir superposición',
	'remove_overlay'        => 'Eliminar superposición',
	'overlay_label'         => 'Superposición',
	'import_export'         => 'Importar/Exportar',
	'import'                => 'Importar',
	'export'                => 'Exportar',
	'select_file'           => 'Seleccionar un archivo .csv',
	'import_message'        => 'Select the map you want to add markers to, select the csv file to import your markers from your hard drive, select the delimiter for the datas and select the fields you want to import.',
	'markers_added'         => 'Marcadores añadidos al mapa:',
	'export_message'        => 'Seleccione el mapa cuyos marcadores desea exportar, el delimitador de los datos y los campos que desea exportar.',
	'no_marker_to_export'   => 'Lo sentimos, no hay marcadores que exportar desde este mapa.',
	'icons'                 => 'Iconos',
	'icons_not_writable'    => 'Icons folder is not writable: Please create this folder first and make it writable before using this feature.',
	'icons_list'            => 'Lista de iconos',
	'create_icon'           => 'Crear un icono nuevo',
	'icon_edit'             => 'Editar icono',
	'icon_presentation'     => 'Aquí puede cargar un icono nuevo para usarlo con los marcadores', 
	'icon_name_label'       => 'Nombre del icono',
	'xmarkers'              => 'marcadores',
	'1marker'               => 'marcador',
	'choose_icon'           => 'Puede elegir un icono para este marcador. Los iconos prioritarios se muestran sobre los colores.',
	'no_icon'               => 'Sin icono',
	'no_custom_icons'        => 'Todavía no hay ningún icono personalizado registrado.',
	'manage_icons'           => 'Gestionar iconos',
	'separator'             => 'Elegir delimitador',
	'markers_to_add'        => 'Compruebe todos los pares campo/valor y confirme que desea añadir al mapa todos los marcadores siguientes:',
	'choose_fields_import'  => 'Elegir campos para importar',
	'choose_fields_export'  => 'Elegir campos para exportar',
	'checkall'              => 'Seleccionar todo',
    'import_step_1' => 'Preparar la importación',
    'import_step_1_text' => 'Elija el mapa de destino, el archivo CSV, el delimitador y el orden de las columnas.',
    'import_step_2' => 'Comprobar los datos',
    'import_step_2_text' => 'Mapas valida, normaliza y geocodifica las filas antes de escribir nada.',
    'import_step_3' => 'Confirmar la importación',
    'import_step_3_text' => 'Revise el destino, el propietario y los permisos y, después, confirme el lote.',
    'import_minimum' => 'Campos mínimos',
    'import_minimum_help' => 'name + address, o name + lat + lng. El preajuste Mínimo utiliza la opción basada en dirección.',
    'import_recommended' => 'Campos recomendados',
    'import_recommended_help' => 'name, address, lat, lng, description, street, code, city, state, country, tel y web.',
    'import_order_help' => 'Las columnas CSV deben seguir el mismo orden que los campos seleccionados que se muestran a continuación.',
    'import_select_minimum' => 'Campos mínimos',
    'import_select_recommended' => 'Campos recomendados',
    'import_clear_fields' => 'Borrar selección',
    'import_preview_title' => 'Comprobar los datos',
    'import_preview_text' => 'Estos son los valores normalizados que se escribirán si confirma la importación.',
    'import_summary_rows' => 'filas preparadas',
    'import_summary_coordinates' => 'coordenadas proporcionadas',
    'import_summary_geocoded' => 'geocodificadas automáticamente',
    'import_summary_partial' => 'con datos parciales de dirección',
    'import_status' => 'Estado',
    'import_status_ready' => 'Listo',
    'import_status_partial' => 'Listo · datos parciales',
    'import_status_geocoded' => 'geocodificado',
    'import_confirm_title' => 'Confirmar la importación',
    'import_confirm_text' => 'Compruebe la configuración del lote antes de crear los marcadores.',
    'import_confirm_button' => 'Importar %d marcadores',
    'import_cancel_button' => 'Cancelar',
	'order'                 => 'Orden',
	'move'                  => 'Mover',
	'name_missing'          => 'Falta al menos un nombre. Compruebe el archivo CSV.',
	'need_address'          => 'Se necesita al menos una dirección o coordenadas para crear un marcador. Compruebe el archivo CSV; falta algún dato.',
	'manage_groups'         => 'Gestionar grupos de superposiciones',
	'create_group'          => 'Crear un grupo de superposiciones nuevo',
	'group_edit'            => 'Editar grupo de superposiciones',
	'group_overlay_presentation' => 'Aquí puede elegir o editar el nombre del grupo de superposiciones',
	'group_overlay_name_label'   => 'Nombre del grupo',
	'group_label'           => 'Grupo (opcional)',
	'choose_group'          => 'Elegir un grupo',
	'group'                 => 'Grupo',
	
	//v1.3
	'geo_fail'              => 'La dirección introducida no parece válida',
	'on_map'                => 'En el mapa',
	'read_more'             => 'Leer más',
	'from_map'              => 'Mapa:',
	'show_hide_overlays'    => 'Mostrar / ocultar superposiciones',
	'fields_presentation'   => 'Edite una categoría existente para añadir o editar un campo.',
	'overlays_added'        => 'Superposiciones presentes en este mapa',
	'overlays_to_add'       => 'Superposiciones que puede añadir a este mapa',
	'marker_modification'   => 'Modificación del marcador',
	'from_owner'            => 'Añadido por:',
	'marker_limited'        => 'Lo sentimos, el acceso a este marcador está limitado…',
	'events_map'            => 'Mapa de próximos eventos',
	'info_events_map'       => '',
	'from_cal'              => 'Desde',
	'to_cal'                => 'a',
	'on_cal'                => 'Activado',
    //v1.4
    'admin_menu_maps' => 'Mapas',
    'admin_menu_markers' => 'Marcadores',
    'admin_menu_icons' => 'Iconos',
    'admin_menu_overlays' => 'Superposiciones',
    'admin_menu_import_export' => 'Importar/Exportar',
    'admin_menu_geocoder' => 'Geocodificador',
    'admin_menu_geolocation' => 'Geolocation',
    'admin_menu_configuration' => 'Configuración',
    'section_location' => 'Ubicación',
    'section_content_contact' => 'Contenido y contacto',
    'section_appearance' => 'Apariencia',
    'section_publication' => 'Publicación',
    'section_resources' => 'Recursos',
    'section_ownership' => 'Propietario',
    'section_permissions' => 'Permisos',
    'delete_confirm' => '¿Eliminar este marcador permanentemente?',
    'marker_not_found' => 'No se encontró el marcador o no tiene permiso para verlo.',
    'delete_map_confirm' => '¿Eliminar este mapa permanentemente?',
    'technical_coordinates' => 'Coordenadas técnicas',
    'configuration'         => 'Configuración',
    // Maps 1.5.6 map editor
    'map_section_display' => 'Visualización',
    'map_section_center' => 'Centro y zoom',
    'map_section_markers' => 'Marcadores',
    'map_section_advanced' => 'Opciones avanzadas',
    'map_center_search' => 'Buscar una dirección',
    'map_center_search_button' => 'Localizar',
    'map_center_use_button' => 'Usar el centro mostrado',
    'map_center_help' => 'Busque una dirección, haga clic en el mapa o arrastre el marcador para elegir con precisión el centro del mapa.',
    'latitude_label' => 'Latitud',
    'longitude_label' => 'Longitud',
    'map_center_marker' => 'Centro del mapa',

);

$LANG_MAPS_MESSAGE = array(
    'message'               => 'Mensaje del sistema',
    'add_new_field'         => 'El campo nuevo se creó correctamente',
    'save_field'            => 'El campo se guardó correctamente',
    'delete_field'          => 'El campo se eliminó correctamente'
);

$LANG_MAPS_EMAIL = array(
    'hello_admin'           => 'Hola administrador,',
    'new_marker'            => 'Hay un marcador nuevo esperando aprobación.',
    'name'                  => 'Name:',
    'on_map'                => 'En el mapa:',
    'submissions'           => 'Envíos: ',
    'marker_submissions'    => 'Envíos de marcadores',
	'marker_modification'   => 'Modificación del marcador',
	'description'           => 'Descripción:',
);

// Messages for the plugin upgrade
$PLG_maps_MESSAGE3002 = $LANG32[9]; // "requires a newer version of Geeklog"

$PLG_maps_MESSAGE1  = "Thank-you for submitting a marker to {$_CONF['site_name']}.  It has been submitted to our staff for approval.";
$PLG_maps_MESSAGE2  = "Marker submission is close.";
$PLG_maps_MESSAGE3  = "Oups... There was an error. I can't save your marker.";

/**
*   Localization of the Admin Configuration UI
*   @global array $LANG_configsections['maps']
*/
$LANG_configsections['maps'] = array(
    'label' => 'Mapas',
    'title' => 'Configuración de Mapas'
);

/**
*   Configuration system prompt strings
*   @global array $LANG_confignames['maps']
*/
$LANG_confignames['maps'] = array(
    'hide_maps_menu'        => 'Ocultar menú Mapas',
    'maps_login_required'   => 'Se requiere iniciar sesión para Mapas',
    'autofill_coord'        => 'Rellenar automáticamente las coordenadas no definidas',
    'display_geo_profile'   => 'Geolocalización del perfil',
    'map_type_profile'      => 'Tipo de mapa del perfil',
    'map_type_geotag'       => 'Tipo de mapa del autotag geo',
    'show_directions_geo'   => 'Mostrar indicaciones en autotag geo',
    'show_directions_profile' => 'Mostrar indicaciones en el perfil',
    'map_width_geotag'      => 'Anchura del mapa del autotag geo (con % o px)',
    'map_height_geotag'     => 'Altura del mapa del autotag geo (solo px)',
    'map_zoom_geotag'       => 'Zoom del autotag geo (0-21)',
    'map_width_profile'     => 'Anchura del mapa del perfil (con % o px)',
    'map_height_profile'    => 'Altura del mapa del perfil (solo px)',
    'show_map'              => 'Mostrar mapa de Google',
    'google_api_key'        => 'Clave API de Google Maps',
    'url_geocode'           => 'URL del servicio de geocodificación de Google',
    'map_width'             => 'Anchura predeterminada de los mapas (con % o px)',
    'map_height'            => 'Altura predeterminada de los mapas (solo px)',
    'map_zoom'              => 'Zoom predeterminado de los mapas (0-21)',
    'map_type'              => 'Tipo de mapa predeterminado',
    'default_permissions'   => 'Permisos predeterminados',
    'map_main_header'       => 'Encabezado de la página principal, autotag welcome',
    'map_main_footer'       => 'Pie de la página principal, también autotag welcome',
    'map_geo'               => 'Crear un mapa con todos los perfiles',
    'map_markers'           => 'Crear un mapa con todos los marcadores',
    'map_active'            => 'El mapa está activo',
    'map_hidden'            => 'El mapa está oculto',
    'free_markers'          => 'El mapa acepta marcadores gratuitos',
    'paid_markers'          => 'El mapa acepta marcadores de pago (requiere el plugin PayPal)',
    'street'                => 'Usar información de calle',
    'code'                  => 'Usar código postal',
    'city'                  => 'Usar ciudad',
    'state'                 => 'Usar estado/región',
    'country'               => 'Usar país',
    'tel'                   => 'Usar teléfono',
    'fax'                   => 'Usar contacto adicional',
    'web'                   => 'Usar web',
    'item_1'                => 'Etiqueta del campo personalizado 1',
    'item_2'                => 'Etiqueta del campo personalizado 2',
    'item_3'                => 'Etiqueta del campo personalizado 3',
    'item_4'                => 'Etiqueta del campo personalizado 4',
    'item_5'                => 'Etiqueta del campo personalizado 5',
    'item_6'                => 'Etiqueta del campo personalizado 6',
    'item_7'                => 'Etiqueta del campo personalizado 7',
    'item_8'                => 'Etiqueta del campo personalizado 8',
    'item_9'                => 'Etiqueta del campo personalizado 9',
    'item_10'               => 'Etiqueta del campo personalizado 10',
    'label_color'           => 'Color de la etiqueta',
    'star_primary_color'    => 'Color principal de la estrella',
    'star_stroke_color'     => 'Color del contorno de la estrella',
    'marker_active'         => 'Marker is active by default',
    'marker_hidden'         => 'Marker is hidden by default',
    'marker_payed'          => 'Marcador de pago por defecto',
    'marker_validity'       => 'Validez del marcador por defecto',
    'marker_submission'     => 'Permitir envío de marcadores',
    'users_map'             => 'Mapa activo de usuarios del sitio',
    'global_map' 	        => 'Mapa global activo',
    'global_type'           => 'Tipo de mapa global',	
    'global_width'  	    => 'Anchura del mapa global',
    'global_height' 	    => 'Altura del mapa global',
    'global_zoom'           => 'Zoom del mapa global (0-21)',
    'detail_zoom'           => 'Zoom del detalle del marcador (0-21)',
    'submit_login_required' => 'Se requiere iniciar sesión para enviar marcadores',
    'marker_edition'        => 'Edición de marcadores',
	'use_cluster'           => 'Usar agrupación de marcadores',
	'zoom_profile'          => 'Zoom del mapa en el perfil del usuario (0-21)',
	'display_events_map'    => 'Mostrar mapa de eventos',
);

/**
*   Configuration system subgroup strings
*   @global array $LANG_configsubgroups['maps']
*/
$LANG_configsubgroups['maps'] = array(
    'sg_main' => 'Configuración principal',
    'sg_display' => 'Configuración de visualización'
);

/**
*   Configuration system tab names
*   @global array $LANG_configtabs['maps']
*/
$LANG_configtabs['maps'] = array(
    'tab_general' => 'General',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'Mapas',
    'tab_markers' => 'Marcadores',
    'tab_fields' => 'Campos de marcadores',
);

/** Geeklog configuration tab labels (used by config::_UI_get_tab). */
$LANG_tab['maps'] = array(
    'tab_general' => 'General',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'Mapas',
    'tab_markers' => 'Marcadores',
    'tab_fields' => 'Campos de marcadores',
);

/**
*   Configuration system fieldset names
*   @global array $LANG_fs['maps']
*/
$LANG_fs['maps'] = array(
    'fs_main'            => 'Configuración general',
    'fs_ads'             => 'Configuración de Google Ads',
    'fs_google'          => 'Configuración de la API de Google',
    'fs_permissions'     => 'Permisos predeterminados',
    'fs_display'         => 'Mapas',
    'fs_global_map'      => 'Mapas globales',
    'fs_display_profile' => 'Perfil',
    'fs_display_geo'     => 'autotag geo',
    'fs_map_default'     => 'Configuración predeterminada del mapa',
    'fs_marker_default'  => 'Configuración predeterminada del marcador',
 );

/**
*   Configuration system selection strings
*   Note: entries 0, 1, and 12 are the same as in 
*   $LANG_configselects['Core']
*
*   @global array $LANG_configselects['maps']
*/
$LANG_configselects['maps'] = array(
    0 => array('Verdadero' => 1, 'Falso' => 0),
    1 => array('Verdadero' => TRUE, 'Falso' => FALSE),
    3 => array('Sí' => 1, 'No' => 0),
    4 => array('Activado' => 1, 'Desactivado' => 0),
    5 => array('Parte superior de la página' => 1, 'Debajo del artículo destacado' => 2, 'Parte inferior de la página' => 3),
    10 => array('5' => 5, '10' => 10, '25' => 25, '50' => 50),
    11 => array('Millas' => 'millas', 'Kilómetros' => 'km'),
    12 => array('Sin acceso' => 0, 'Solo lectura' => 2, 'Lectura y escritura' => 3),
	// changed in v1.3
    20 => array('Mapa de calles normal' => 'ROADMAP', 'Imágenes de satélite' => 'SATELLITE', 'Mapa de terreno' => 'TERRAIN', 'Capa transparente de calles principales sobre imágenes de satélite' => 'HYBRID'),
    30 => array('Blanco' => 1, 'Negro' => 0),
    31 => array('Temporal' => 1, 'Permanente' => 0),
);

$LANG_MAPS_1['location_search_label'] = 'Buscar una dirección';
$LANG_MAPS_1['location_search_help'] = 'Busque una dirección, haga clic en el mapa o arrastre el marcador para ajustar con precisión su posición.';
$LANG_MAPS_1['use_map_click_help'] = 'Haga clic en el mapa para mover el marcador.';

/* Maps 1.5.7 configuration labels. */
$LANG_configsubgroups['maps']['sg_main'] = 'Principal';
$LANG_configtabs['maps']['tab_general'] = 'General';
$LANG_tab['maps']['tab_general'] = 'General';
$LANG_configtabs['maps']['tab_google'] = 'Google Maps';
$LANG_tab['maps']['tab_google'] = 'Google Maps';
$LANG_configtabs['maps']['tab_maps'] = 'Mapas';
$LANG_tab['maps']['tab_maps'] = 'Mapas';
$LANG_configtabs['maps']['tab_markers'] = 'Marcadores';
$LANG_tab['maps']['tab_markers'] = 'Marcadores';
$LANG_configtabs['maps']['tab_fields'] = 'Campos de marcadores';
$LANG_tab['maps']['tab_fields'] = 'Campos de marcadores';

$LANG_fs['maps']['fs_main'] = 'Acceso y funciones';
$LANG_fs['maps']['fs_permissions'] = 'Permisos predeterminados';
$LANG_fs['maps']['fs_uploads'] = 'Imágenes y cargas';
$LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
$LANG_fs['maps']['fs_display'] = 'Visualización general';
$LANG_fs['maps']['fs_global_map'] = 'Mapa global y de usuarios';
$LANG_fs['maps']['fs_display_profile'] = 'Mapa del perfil de usuario';
$LANG_fs['maps']['fs_display_geo'] = 'Autotag geo';
$LANG_fs['maps']['fs_map_defaults'] = 'Valores predeterminados de nuevos mapas';
$LANG_fs['maps']['fs_events_map'] = 'Mapa de eventos';
$LANG_fs['maps']['fs_marker_defaults'] = 'Valores predeterminados de marcadores';
$LANG_fs['maps']['fs_marker_editor'] = 'Mapa del editor de marcadores';
$LANG_fs['maps']['fs_marker_detail'] = 'Mapa de detalle del marcador';
$LANG_fs['maps']['fs_marker_popup'] = 'Ventanas de información de marcadores';
$LANG_fs['maps']['fs_marker_fields'] = 'Campos y etiquetas de marcadores';

$LANG_confignames['maps']['max_image_width'] = 'Anchura máxima de imagen (px)';
$LANG_confignames['maps']['max_image_height'] = 'Altura máxima de imagen (px)';
$LANG_confignames['maps']['max_image_size'] = 'Tamaño máximo de imagen (bytes)';
$LANG_confignames['maps']['google_api_key'] = 'Clave API de Google Maps para navegador';
$LANG_confignames['maps']['google_server_api_key'] = 'Clave API de Google Geocoding para servidor';
$LANG_confignames['maps']['google_map_id'] = 'ID de mapa de Google (preparación para Advanced Markers)';
$LANG_confignames['maps']['google_language'] = 'Idioma de Google Maps (opcional, p. ej. es)';
$LANG_confignames['maps']['google_region'] = 'Región de Google Maps (opcional, p. ej. ES)';
$LANG_confignames['maps']['url_geocode'] = 'URL del servicio Google Geocoding';
$LANG_confignames['maps']['map_primary_color'] = 'Color principal predeterminado del mapa';
$LANG_confignames['maps']['map_stroke_color'] = 'Color de contorno predeterminado del mapa';
$LANG_confignames['maps']['map_label'] = 'Etiqueta predeterminada del marcador del mapa';
$LANG_confignames['maps']['map_label_color'] = 'Color predeterminado de etiqueta del mapa';
$LANG_confignames['maps']['events_map_zoom'] = 'Zoom del mapa de eventos';
$LANG_confignames['maps']['events_map_height'] = 'Altura del mapa de eventos';
$LANG_confignames['maps']['users_map_lat'] = 'Latitud central del mapa de usuarios (vacío = automático)';
$LANG_confignames['maps']['users_map_lng'] = 'Longitud central del mapa de usuarios (vacío = automático)';
$LANG_confignames['maps']['users_map_zoom'] = 'Zoom del mapa de usuarios (vacío = mapa global)';
$LANG_confignames['maps']['users_map_type'] = 'Tipo del mapa de usuarios (vacío = mapa global)';
$LANG_confignames['maps']['users_map_width'] = 'Anchura del mapa de usuarios (vacío = mapa global)';
$LANG_confignames['maps']['users_map_height'] = 'Altura del mapa de usuarios (vacío = mapa global)';
$LANG_confignames['maps']['marker_editor_type'] = 'Tipo de mapa del editor de marcadores';
$LANG_confignames['maps']['marker_editor_zoom'] = 'Zoom inicial del editor de marcadores';
$LANG_confignames['maps']['marker_editor_width'] = 'Anchura del mapa del editor de marcadores';
$LANG_confignames['maps']['marker_editor_height'] = 'Altura del mapa del editor de marcadores';
$LANG_confignames['maps']['detail_width'] = 'Anchura del mapa de detalle del marcador';
$LANG_confignames['maps']['detail_height'] = 'Altura del mapa de detalle del marcador';
$LANG_confignames['maps']['detail_zoom'] = 'Zoom del mapa de detalle del marcador';
$LANG_confignames['maps']['popup_width'] = 'Anchura de la ventana de información';
$LANG_confignames['maps']['popup_height'] = 'Altura de la ventana de información';

/* Maps 1.5.10 landing-page SEO configuration. */
$LANG_fs['maps']['fs_seo'] = 'SEO de la página de entrada';
$LANG_confignames['maps']['maps_page_title'] = 'Título SEO de la página de entrada de Mapas';
$LANG_confignames['maps']['maps_page_h1'] = 'Encabezado H1 de la página de entrada de Mapas';
$LANG_confignames['maps']['maps_meta_description'] = 'Metadescripción de la página de entrada de Mapas';
$LANG_confignames['maps']['map_main_header'] = 'Contenido introductorio de la página de entrada de Mapas (admite autotags)';

$LANG_MAPS_1['server_geocode_key_missing'] = 'La búsqueda de coordenadas del lado del servidor está habilitada, pero no hay configurada una clave de API de Google Geocoding dedicada al servidor. La clave del navegador nunca se utiliza para geocodificación del lado del servidor.';
$LANG_MAPS_1['api_diag_title'] = 'Configuración de Google Maps Platform';
$LANG_MAPS_1['api_diag_maps_js'] = 'API JavaScript de Maps';
$LANG_MAPS_1['api_diag_geocoding'] = 'API de Geocoding';
$LANG_MAPS_1['api_diag_directions'] = 'API de Directions';
$LANG_MAPS_1['api_diag_browser_key'] = 'Clave API del navegador';
$LANG_MAPS_1['api_diag_server_key'] = 'Clave API del servidor';
$LANG_MAPS_1['api_diag_map_id'] = 'ID de mapa';
$LANG_MAPS_1['api_diag_configured'] = 'Clave configurada — API no verificada';
$LANG_MAPS_1['api_diag_browser_verify'] = 'Clave configurada — verifique con la prueba del navegador siguiente';
$LANG_MAPS_1['api_diag_referrer_hint'] = 'Para las restricciones de referente HTTP de la clave del navegador, autorice este sitio (por ejemplo: %s/*).';
$LANG_MAPS_1['api_diag_missing'] = 'Falta';
$LANG_MAPS_1['api_diag_optional'] = 'Opcional / no configurado';
$LANG_MAPS_1['integrations_title'] = 'Integraciones';
$LANG_MAPS_1['integrations_intro'] = 'Mapas utiliza las API y servicios de Geeklog para que los plugins relacionados puedan descubrir Mapas sin acoplamiento directo con la base de datos.';
$LANG_MAPS_1['integration_active'] = 'Activo';
$LANG_MAPS_1['integration_missing'] = 'Falta el plugin';
$LANG_MAPS_1['integration_native'] = 'Compatibilidad nativa';
$LANG_MAPS_1['integration_xmlsitemap'] = 'Mapa del sitio XML';
$LANG_MAPS_1['integration_documents'] = 'Documentos';
$LANG_MAPS_1['integration_indexnow'] = 'IndexNow';
$LANG_MAPS_1['integration_rss'] = 'Fuentes RSS / Atom';
$LANG_MAPS_1['use_my_location'] = 'Usar mi ubicación';
$LANG_MAPS_1['geolocation_unavailable'] = 'La geolocalización del navegador no está disponible. Introduzca manualmente una dirección de partida.';
$LANG_MAPS_1['geolocation_denied'] = 'No se pudo obtener su posición. Permita el acceso a la ubicación o introduzca manualmente una dirección de partida.';
$LANG_MAPS_1['geolocation_https'] = 'La geolocalización del navegador normalmente requiere HTTPS.';
?>
