<?php
/*
Plugin Name: BSV GitHub Updater
Description: Gestisce l'aggiornamento di tutti i plugin BSV da GitHub.
Version: 1.0
Author: Mattia Giudici
*/

require_once plugin_dir_path(__FILE__) . 'updater.php';

add_action('init', function () {
    $plugins = [
        [
            'slug' => 'bsv-anagrafica-admin',
            'file' => 'bsv-anagrafica-admin/bsv-anagrafica-admin.php',
            'user' => 'MattiaGiudici',
            'repo' => 'bsv-anagrafica-admin',
        ],
        [
            'slug' => 'bsv-custom-iam',
            'file' => 'bsv-custom-iam/bsv-custom-iam.php',
            'user' => 'MattiaGiudici',
            'repo' => 'bsv-custom-iam',
        ],
        [
            'slug' => 'bsv-custom-immich-connector',
            'file' => 'bsv-custom-immich-connector/bsv-custom-immich-connector.php',
            'user' => 'MattiaGiudici',
            'repo' => 'bsv-custom-immich-connector',
        ],
        [
            'slug' => 'bsv-customizations',
            'file' => 'bsv-customizations/bsv-customizations.php',
            'user' => 'MattiaGiudici',
            'repo' => 'bsv-customizations',
        ],
        [
            'slug' => 'bsv-widget-copyright-standard',
            'file' => 'bsv-widget-copyright-standard/bsv-widget-copyright-standard.php',
            'user' => 'MattiaGiudici',
            'repo' => 'bsv-widget-copyright-standard',
        ],
    ];

    foreach ($plugins as $plugin) {
        new BSV_GitHub_Updater($plugin['slug'], $plugin['file'], $plugin['user'], $plugin['repo']);
    }
});
?>
