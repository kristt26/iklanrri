<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $allowedFields = ['id', 'username', 'password', 'email', 'login_oauth_uid', 'first_name', 'last_name', 'profile_picture', 'fullname', 'status', 'alamat', 'kontak'];
    protected $encrypter;
    protected $db;
    public function __construct()
    {
        $this->encrypter = \Config\Services::encrypter();
        $this->db = \Config\Database::connect();
    }

    public function check()
    {
        if ($this->db->table('user')->countAllResults(false) == 0) {
            $this->db->transBegin();
            $user = [
                "username" => "Administrator",
                "password" => base64_encode($this->encrypter->encrypt("Admin@123")),
                "email" => "admin@mail.com",
                "fullname" => "Administrator",
                "status" => "1",
            ];
            $this->db->table('user')->insert($user);
            $userid = $this->db->insertID();
            $role = [
                "role" => "Admin",
            ];
            $this->db->table('role')->insert($role);
            $roleid = $this->db->insertID();
            $roleuser = [
                'userid' => $userid,
                'roleid' => $roleid,
            ];
            $this->db->table('userinrole')->insert($roleuser);

            $user = [
                "username" => "Siaran",
                "password" => base64_encode($this->encrypter->encrypt("Admin@123")),
                "email" => "siaran@mail.com",
                "fullname" => "Siaran",
                "status" => "1",
            ];
            $this->db->table('user')->insert($user);
            $userid = $this->db->insertID();
            $role = [
                "role" => "Siaran",
            ];
            $this->db->table('role')->insert($role);
            $roleid = $this->db->insertID();
            $roleuser = [
                'userid' => $userid,
                'roleid' => $roleid,
            ];
            $this->db->table('userinrole')->insert($roleuser);
            $role = [
                "role" => "Pemesan",
            ];
            $this->db->table('role')->insert($role);
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return true;
            } else {
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
            `user`.`login_oauth_uid`,
            `user`.`first_name`,
            `user`.`last_name`,
            `user`.`profile_picture`,
            `user`.`created_at`,
            `user`.`updated_at`,
            `user`.`fullname`,
            `user`.`status`,
            `role`.`role`
        FROM
            `user`
            LEFT JOIN `userinrole` ON `userinrole`.`userid` = `user`.`id`
            LEFT JOIN `role` ON `role`.`id` = `userinrole`.`roleid` WHERE username='$username'")->getRowArray();
        if ($result) {
            if ($result['status'] != "0") {
                $p = $this->encrypter->decrypt(base64_decode($result['password']));
                if ($p == $data['password']) {
                    return $result;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
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
        $this->db->table('user')->update($data, ['id' => $id]);
    }

    public function updateUser($data, $id)
    {
        $this->db->table('user')->update($data, ['login_oauth_uid' => $id]);
    }
    public function insertUser($data)
    {
        $this->db->transBegin();
        $role = $this->db->table('role')->where('role', 'Pemesan')->get()->getRowArray();
        $this->db->table('user')->insert($data);
        $userid = $this->db->insertID();
        $userinrole = [
            'userid' => $userid,
            'roleid' => $role['id'],
        ];
        $this->db->table('userinrole')->insert($userinrole);
        if ($this->db->transStatus()) {
            $this->db->transCommit();
            return $userid;
        } else {
            $this->db->transRollback();
            return false;
        }
    }
}
