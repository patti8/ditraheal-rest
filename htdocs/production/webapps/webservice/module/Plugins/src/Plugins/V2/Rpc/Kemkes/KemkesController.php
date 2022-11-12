<?php
namespace Plugins\V2\Rpc\Kemkes;

use DBService\RPCResource;
use Laminas\Json\Json;
use Laminas\ApiTools\ApiProblem\ApiProblem;

class KemkesController extends RPCResource
{
    protected $authType = self::AUTH_TYPE_LOGIN;
    
    public function __construct($controller) {        
        $this->jenisBridge = 3;
        $this->config = $controller->get('Config');
        $this->config = $this->config['services']['SIMpelService'];
        $this->config = $this->config['plugins']['Kemkes'];
    }        

    /* Siranap - Informasi Referensi Kelas
     * @method kelas
     */
    public function kelasAction() {
        $result = $this->sendRequest("siranap/kelas");        
        return $this->getResultRequest($result);
    }

    /* Siranap - Mapping Kelas Simrs dan Kemkes
     * @method kelasSimrsKemkes     
     * @get
     * @post @put array(
     *    "ID" => int
     *    , "KELAS" => int
     *    , "KEMKES_KELAS" => string
     *    , "STATUS" => int
     * )
     * @return array | mixed
     */
    public function kelasSimrsKemkesAction()
    {       
		$request = $this->getRequest();
        $method = $request->getMethod();    
		$data = Json::encode($this->getPostData($request));
		$result = $this->sendRequest('siranap/kelasSimrsKemkes', ($method != "GET" ? 'POST' : "GET"), $data);
		
        return $this->getResultRequest($result);
    }

    /* Siranap - Mapping Ruangan Simrs dan Kemkes
     * @method ruanganSimrsKemkes     
     * @get
     * @post @put array(
     *    "ID" => int
     *    , "RUANGAN" => int
     *    , "KEMKES_RUANGAN" => string
     *    , "STATUS" => int
     * )
     * @return array | mixed
     */
    public function ruanganSimrsKemkesAction()
    {       
		$request = $this->getRequest();
        $method = $request->getMethod();    
		$data = Json::encode($this->getPostData($request));
		$result = $this->sendRequest('siranap/ruanganSimrsKemkes', ($method != "GET" ? 'POST' : "GET"), $data);
		
        return $this->getResultRequest($result);
    }

    /* Siranap - Informasi Referensi Ruangan
     * @method ruangan
     */
    public function ruanganAction() {
        $result = $this->sendRequest("siranap/ruangan");        
        return $this->getResultRequest($result);
    }

    /* Siranap - Informasi Bed monitor
     * @method tempatTidur
     */
    public function tempatTidurAction() {
        $query = (array) $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $result = $this->sendRequest("siranap/tempatTidur".$query);
        
        return $this->getResultRequest($result);
    }

    /* Dashboard - Data Kunjungan
     * @method dashboardKunjungan
     * @params [
     *  "TAHUN" => int,
     *  "BULAN" => 01-12
     * ]
     */
    public function dashboardKunjunganAction() {
        $query = (array) $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $result = $this->sendRequest("dashboard/kunjungan".$query);
        
        return $this->getResultRequest($result);
    }

    /* Dashboard - Jumlah Kasus Rujukan
     * @method dashboardRujukan
     * @params [
     *  "TAHUN" => int,
     *  "BULAN" => 01-12
     * ]
     */
    public function dashboardRujukanAction() {
        $query = (array) $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $result = $this->sendRequest("dashboard/rujukan".$query);
        
        return $this->getResultRequest($result);
    }

    /* Dashboard - 10 Besar Penyakit Rujukan
     * @method dashboardPenyakitRujukan10Besar
     * @params [
     *  "TAHUN" => int,
     *  "JENIS" => 1 = DIRUJUK, 2 = RUJUKAN
     * ]
     */
    public function dashboardPenyakitRujukan10BesarAction() {        
        $query = (array) $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $result = $this->sendRequest("dashboard/penyakitRujukan10Besar".$query);
        
        return $this->getResultRequest($result);
    }

    /* Dashboard - Data Indikator Pelayanan
     * @method dashboardIndikatorPelayanan
     * @params [
     *  "TAHUN" => int,
     *  "JENIS" => 1 = BULANAN, 2 = TRIWULAN, 3 = TAHUNAN
     * ]
     */
    public function dashboardIndikatorPelayananAction() {        
        $query = (array) $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $result = $this->sendRequest("dashboard/indikatorPelayanan".$query);
        
        return $this->getResultRequest($result);
    }

