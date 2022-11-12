<?php
/**
 * @author hariansyah
 */
 
namespace RIS\driver\viewer;

class Driver {
    protected $config;
    protected $caller;

    function __construct($config, $caller) {
        $this->config = $config;
        $this->caller = $caller;
    }

    public function getViewer($accNumber) {
        $viewer = $this->config;
        $url = str_replace("[ACCESSION_NUMBER]", $accNumber, $viewer["url"].$viewer["queries"]);        
        header("Location: ".$url);
    }

    public function supportViewerWithIframe() {
        return $this->config["supportIframe"];
    }
}