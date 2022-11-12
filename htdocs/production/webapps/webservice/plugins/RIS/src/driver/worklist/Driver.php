<?php
namespace RIS\driver\worklist;

use Layanan\V1\Rest\TindakanMedis\TindakanMedisService as TindakanMedis;
use Layanan\V1\Rest\PetugasTindakanMedis\PetugasTindakanMedisService as PetugasTindakanMedis;
use Layanan\V1\Rest\OrderRad\OrderRadService as OrderRad;
use RIS\V1\Rest\ModalityTindakan\Service as ModalityTindakan;
use Laminas\ApiTools\ApiProblem\ApiProblem;

use RIS\hl7\Connection;
use RIS\hl7\Message;
use RIS\hl7\v_2_3_1\MSH;
use RIS\hl7\v_2_3_1\PID;
use RIS\hl7\v_2_3_1\PV1;
use RIS\hl7\v_2_3_1\ORC;
use RIS\hl7\v_2_3_1\OBR;
use RIS\hl7\v_2_3_1\ZDS;

use RIS\V1\Rest\Logs\Service as Hl7Logs;

class Driver {
    protected $tindakanMedis;
    protected $petugasMedis;
    protected $orderRad;
    protected $modalityTindakan;

    protected $msh;
    protected $pid;
    protected $pv1;
    protected $orc;
    protected $obr;
    protected $zds;

    protected $config;
    protected $hl7;
    protected $logs;

    protected function onBeforeKirim($params = []) {}

    function __construct($config)
    {
        $this->config = $config;
        $this->tindakanMedis = new TindakanMedis();
        $this->petugasMedis = new PetugasTindakanMedis();
        $this->orderRad = new OrderRad(true, [
            'Pendaftaran' => false,
            'Ruangan' => false,
            'Referensi' => false,
            'Dokter' => true,
            'OrderDetil' => false,
            'Kunjungan' => false
        ]);

        $this->modalityTindakan = new ModalityTindakan();

        $this->hl7 = new Connection($this->config["host"], $this->config["port"]);
        $this->logs = new Hl7Logs();

        $this->msh = new MSH();
        $this->pid = new PID();
        $this->pv1 = new PV1();
        $this->orc = new ORC();
        $this->obr = new OBR();
        $this->zds = new ZDS();     
        
        $this->msg = new Message();
    }