    /* Sisrute - Referensi
     * @method getFaskes
     * @params query & page [optional]
     */
    public function getFaskesAction() {
        $query = (array) $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $result = $this->sendRequest("sisrute/getFaskes".$query);
        
        return $this->getResultRequest($result);
    }
    
    /* Sisrute - Rujukan
     * @method buatRujukan
     * @params
     * {
     "PASIEN": {
     "NORM": 11223345,               # Nomor Rekam Medis
     "NIK": "7371140101010003",      # Nomor Induk Kependudukan
     "NO_KARTU_JKN": "0000001234501",# Nomor Kartu Jaminan Kesehatan Nasional / BPJS
     "NAMA": "Rahmat Hidayat",       # Nama Pasien (Tanpa Gelar)
     "JENIS_KELAMIN": "1",           # Jenis Kelamin 1. Laki - laki, 2. Perempuan
     "TANGGAL_LAHIR": "1980-01-03",  # Tanggal Lahir Format yyyy-mm-dd
     "TEMPAT_LAHIR": "Makassar",     # Tempat Lahir
     "ALAMAT": "Pettarani",          # Alamat
     "KONTAK": "085123123122"        # Nomor Kontak / HP
     },
     "RUJUKAN": {
     "JENIS_RUJUKAN": "2",          # Jenis Rujukan 1. Rawat Jalan, 2. Rawat Darurat/Inap, 3. Parsial
     "TANGGAL": "2018-08-29 10:00:00", # Tanggal Rujukan Format yyy-mm-dd hh:ii:ss
     "FASKES_TUJUAN": "3404015",     # Kode Faskes Tujuan
     "ALASAN": "1",                  # Lihat Referensi Alasan Rujukan
     "ALASAN_LAINNYA": "Pusing",     # Alasan Lainnya / Tambahan Alasan Rujukan
     "DIAGNOSA": "I10",              # Kode ICD10 Diagnosa Utama
     "DOKTER": {                     # Dokter DPJP
     "NIK": "7371140101010111",  # NIK Dokter
     "NAMA": "Dr. Raffi"         # Nama Dokter
     },
     "PETUGAS": {                    # Petugas yang merujuk
     "NIK": "7371140101010112",  # NIK Petugas
     "NAMA": "Enal"              # Nama Petugas
     }
     },
     "KONDISI_UMUM": {
     "KESADARAN": "1",               # Kondisi Kesadaran Pasien 1. Sadar, 2. Tidak Sadar
     "TEKANAN_DARAH": "120/90",      # Tekanan Darah Pasien dalam satuan mmHg
     "FREKUENSI_NADI": "50",         # Frekuensi Nadi Pasien (Kali/Menit)
     "SUHU": "37",                   # Suhu (Derajat Celcius)
     "PERNAPASAN": "25",             # Pernapasan (Kali/Menit)
     "KEADAAN_UMUM": "sesak, gelisah", # Keadaan Umum Pasien
     "NYERI": 0,                     # Skala Nyeri 0. Tidak Nyeri, 1. Ringan, 2. Sedang, 3. Berat
     "ALERGI": "-"                   # Alergi Pasien
     },
     "PENUNJANG": {
     "LABORATORIUM": "WBC:11,2;HB:15,6;PLT:215;", # Hasil Laboratorium format: parameter:hasil;
     "RADIOLOGI": "EKG:Sinus Takikardi;Foto Thorax:Cor dan pulmo normal;", # Hasil Radiologi format: tindakan:hasil;
     "TERAPI_ATAU_TINDAKAN": "TRP:LOADING NACL 0.9% 500 CC;INJ. RANITIDIN 50 MG;#TDK:TERPASANG INTUBASI ET NO 8 BATAS BIBIR 21CM;" # Terapi atau Tindakan yang diberikan format; TRP:Nama;#TDK:Nama;
     }
     }
     */
    public function buatRujukanAction() {
        $data = Json::encode($this->getPostData($this->getRequest()));
        $result = $this->sendRequest('sisrute/buatRujukan', "POST", $data);
        return $this->getResultRequest($result);
    }
    
    /* Sisrute - Ubah Rujukan
     * @method ubahRujukan/id(Nomor Rujukan)
     * @params = sama dengan post
     */
    public function ubahRujukanAction() {
        $id = $this->params()->fromRoute('id', 0);
        $data = Json::encode($this->getPostData($this->getRequest()));
        $result = $this->sendRequest('sisrute/ubahRujukan/'.$id, "PUT", $data);
        return $this->getResultRequest($result);
    }
    
