<?php

class BSV_GitHub_Updater {
    private $slug;
    private $plugin_file;
    private $username;
    private $repository;

    public function __construct($slug, $plugin_file, $username, $repository) {
        $this->slug = $slug;
        $this->plugin_file = $plugin_file;
        $this->username = $username;
        $this->repository = $repository;

        add_filter("pre_set_site_transient_update_plugins", [$this, "check_update"]);
        add_filter("plugins_api", [$this, "plugin_info"], 10, 3);
    }

    private function get_latest_release() {
        $url = "https://api.github.com/repos/{$this->username}/{$this->repository}/releases/latest";

        $args = [
            'headers' => ['User-Agent' => 'WordPress'],
            'timeout' => 20,
        ];

        $response = wp_remote_get($url, $args);

        if (is_wp_error($response)) return false;

        $data = json_decode(wp_remote_retrieve_body($response), true);

        return $data;
    }

    public function check_update($transient) {
        if (empty($transient->checked)) return $transient;

        $release = $this->get_latest_release();
        if (!$release) return $transient;

        $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $this->plugin_file);
        $current_version = $plugin_data['Version'];
        $latest_version = ltrim($release['tag_name'], 'v');

        if (version_compare($latest_version, $current_version, '>')) {
            $transient->response[$this->plugin_file] = (object)[
                'slug' => $this->slug,
                'new_version' => $latest_version,
                'url' => $release['html_url'],
                'package' => $release['zipball_url'],
            ];
        }

        return $transient;
    }

    public function plugin_info($result, $action, $args) {
        if ($action !== 'plugin_information' || $args->slug !== $this->slug) {
            return $result;
        }

        $release = $this->get_latest_release();
        if (!$release) return $result;

        return (object)[
            'name' => $this->slug,
            'slug' => $this->slug,
            'version' => ltrim($release['tag_name'], 'v'),
            'author' => '<a href="https://github.com/' . $this->username . '">' . $this->username . '</a>',
            'homepage' => $release['html_url'],
            'download_link' => $release['zipball_url'],
            'sections' => [
                'description' => $release['body'],
                'changelog' => $release['body'],
            ]
        ];
    }
}
?>