    function kirim($data = [], $tipe = "NW") {
        $result = [];
        try {
            $this->hl7->connect();
            
            $this->msh->getFieldByName("SendingApplication")->set("VALUE", $this->config["appName"]);
            $this->msh->getFieldByName("SendingFacility")->set("VALUE", $this->config["organization"]["id"]."^".$this->config["organization"]["name"]);
            $this->msh->getFieldByName("ReceivingApplication")->set("VALUE", $this->config["provider"]["name"]);
            $this->msh->getFieldByName("ReceivingFacility")->set("VALUE", $this->config["provider"]["facility"]);            
            $this->msh->getFieldByName("MessageType")->set("VALUE", "ORM^O01");
            $this->msh->getFieldByName("ProcessingID")->set("VALUE", "P");
            $this->msh->getFieldByName("HL7Version")->set("VALUE", $this->config["version"]);

            foreach($data as $d) {
                $tindakanMedis = $this->tindakanMedis->load(["ID" => $d]);
                if(count($tindakanMedis) > 0) {
                    // Get Dokter yang melakukan tindakan
                    $petugasMedis = $this->petugasMedis->load([
                        "TINDAKAN_MEDIS" => $d,
                        "JENIS" => 1,
                        "STATUS" => 1,
                        "start" => 0,
                        "limit" => 1
                    ]);
                    $dok = "^";
                    if(count($petugasMedis) > 0) {
                        $dokter = $petugasMedis[0]["REFERENSI"]["MEDIS"];
                        $dok = $dokter["ID"]."^".$dokter["NAMA"];
                    }                    

                    $this->msh->getFieldByName("MessageControlID")->set("VALUE", $d);
                    $kunjungan = $tindakanMedis[0]["REFERENSI"]["KUNJUNGAN"];
                    
                    // Get Dokter yang merujuk / order
                    $order = $this->orderRad->load([
                        "NOMOR" => $kunjungan["REF"]
                    ]);
                    $dokAsal = "^";
                    if(count($order) > 0) {
                        $dokterAsal = $order[0]["REFERENSI"]["DOKTER_ASAL"];
                        $dokAsal = $dokterAsal["ID"]."^".$dokterAsal["NAMA"];
                    }

                    // Get mapping modality tindakan
                    $modalityTindakan = $this->modalityTindakan->load([
                        "TINDAKAN" => $tindakanMedis[0]["TINDAKAN"],
                        "STATUS" => 1
                    ]);
                    $modality = "";
                    if(count($modalityTindakan) > 0) {
                        $ref = $modalityTindakan[0]["REFERENSI"];
                        $modality = $ref["MODALITY"]["NAMA"];
                    }
                    
                    $pendaftaran = $kunjungan["REFERENSI"]["PENDAFTARAN"];
                    $pasien = $pendaftaran["REFERENSI"]["PASIEN"];
                    $ruangan = $kunjungan["REFERENSI"]["RUANGAN"];
                    $tujuan = $pendaftaran["TUJUAN"];
                    $tujuanRuangan = $tujuan["REFERENSI"]["RUANGAN"]; 
                    $jenisRawat = $tujuanRuangan["JENIS_KUNJUNGAN"] == 3 ? "I" : "O";

                    $tanggal = $this->removeSymbolDateTime($kunjungan["MASUK"]);
                    $this->msh->getFieldByName("MessageDateAndTime")->set("VALUE", $tanggal);

                    $this->initPID($pasien);

                    $this->pv1->getFieldByName("PatientClass")->set("VALUE", $jenisRawat);
                    $this->pv1->getFieldByName("AssignedPatientLocation")->set("VALUE", $ruangan["ID"]."^".$ruangan["DESKRIPSI"]);
                    $this->pv1->getFieldByName("AttendingDoctor")->set("VALUE", $dok); // Dokter yang menghadiri
                    $this->pv1->getFieldByName("ReferringDoctor")->set("VALUE", $dokAsal); // Dokter yang merujuk
                    $this->pv1->getFieldByName("VisitNumber")->set("VALUE", $kunjungan["NOMOR"]); // Nomor Kunjungan
                    $this->pv1->getFieldByName("AdmitDate")->set("VALUE", $tanggal);

                    $this->orc->getFieldByName("OrderControl")->set("VALUE", $tipe);
                    $this->orc->getFieldByName("PlacerOrderNumber")->set("VALUE", $d);
                    $this->orc->getFieldByName("FillerOrderNumber")->set("VALUE", $d);
                    $this->orc->getFieldByName("PlacerGroupNumber")->set("VALUE", $kunjungan["NOPEN"]);
                    $this->orc->getFieldByName("OrderStatus")->set("VALUE", $tipe == "CA" ? "CA" : "IP"); //SC
                    $this->orc->getFieldByName("Quantity")->set("VALUE", "1^once^^^^S");
                    $tanggalTindakan = $this->removeSymbolDateTime($tindakanMedis[0]["TANGGAL"]);
                    $this->orc->getFieldByName("DateOfTransaction")->set("VALUE", $tanggalTindakan);
                    //$this->orc->getFieldByName("EnteredBy")->set("VALUE", $tindakanMedis[0]["OLEH"]."^".$tindakanMedis[0]["NAMA_PENGGUNA"]);
                    $this->orc->getFieldByName("OrderingProvider")->set("VALUE", $dok);
                    $this->orc->getFieldByName("EntererLocation")->set("VALUE", $ruangan["ID"]."^".$ruangan["DESKRIPSI"]);
                    $this->orc->getFieldByName("EnteringOrganization")->set("VALUE", $this->config["organization"]["id"]."^".$this->config["organization"]["name"]);
                    
                    $this->obr->getFieldByName("PlacerOrderNumber")->set("VALUE", $d);
                    $this->obr->getFieldByName("FillerOrderNumber")->set("VALUE", $d);
                    $this->obr->getFieldByName("UniversalServiceID")->set("VALUE", $tindakanMedis[0]["TINDAKAN"]."^".$tindakanMedis[0]["TINDAKAN_DESKRIPSI"]);
                    $this->obr->getFieldByName("RequestedDate")->set("VALUE", $tanggalTindakan);
                    $this->obr->getFieldByName("ObservationDate")->set("VALUE", $tanggalTindakan);
                    $this->obr->getFieldByName("OrderingProvider")->set("VALUE", $dok);
                    $this->obr->getFieldByName("PlacerField1")->set("VALUE", $d);
                    $this->obr->getFieldByName("PlacerField2")->set("VALUE", $tindakanMedis[0]["TINDAKAN"]);
                    $this->obr->getFieldByName("FillerField2")->set("VALUE", $tindakanMedis[0]["TINDAKAN_DESKRIPSI"]);
                    ////$this->obr->getFieldByName("ResultsRptOrStatusChngDate")->set("VALUE", "");
                    $this->obr->getFieldByName("DiagnosticServSectID")->set("VALUE", $modality); //US, CT, MR
                    $this->obr->getFieldByName("Quantity")->set("VALUE", "^^^".$tanggalTindakan."^".$tanggalTindakan."^000");
                    $this->obr->getFieldByName("ProcedureCode")->set("VALUE", $tindakanMedis[0]["TINDAKAN"]."^".$tindakanMedis[0]["TINDAKAN_DESKRIPSI"]);
                    ////$this->obr->getFieldByName("CollectorComment")->set("VALUE", "");

                    $logs = $this->logs->load([
                        "TINDAKAN_MEDIS" => $d,
                        "JENIS" => 0 // ORM                        
                    ]);

                    $this->onBeforeKirim([
                        "tindakanMedis" => $tindakanMedis[0],
                        "petugasMedis" => $petugasMedis[0],
                        "modalityTindakan" => $modalityTindakan,
                        "pendaftaran" => $pendaftaran,
                        "kunjungan" => $kunjungan,
                        "pasien" => $pasien,
                        "logs" => $logs
                    ]);
                
                    $this->msg->addSegment("MSH", $this->msh);
                    $this->msg->addSegment("PID", $this->pid);
                    $this->msg->addSegment("PV1", $this->pv1);
                    $this->msg->addSegment("ORC", $this->orc);
                    $this->msg->addSegment("OBR", $this->obr);
                    $this->msg->addSegment("ZDS", $this->zds);                    

                    $request = $this->msg->toString("\r");
                    
                    $id = 0;
                    if(count($logs) > 0) {
                        $id = $logs[0]["ID"];
                        $this->logs->simpanData([
                            "ID" => $id,
                            "TGL_REQUEST" => new \Laminas\Db\Sql\Expression("NOW()"),
                            "REQUEST" => $request,
                            "KIRIM" => 0,
                            "RESPONSE" => new \Laminas\Db\Sql\Expression("NULL")
                        ], false, false);
                    } else {
                        $id = $this->logs->simpanData([
                            "TINDAKAN_MEDIS" => $d,
                            "REQUEST" => $request                            
                        ], true, false);
                    }

                    $result[] = $request;
                    $response = $this->hl7->send($request); 
                    if($id > 0) {
                        $this->logs->simpanData([
                            "ID" => $id,
                            "RESPONSE" => $response,
                            "KIRIM" => 1,
                            "STATUS" => ($tipe == "NW" ? 1 : 0)
                        ], false, false);
                    }                    
                }
            }
        } catch(\Exception $e) {
            return new ApiProblem(422, $e->getMessage());
        } finally {
            $this->hl7->close();
        }

        return $result;
    }    

