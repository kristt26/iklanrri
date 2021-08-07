<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\IklanModel;
use App\Models\LayananModel;
use App\Models\TarifModel;
use App\Models\PembayaranModel;
use DateTime;

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
        \Midtrans\Config::$appendNotifUrl = base_url("Iklan/test1") ;
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
        $item =[
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
            'orderid'=>$result['order_id'], 
            'iklanid'=>$item['id'], 
            'nominal'=>$data['biaya'], 
            'status'=>"Proses"
        ];
        $this->pembayaran->save($pembayaran);
        return $this->respond(["token"=>$result['token']]);
    }

    public function read($id = null)
    {
        if ($id) {
            $data = $this->iklan->select("*")->join("layanan", "layanan.id=iklan.id", "left")->join("tarif", "tarif.id=iklan.id", "left")->join("pemesan", "pemesan.id=iklan.id")->where('iklan.id', $id)->first();
            return $this->respond($data);
        } else {
            $data = [
                'iklan' => $this->iklan->select("`iklan`.*,
                `layanan`.`layanan`,
                `tarif`.`kategori`,
                `tarif`.`jenis`,
                `tarif`.`uraian`,
                `tarif`.`satuan`,
                `tarif`.`tarif`")->join("layanan", "layanan.id=iklan.id", "left")->join("tarif", "tarif.id=iklan.id", "left")->where('userid', session()->get('id'))->get()->getResultArray(),
                'layanan' => $this->layanan->get()->getResultArray(),
                'tarif' => $this->tarif->get()->getResultArray()
            ];
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

          $item_details = array ($item1_details);
  
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
            'email'         => "andri@litani.com",
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
          return ['token'=>$snapToken, 'order_id'=>$transaction_details['order_id']];
		
    }

    public function status()
    {
        $data = (array)$this->request->getJSON();
        $status = \Midtrans\Transaction::status($data['order_id']);
        if($status->transaction_status == 'settlement'){
            $this->pembayaran->update($data['order_id'], ['status'=>'Success']);
            $this->respond(true);
        }
    }

}
