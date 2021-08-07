<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $encrypter;
    protected $db;
    public function __construct()
    {
        $this->encrypter = \Config\Services::encrypter();
        $this->db = \Config\Database::connect();
    }
    
    public function check()
    {
        $plainText = 'Admin@123';
        $ciphertext = $this->encrypter->encrypt($plainText);
        $hasil =  $this->encrypter->decrypt($ciphertext);
        
        if($this->db->table('user')->countAllResults(false) == 0){
            $this->db->transBegin();
            $user = [
                "username"=>"Administrator",
                "password"=> base64_encode($this->encrypter->encrypt("Admin@123")),
                "email"=>"admin@mail.com"
            ];
            $this->db->table('user')->insert($user);
            $userid = $this->db->insertID();
            $role = [
                    "role"=>"Admin"
            ];
            $this->db->table('role')->insert($role);
            $roleid = $this->db->insertID();

            $roleuser = [
                'userid'=>$userid,
                'roleid'=>$roleid
            ];
            $this->db->table('userinrole')->insert($roleuser);

            $petugas = [
                'nama'=>"Administrator",
                'jabatan'=>"Administrator",
                'userid'=>$userid
            ];
            $this->db->table('petugas')->insert($petugas);
            if($this->db->transStatus()=== false){
                $this->db->transRollback();
                return true;
            }else{
                $this->db->transCommit();
                return false;
            }
        }
    }    

    public function login($data)
    {
        $username = $data['username'];
        $result = $this->db->query("SELECT
                `user`.`id`,
                `user`.`username`,
                `user`.`password`,
                `user`.`email`,
                `userinrole`.`roleid`,
                `role`.`role`
            FROM
                `user`
                LEFT JOIN `userinrole` ON `userinrole`.`userid` = `user`.`id`
                LEFT JOIN `role` ON `role`.`id` = `userinrole`.`roleid` WHERE username='$username'")->getRowArray();
        if($result){
            $p = $this->encrypter->decrypt(base64_decode($result['password']));
            if($p==$data['password']){
                return $result;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function isAlreadyRegister($id)
    {
        $builder = $this->db->table('user');
        $builder->where('login_oauth_uid', $id);
        return $builder->countAllResults();
    }

    public function readData($id)
    {
        $builder = $this->db->table('user');
        $builder->where('login_oauth_uid', $id);
        return $builder->get()->getRowArray();
    }

    public function updateUserGoogle($data, $id)
    {
        $this->db->table('user')->update($data, ['login_oauth_uid'=>$id]);
    }
    public function insertUserGoogle($data)
    {
        $this->db->transBegin();
        $role = $this->db->table('role')->where('role', 'Pemesan')->get()->getRowArray();
        $this->db->table('user')->insert($data);
        $userid = $this->db->insertID();
        $userinrole = [
            'userid'=>$userid,
            'roleid'=>$role['id']
        ];
        $this->db->table('userinrole')->insert($userinrole);
        if($this->db->transStatus()){
            $this->db->transCommit();
            return $userid;
        }else{
            $this->db->transRollback();
            return false;
        }
    }
}