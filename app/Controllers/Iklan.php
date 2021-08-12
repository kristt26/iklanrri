<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\IklanModel;
use App\Models\LayananModel;
use App\Models\TarifModel;
use App\Models\PembayaranModel;
use DateTime;
use Google\Service\AdExchangeBuyerII\Date;

class Iklan extends BaseController
{
    use ResponseTrait;
    public $iklan;
    public $layanan;
    public $tarif;
    public $pembayaran;

    public function __construct()
    {
        $this->iklan = new IklanModel();
        $this->layanan = new LayananModel();
        $this->tarif = new TarifModel();
        $this->pembayaran = new PembayaranModel();
        \Midtrans\Config::$serverKey = 'SB-Mid-server-SB7XxpKRC8n-Htw3A0efKUtw';
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        \Midtrans\Config::$appendNotifUrl = base_url("Iklan/test1");
    }


    public function index()
    {
        $data['datamenu'] = ['menu' => "Pemasangan Iklan"];
        $data['sidebar'] = view('layout/sidebar');
        $data['header'] = view('layout/header');
        $data['content'] = view('iklan');
        return view('layout/layout', $data);
    }

    public function create()
    {
        $data = (array)$this->request->getJSON();
        // $this->iklan->save($data);
        // $data['id'] = $this->iklan->insertID();

        $item = [
            'layananid' => $data['layananid'],
            'topik' => $data['topik'],
            'waktu' => serialize($data['waktu']),
            'tanggalmulai' => $data['tanggalmulai'],
            'tanggalselesai' => $data['tanggalselesai'],
            'jeniskontent' => $data['jeniskontent'],
            'kontent' => $data['jeniskontent']=='File' ? $this->decodebase64($data['kontent']->base64, 'file') : $data['kontent'],
            'tarifid' => $data['tarifid'],
            'userid' => session()->get('id'),
            'status' => 0,
            'tanggal' => date("Y-m-d")
        ];
        $a = $this->iklan->save($item);
        $item['id'] = $this->iklan->insertID();
        $result = $this->token($item, $data['biaya']);
        $pembayaran = [
            'orderid' => $result['order_id'],
            'iklanid' => $item['id'],
            'nominal' => $data['biaya'],
            'status' => "Proses"
        ];
        $this->pembayaran->save($pembayaran);
        $idiklan = $item['id'];
        $iklan = $this->iklan->newIklan($idiklan);
        $iklan['waktu'] = unserialize($iklan['waktu']);
        $this->tanggalsiaran($item);
        return $this->respond(["token" => $result['token'], "iklan" => $iklan]);
    }

    public function read($id = null)
    {
        $jadwalsiaran = new \App\Models\JadwalModel();
        if ($id) {
            $data = $this->iklan->select("*")->join("layanan", "layanan.id=iklan.id", "left")->join("tarif", "tarif.id=iklan.id", "left")->join("pemesan", "pemesan.id=iklan.id")->where('iklan.id', $id)->first();
            return $this->respond($data);
        } else {
            $data = [
                'iklan' => $this->iklan->readData(),
                'layanan' => $this->layanan->get()->getResultArray(),
                'tarif' => $this->tarif->get()->getResultArray()
            ];
            foreach ($data['iklan'] as $key => $value) {
                $data['iklan'][$key]['jadwalsiaran'] = $jadwalsiaran->where('iklanid', $value['id'])->get()->getResultArray();
            }
            return $this->respond($data);
        }
    }

    public function update()
    {
        $data = (array)$this->request->getJSON();
        $result = $this->iklan->update($data['id'], [
            'layananid' => $data['layananid'],
            'topik' => $data['topik'],
            'waktu' => serialize($data['waktu']),
            'tanggalmulai' => $data['tanggalmulai'],
            'tanggalselesai' => $data['tanggalselesai'],
            'jeniskontent' => $data['jeniskontent'],
            'kontent' => $data['kontent'],
            'tarifid' => $data['tarifid'],
            'pemesanid' => $data['pemesanid'],
            'status' => $data['status']
        ]);
        return $this->respond($result);
    }

