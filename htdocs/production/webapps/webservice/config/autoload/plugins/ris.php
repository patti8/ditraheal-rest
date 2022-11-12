<?php
return [
    'db' => [
        'adapters' => [        
            // Jika ada koneksi db tersendiri
        ],
    ],
    'services' => [
        'SIMpelService' => [
            'plugins' => [
                // Jika ada koneksi ke plugins untuk depedensi akses
            ],
        ],
        'RIService' => [ 
            /**
             *  Example Configuration Using DCM4CHEE
             */ 
             /**            
            "HL7" => [ // Pengaturan Koneksi HL7 di Server PACS
                'host' => "10.10.9.25", // IP Server HL7
                'port' => 2575, // Port HL7
                'appName' => 'SIMRSGos V.2+', // Nama Aplikasi yang melakukan Akses
                'organization' => [
                    'id' => '8171123', // Kode Faskes Kemenkes
                    'name' => 'RSUP. dr. J. Leimena', // Nama Faskes
                    'root' => '1.2.360.7371325'
                ],
                'version' => '2.3.1', // HL7 Format Message                
                'provider' => [
                    'name' => "DMWL_AE", // @see driver/worklist
                    'facility' => "RIS"
                ]
            ],
            */

            /**
             *  Example Configuration Using GE
             */    
                  
            "HL7" => [ // Pengaturan Koneksi HL7 di Server PACS
                'host' => "10.10.9.25", // IP Server HL7
                'port' => 6002, // Port HL7
                'appName' => 'SIMRSGos V.2+', // Nama Aplikasi yang melakukan Akses
                'organization' => [
                    'id' => '8171123', // Kode Faskes Kemenkes
                    'name' => 'RSUP. dr. J. Leimena', // Nama Faskes                    
                ],
                'version' => '2.4', // HL7 Format Message
                'provider' => [
                    'name' => "GEPACS",
                    'facility' => "RIS"
                ]
            ],
          

            /**
             *  Example Configuration Using ZFP (GE)
             */       
     
            "viewer" => [
                'name' => 'ZFP',
                'url' => 'http://10.10.9.23/ZFP',
                'queries' => '?mode=proxy#view&un=openapi&pw=mwcePzN9Mp%2f8SvLm%2fM6Wi4IkHMZ%2b05i641Kc3dcsG9BrFN4G6eUoy3kicDG0%2bQRQ&ris_exam_id=[ACCESSION_NUMBER]',
                'supportIframe' => false,
            ],
        

            /**
             *  Example Configuration Using OSIMIS
             */
            /*
            "viewer" => [
                'name' => 'OSIMIS',
                'url' => 'http://192.168.137.99:8042/',
                'username' => 'remote', 
                'password' => 'bismillah',
                'queries' => 'osimis-viewer/app/index.html',
                'supportIframe' => true,
            ],
            */    

            /**
             *  Example Configuration Using OVIYAM
             */
             
            /**
            "viewer" => [
                'name' => 'OVIYAM',
                'url' => 'http://192.168.137.80:8080/oviyam2/oviyam',
                'queries' => '?accessionNumber=[ACCESSION_NUMBER]',
                'supportIframe' => true,
            ],
            */
        ],
    ],
];
