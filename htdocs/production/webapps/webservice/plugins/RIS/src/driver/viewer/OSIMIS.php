<?php
/**
 * @author hariansyah
 */
 
namespace RIS\driver\viewer;

class OSIMIS extends Driver {
    public function getViewer($accNumber) {
        $viewer = $this->config;
        $studies = $this->getStudyId($accNumber);  
        try {
            $studies = json_decode($studies);
        } catch(\Exception $e) {
            $studies = [ "" ];
        }        
        if(count($studies) > 0) {
            header("Location: ".($viewer["url"].$viewer["queries"])."?study=".$studies[0]);
            header("Authorization: Basic ".base64_encode($viewer["username"].":".$viewer["password"]));
        }
        
        /*$series = $this->getSeriesId($data[0]["PATIENT_ID"], $data[0]["STUDY_UID"], $data[0]["SERIES_UID"]);
        if(count($series) > 0) {
            header("Location: ".($this->api["url"].$this->api["viewer"])."?series=".$series[0]);
        }*/        
    }

    public function getStudyId($accessionNumber) {
        $viewer = $this->config;
        $options = [
            "url" => $viewer["url"]."tools/find",
            "method" => "POST",
            "data" => [
                "Level" => "Study",
                "Query" => [
                    "0008,0050" => $accessionNumber
                ]
            ],
            "header" => [
                "Authorization: Basic ".base64_encode($viewer["username"].":".$viewer["password"])
            ]
        ];
        return $this->caller->sendRequestData($options);
    }

    public function getSeriesId($patientID, $studyUID, $seriesUID) {
        $viewer = $this->config;
		$options = [
			"url" => $viewer["url"]."tools/find",
			"method" => "POST",
			"data" => [
				"Level" => "Series",
				"Query" => [
					"0010,0020" => $patientID,
					"0020,000d" => $studyUID,
					"0020,000e" => $seriesUID
                ]
            ],
			"header" => [
				"Authorization: Basic ".base64_encode($viewer["username"].":".$viewer["password"])
            ]
        ];

		return $this->caller->sendRequestData($options);
    }
    
    public function supportViewerWithIframe() {
        return $this->config["supportIframe"];
    }
}