    public function delete($id)
    {
        return $this->respond($this->iklan->delete($id));
    }

    public function token($item, $biaya)
    {

        $transaction_details = array(
            'order_id' => time(),
            'gross_amount' => floatval($biaya), // no decimal allowed for creditcard
        );

        // Optional
        $item1_details = array(
            'id' => $item['id'],
            'price' => floatval($biaya),
            'quantity' => 1,
            'name' => "Pemasangan Iklan"
        );

        $item_details = array($item1_details);

        // Optional
        $billing_address = array(
            'first_name'    => session()->get('first_name'),
            'last_name'     => session()->get('last_name'),
            'address'       => "",
            'city'          => "Jayapura",
            'postal_code'   => "99221",
            'phone'         => session()->get('kontak'),
            'country_code'  => 'IDN'
        );

        // Optional
        $customer_details = array(
            'first_name'    => session()->get('first_name'),
            'last_name'     => session()->get('last_name'),
            'email'         => "example@mail.com",
            'phone'         => session()->get('email'),
            'billing_address'  => $billing_address,
            'shipping_address' => ''
        );

        // Fill transaction details
        $transaction = array(
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        );
        //error_log(json_encode($transaction));
        $snapToken = \Midtrans\Snap::getSnapToken($transaction);
        error_log($snapToken);
        return ['token' => $snapToken, 'order_id' => $transaction_details['order_id']];
    }

    public function status()
    {
        $data = (array)$this->request->getJSON();
        $status = \Midtrans\Transaction::status($data['order_id']);
        if ($status->transaction_status == 'settlement') {
            $respon = $this->pembayaran->updatePembayaran(['status' => 'Success'], $data['order_id']);
            $this->respond(true);
        }
    }

