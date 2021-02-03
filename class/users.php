<?php

class Users {

    public $db;
    public $mesin;

    public function __construct() 
    {
        $this->db = new DB_Class();
        $this->mesin = new Mesin();
    }

    public function login($nip, $password)
    {
        $password = md5($password);
        $result = $this->db->konek()->query("SELECT * FROM users WHERE username='$nip' AND password = '$password'");
        $user_data = $result->fetch_assoc();
        $rows = $result->num_rows;
        if ($rows == 1)
        {
            $_SESSION['login'] = true;
            $_SESSION['nip'] = $user_data['username'];
            $_SESSION['posisi'] = $user_data['posisi'];
            return TRUE;
        } else{
            return FALSE;
        }
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['login']);
        unset($_SESSION['nip']);
        unset($_SESSION['posisi']);
        session_destroy();
        return TRUE;
    }

    public function changePassword($pass1, $pass2, $pass3)
    {
        $nip = $_SESSION['nip'];
        $pass1 = md5($pass1);
        $result = $this->db->konek()->query("SELECT * FROM users WHERE username='$nip'");
        $data = $result->fetch_assoc();
        $passdb = $data['password'];
        if ($passdb == $pass1):
            if ($pass2 == $pass3) :
                $pass2 = md5($pass2);
                $result = $this->db->konek()->query("UPDATE users SET password='$pass2' WHERE username='$nip'");
                if ($result) : 
                    return TRUE;
                else :
                    return FALSE;
                endif;
            else :
                return FALSE;
            endif;
        else :
            return FALSE;
        endif;
    }

    public function getUsers($nip)
    {
        $result = $this->db->konek()->query("SELECT * FROM users WHERE username='$nip'");
        while ($row = $result->fetch_assoc())
            $data[] = $row;
        return $data;
    }

    public function getNamaUsers($nip)
    {
        $result = $this->db->konek()->query("SELECT * FROM users WHERE username='$nip'");
        $user_data = $result->fetch_assoc();
        
        $nama = $user_data['nama'];
        return $nama;
    }

    public function vUsers()
    {
        $result = $this->db->konek()->query("SELECT * FROM users WHERE posisi!='Admin'");
        $numrow = $result->num_rows;
        while ($row = $result->fetch_assoc())
            $data[] = $row;
        if($numrow > 0):
            return $data;
        else :
            return FALSE;
        endif;
    }

    public function addUsers($nama, $id, $posisi, $lahir)
    {
        $username = $_SESSION['nip'];

        $password = md5(preg_replace("/[^0-9]/", "", $lahir));
        $result = $this->db->konek()->query("INSERT INTO users (nama, username, password, posisi, ttl, create_by) VALUES ('$nama', '$id', '$password', '$posisi', '$lahir', '$username')");
        if ($result):
            $usersOnMachine = $this->usersOnMachine($nama, $id);
            if ($usersOnMachine) :
                return TRUE;
            else :
                return FALSE;
            endif;
        else :
            return FALSE;
        endif;
    }

    public function editUsers($nama, $id, $posisi, $lahir)
    {        
            $password = md5(preg_replace("/[^0-9]/", "", $lahir));
            $result = $this->db->konek()->query("UPDATE users SET nama='$nama', password='$password', posisi='$posisi', ttl='$lahir' WHERE username='$id'");
            if ($result):
                $usersOnMachine = $this->usersOnMachine($nama, $id);
                if ($usersOnMachine) :
                    return TRUE;
                else :
                    return FALSE;
                endif;
            else :
                return FALSE;
            endif;
    }

    public function usersOnMachine($nama, $id)
    {
        $result = $this->db->konek()->query("SELECT * FROM config_mesin WHERE id='1'");
        $row = $result->fetch_assoc();
        $IP = $row['ip'];
        $Key = $row['kunci'];

        $Connect = fsockopen($IP, "80", $errno, $errstr, 1);
        if($Connect){
            $soap_request="<SetUserInfo><ArgComKey Xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN>".$id."</PIN><Name>".$nama."</Name></Arg></SetUserInfo>";
            $newLine="\r\n";
            fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
            fputs($Connect, "Content-Type: text/xml".$newLine);
            fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
            fputs($Connect, $soap_request.$newLine);
            $buffer="";
            while($Response=fgets($Connect, 1024)){
                $buffer=$buffer.$Response;
            }
        } else {
            return FALSE;
        }

        $buffer = $this->mesin->Parse_Data($buffer,"<Information>","</Information>");
        if ($buffer) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function hapusUser($id)
    {
        $mesin = $this->mesin->getMesin();
        foreach ($mesin as $mesin):
            $IP = $mesin['ip'];
            $Key = $mesin['kunci'];
        endforeach;

        $Connect = fsockopen($IP, "80", $errno, $errstr, 1);
        if($Connect){
            $soap_request="<DeleteUser><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">".$id."</PIN></Arg></DeleteUser>";
            $newLine="\r\n";
            fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
            fputs($Connect, "Content-Type: text/xml".$newLine);
            fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
            fputs($Connect, $soap_request.$newLine);
            $buffer="";
            while($Response=fgets($Connect, 1024)){
                $buffer=$buffer.$Response;
            }
        } else {
            return FALSE;
        }
        $buffer = $this->mesin->Parse_Data($buffer,"<DeleteUserResponse>","</DeleteUserResponse>");
        if ($buffer):
            $buffer = $this->mesin->Parse_Data($buffer,"<Information>","</Information>");
            if ($buffer):
                $result = $this->db->konek()->query("DELETE FROM users WHERE username='$id'");
                if ($result):
                    return TRUE;
                else :
                    return FALSE;
                endif;
            else :
                return FALSE;
            endif;
        else :
            return FALSE;
        endif;
    }

    public function formatTanggal($lahir)
    {
        $pecah = explode('/', $lahir);
        return $pecah[2].'-'.$pecah[1].'-'.$pecah[0];
    }
}