    /* Sisrute - Simpan Rujukan
     * @method simpanRujukan
     * @params = sama dengan post
     */
    public function simpanRujukanAction() {
        $data = Json::encode($this->getPostData($this->getRequest()));
        $result = $this->sendRequest('sisrute/simpanRujukan', "POST", $data);
        return $this->getResultRequest($result);
    }
    
    /* Sisrute - Batal Rujukan
     * @method batalRujukan/id(Nomor Rujukan)
     */
    public function batalRujukanAction() {
        $id = $this->params()->fromRoute('id', 0);
        $data = Json::encode($this->getPostData($this->getRequest()));
        $result = $this->sendRequest('sisrute/batalRujukan/'.$id, "PUT", $data);
        return $this->getResultRequest($result);
    }
    
    /* Sisrute - Mengambil Rujukan
     * @method getRujukan/id(Nomor Rujukan)
     */
    public function getRujukanAction() {
        $id = $this->params()->fromRoute('id', 0);
        $result = $this->sendRequest('sisrute/getRujukan/'.$id);
        return $this->getResultRequest($result);
    }
    
    /* Sisrute - Daftar Rujukan
     * @method listRujukan
     * @params nomor [optional], tanggal [optional], page [optional], create [optional] (menampilkan rujukan yg dibuat)
     */
    public function listRujukanAction() {
        $query = (array) $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $result = $this->sendRequest('sisrute/listRujukan'.$query);
        return $this->getResultRequest($result);
    }
    
    /* SITB
     * @method sitb
     * @params 
     */
    public function sitbAction() {        
        $request = $this->getRequest();
        $method = $request->getMethod();
        if($method == "GET") {
            $querys = (array) $request->getQuery();
            $querys = count($querys) > 0 ? "?".http_build_query($querys) : "";
            $result = $this->sendRequest('sitb'.$querys);
        } else {
            $id = $this->params()->fromRoute('id', 0);
            $data = Json::encode($this->getPostData($request));
            if(isset($id)) {
                $result = $this->sendRequest('sitb/'.$id, "PUT", $data);
            } else {
                $result = $this->sendRequest('sitb', "POST", $data);
            }
        }
        
        return $this->getResultRequest($result);
    }

    /* Dashboard - 10 Besar Penyakit
     * @method dashboardPenyakit10Besar
     * @params [
     *  "TAHUN" => int,
     *  "JENIS" => 1 = RAWAT INAP, 2 = RAWAT JALAN
     * ]
     */
    public function dashboardPenyakit10BesarAction() {        
        $query = (array) $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $result = $this->sendRequest("dashboard/penyakit10Besar".$query);
        
        return $this->getResultRequest($result);
    }

    /* Dashboard - Data Jumlah Kematian
     * @method dashboardJumlahKematian
     * @params [
     *  "TAHUN" => int
     * ]
     */
    public function dashboardJumlahKematianAction() {        
        $query = (array) $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $result = $this->sendRequest("dashboard/jumlahKematian".$query);
        
        return $this->getResultRequest($result);
    }

    /* Dashboard - Pemeriksaan Golongan Darah
     * @method dashboardGolonganDarah
     * @params [
     *  "TAHUN" => int
     * ]
     */
    public function dashboardGolonganDarahAction() {        
        $query = (array) $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
        $result = $this->sendRequest("dashboard/golonganDarah".$query);
        
        return $this->getResultRequest($result);
    }    

    /* SITB - TERKONFIMRASI TB
     * @method
     * @get
     * @put array(
     *    "ID" => int
     *    , "NORM" => int
     *    , "KUNJUNGAN" => string
     *    , "DIAGNOSA" => string
     *    , "STATUS" => int
     *    lihat entity / table
     * )
     * @return array | mixed
     */

    public function terkonfirmasiTbAction()
    {       
        $query = (array) $this->getRequest()->getQuery();
        $query = count($query) > 0 ? "?".http_build_query($query) : "";
		$request = $this->getRequest();
        $method = $request->getMethod();
        $id = "";
        if($method == "PUT") {
            $paths = explode("/", $this->getRequest()->getUri()->getPath());
            $id = "/".$paths[count($paths) - 1];
        }
        if($method != "GET") $query = "";        
        $data = Json::encode($this->getPostData($request));              
		$result = $this->sendRequest('terkonfirmasiTb'.$id.$query, ($method != "GET" ? $method : "GET"), $data);
        return $this->getResultRequest($result);
    }
}