    public function CheckTanggal()
    {
        $dari = "2021-02-01"; // tanggal mulai
        $sampai = "2021-02-15"; // tanggal akhir
        $data = [];
        $a = strtotime($dari);
        $c = strtotime($sampai);
        while (strtotime($dari) <= strtotime($sampai)) {
            array_push($data, $dari);
            echo "$dari<br/>";
            $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari))); //looping tambah 1 date
        }
        $b = $data;
    }


    public function tanggalsiaran($data)
    {
        try {
            $jadwal = new \App\Models\JadwalModel();
            $layanan = new \App\Models\LayananModel();
            $dataLayanan = $layanan->get()->getResultArray();
            $dari = $data['tanggalmulai'];
            $sampai = $data['tanggalselesai'];
            $datawaktu = unserialize($data['waktu']);
            $siaran = $jadwal->query("SELECT
                `jadwalsiaran`.*,
                `layanan`.`layanan`,
                `layanan`.`id` AS `layananid`
            FROM
                `jadwalsiaran`
                LEFT JOIN `iklan` ON `iklan`.`id` = `jadwalsiaran`.`iklanid`
                LEFT JOIN `tarif` ON `tarif`.`id` = `iklan`.`tarifid`
                LEFT JOIN `layanan` ON `layanan`.`id` = `tarif`.`layananid` WHERE jadwalsiaran.tanggal >= '$dari' AND jadwalsiaran.tanggal <= '$sampai'")->getResultArray();
            $newArray = [];
            while (strtotime($dari) <= strtotime($sampai)) {
                foreach ($datawaktu as $key => $waktu) {
                    $new_array = [];
                    foreach ($dataLayanan as $key => $itemLayanan) {
                        foreach ($siaran as $key => $value) {
                            $time = strtotime($dari);
                            $tanggal = date('Y-m-d', $time);
                            if ($value['tanggal'] == $tanggal && $value['waktu'] == $waktu && $value['layananid'] == $itemLayanan['id']) {
                                array_push($new_array, $value);
                            }
                        }
                    }
                    if (count($new_array) < 5) {
                        $item = [
                            'iklanid' => $data['id'],
                            'tanggal' => $dari,
                            'waktu' => $waktu,
                        ];
                        array_push($newArray, $item);
                    }
                }

                // echo "$dari<br/>";
                $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
            }
            $jadwal->insertBatch($newArray);
        } catch (\Throwable $th) {
            return $this->respond(['message' => $th->getMessage()]);
        }
    }

    public function jumlahsiaran()
    {
        $data = (array)$this->request->getJSON();
        try {
            $jadwal = new \App\Models\JadwalModel();
            $layanan = new \App\Models\LayananModel();
            $dataLayanan = $layanan->get()->getResultArray();
            $dari = $data['tanggalmulai'];
            $sampai = $data['tanggalselesai'];
            $datawaktu = $data['waktu'];
            $siaran = $jadwal->query("SELECT
                `jadwalsiaran`.*,
                `layanan`.`layanan`,
                `layanan`.`id` AS `layananid`
            FROM
                `jadwalsiaran`
                LEFT JOIN `iklan` ON `iklan`.`id` = `jadwalsiaran`.`iklanid`
                LEFT JOIN `tarif` ON `tarif`.`id` = `iklan`.`tarifid`
                LEFT JOIN `layanan` ON `layanan`.`id` = `tarif`.`layananid` WHERE jadwalsiaran.tanggal >= '$dari' AND jadwalsiaran.tanggal <= '$sampai'")->getResultArray();
            $newArray = [];
            while (strtotime($dari) <= strtotime($sampai)) {
                foreach ($datawaktu as $key => $waktu) {
                    $new_array = [];
                    foreach ($dataLayanan as $key => $itemLayanan) {
                        foreach ($siaran as $key => $value) {
                            $time = strtotime($dari);
                            $tanggal = date('Y-m-d', $time);
                            if ($value['tanggal'] == $tanggal && $value['waktu'] == $waktu && $value['layananid'] == $itemLayanan['id']) {
                                array_push($new_array, $value);
                            }
                        }
                    }
                    if (count($new_array) < 5) {
                        $item = [
                            'tanggal' => $dari,
                            'waktu' => $waktu,
                        ];
                        array_push($newArray, $item);
                    }
                }
                $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
            }
            // $jadwal->insertBatch($newArray);
            return $this->respond($newArray);
        } catch (\Throwable $th) {
            return $this->respond(['message' => $th->getMessage()]);
        }
    }

    public function mime2ext($mime)
    {
        $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp",
        "image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp",
        "image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp",
        "application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg",
        "image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],
        "wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],
        "ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg",
        "video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],
        "kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],
        "rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application",
        "application\/x-jar"],"zip":["application\/x-zip","application\/zip",
        "application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],
        "7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],
        "svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],
        "mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],
        "webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],
        "pdf":["application\/pdf","application\/octet-stream"],
        "pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],
        "ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office",
        "application\/msword"],
        "docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
        "xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],
        "xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel",
        "application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],
        "xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo",
        "video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],
        "log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],
        "wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],
        "tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop",
        "image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],
        "mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar",
        "application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40",
        "application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],
        "cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary",
        "application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],
        "ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],
        "wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],
        "dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php",
        "application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],
        "swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],
        "mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],
        "rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],
        "jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],
        "eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],
        "p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],
        "p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
        $all_mimes = json_decode($all_mimes, true);
        foreach ($all_mimes as $key => $value) {
            if (array_search($mime, $value) !== false) {
                return $key;
            }
        }
        // application/vnd.openxmlformats-officedocument.wordprocessingml.document
        return false;
    }
    public function decodebase64($base64, $folder)
    {
        $target_dir = 'img/' . $folder . "/"; // add the specific path to save the file
        $decoded_file = base64_decode($base64); // decode the file
        $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
        $extension = $this->mime2ext($mime_type); // extract extension from mime type
        $file = uniqid() . '.' . $extension; // rename file as a unique name
        $file_dir = $target_dir . $file;
        $a = file_put_contents($file_dir, $decoded_file);
        if ($a) {
            return $file;
        } else {
            "";
        }
    }
}