    protected function initPID($pasien) {
        $jk = $pasien["JENIS_KELAMIN"] == 1 ? "M" : "F";
        $altId = $this->config["organization"]["id"].(str_pad($pasien["NORM"], 8, "0", STR_PAD_LEFT));
        $tglLhr = $this->removeSymbolDateTime($pasien["TANGGAL_LAHIR"]);

        $kontaks = isset($pasien["KONTAK"]) ? $pasien["KONTAK"] : [];
        $kontak = "-";
        foreach($kontaks as $k) {
            if($k["JENIS"] == 3) $kontak = $k["NOMOR"];
        }

        $this->pid->getFieldByName("PatientIdentifierList")->set("VALUE", $pasien["NORM"]);
        $this->pid->getFieldByName("AlternatePatientID")->set("VALUE", $altId);
        $this->pid->getFieldByName("PatientName")->set("VALUE", $pasien["NAMA"]."^");
        $this->pid->getFieldByName("DateOfBirth")->set("VALUE", $tglLhr);
        $this->pid->getFieldByName("Sex")->set("VALUE", $jk);
        $this->pid->getFieldByName("PatientAddress")->set("VALUE", $pasien["ALAMAT"]);        
        $this->pid->getFieldByName("PhoneNumberHome")->set("VALUE", $kontak);
    }

    /**
     * @method generateUID For Worklist
     * SOP Instance UID
     * Example 1.2.840.xxxxxx.1234567890
     * 
     * 1.2.840.xxxxxx = Organization Root
     * 1 = Identifies ISO
     * 2 = Indetifies ANSI Member Body
     * 840 = Contry code of a specific Member Body (U.S for ANSI) @see https://en.wikipedia.org/wiki/Countries_in_the_International_Organization_for_Standardization
     * xxxxxx = Identifies a specific Organization. (assigned by ANSI)
     * 
     * 1234567890 = identification of the worklist
     */
    // 
    protected function generateUID($id) {
        return $this->config["organization"]["root"].".".$id;
    }

    protected function removeSymbolDateTime($tgl) {
        $tw = explode(" ", $tgl);
        $t = explode("-", $tw[0]);
        $w = explode(":", $tw[1]);

        return implode($t).implode($w);
    }
}