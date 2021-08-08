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
            'kontent' => $data['kontent'],
            'tarifid' => $data['tarifid'],
            'userid' => session()->get('id'),
            'status' => 0,
            'tanggal' => date("Y-m-d")
        ];
        $this->iklan->save($item);
        $item['id'] = $this->iklan->insertID();
        $result = $this->token($item, $data['biaya']);
        $pembayaran = [
            'orderid' => $result['order_id'],
            'iklanid' => $item['id'],
            'nominal' => $data['biaya'],
            'status' => "Proses"
        ];
        $this->pembayaran->save($pembayaran);
        $iklan = $this->iklan->join("pembayaran", "pembayaran.iklanid=iklan.id", "left")->where('iklan.id', $item['id'])->first();
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
                    foreach ($dataLayanan as $key => $itemLayanan) {
                        $new_array = [];
                        foreach ($siaran as $key => $value) {
                            $time = strtotime($dari);
                            $tanggal = date('Y-m-d', $time);
                            if ($value['tanggal'] == $tanggal && $value['waktu'] == $waktu && $value['layananid'] == $itemLayanan['id']) {
                                array_push($new_array, $value);
                            }
                        }
                        if (count($new_array) < 5) {
                            $item = [
                                'iklanid' => $data['id'],
                                'tanggal' => $dari,
                                'waktu' => $waktu,
                            ];
                        }
                    }
                    array_push($newArray, $item);
                }

                // echo "$dari<br/>";
                $dari = date("Y-m-d", strtotime("+1 day", strtotime($dari)));
            }
            $jadwal->insertBatch($newArray);
        } catch (\Throwable $th) {
            return $this->respond(['message' => $th->getMessage()]);
        }
    }
}
