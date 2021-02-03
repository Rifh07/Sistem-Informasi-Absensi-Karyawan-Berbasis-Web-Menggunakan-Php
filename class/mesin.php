<?php

class Mesin 
{
    public $db;

    public function __construct() 
    {
        $this->db = new DB_Class();
    }
    
    public function getMesin()
    {
        $result = $this->db->konek()->query("SELECT * FROM config_mesin WHERE id='1'");
        while ($row = $result->fetch_assoc())
            $data[] = $row;
        return $data;
    }

    public function setMesin($ip, $mesin, $key)
    {
        $result = $this->db->konek()->query("UPDATE config_mesin SET ip='$ip', nama_mesin='$mesin', kunci='$key' WHERE id='1'");
        if ($result):
            return TRUE;
        else :
            return FALSE;
        endif;
    }

    public function tarikData()
    {
        $username = $_SESSION['nip'];
        $result = $this->db->konek()->query("SELECT * FROM config_mesin WHERE id='1'");
        $row = $result->fetch_assoc();
        $IP = $row['ip'];
        $Key = $row['kunci'];

        $getDataAbsen = $this->getDataAbsen($IP, $Key);
        if ($getDataAbsen) :
            $deleteDataAbsen = $this->deleteDataAbsen($IP, $Key);
            if ($deleteDataAbsen) :
                $result = $this->db->konek()->query("INSERT INTO tarikdata_log (ip, username, status) VALUES ('$IP', '$username', 'Berhasil')");
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

    public function getDataAbsen($IP, $Key)
    {
        $Connect = fsockopen($IP, "80", $errno, $errstr, 1);
        if($Connect){
            $soap_request="<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
            $newLine="\r\n";
            fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
            fputs($Connect, "Content-Type: text/xml".$newLine);
            fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
            fputs($Connect, $soap_request.$newLine);
            $buffer="";
            while($Response=fgets($Connect, 1024)){
                $buffer=$buffer.$Response;
            }
        } else return FALSE;

        $buffer = $this->Parse_Data($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");

        $buffer=explode("\r\n",$buffer);
        for($a=0;$a<count($buffer);$a++){
            $data = $this->Parse_Data($buffer[$a],"<Row>","</Row>");
            $PIN = $this->Parse_Data($data,"<PIN>","</PIN>");
            $DateTime = $this->Parse_Data($data,"<DateTime>","</DateTime>");
            $Verified = $this->Parse_Data($data,"<Verified>","</Verified>");
            $Status = $this->Parse_Data($data,"<Status>","</Status>");
            if ($PIN) :
                if ($Status == 0) :
                    $Status = 'Masuk';
                else : 
                    $Status = 'Pulang';
                endif;
                $result = $this->db->konek()->query("INSERT INTO absensi  VALUES ('', '$PIN', '$DateTime', '$Status', '$Verified')");
            endif;
        }
        return TRUE;
    }

    public function deleteDataAbsen($IP, $Key)
    {
        $Connect = fsockopen($IP, "80", $errno, $errstr, 1);
        if($Connect){
            $soap_request="<ClearData><ArgComKey xsi:type=\"xsd:integer\">".$Key."</ArgComKey><Arg><Value xsi:type=\"xsd:integer\">3</Value></Arg></ClearData>";
            $newLine="\r\n";
            fputs($Connect, "POST /iWsService HTTP/1.0".$newLine);
            fputs($Connect, "Content-Type: text/xml".$newLine);
            fputs($Connect, "Content-Length: ".strlen($soap_request).$newLine.$newLine);
            fputs($Connect, $soap_request.$newLine);
            $buffer="";
            while($Response=fgets($Connect, 1024)){
                $buffer=$buffer.$Response;
            }
        }else return FALSE;
 
        $buffer = $this->Parse_Data($buffer,"<Information>","</Information>");
        return TRUE;
    }

    public function Parse_Data($data, $p1, $p2)
    {
        $data=" ".$data;
        $hasil="";
        $awal=strpos($data,$p1);
        if($awal!=""){
            $akhir=strpos(strstr($data,$p1),$p2);
            if($akhir!=""){
                $hasil=substr($data,$awal+strlen($p1),$akhir-strlen($p1));
            }
        }
        return $hasil;
    }

    public function getLog()
    {
        $result = $this->db->konek()->query("SELECT * FROM tarikdata_log  ORDER BY tgl DESC");
        while ($row = $result->fetch_assoc()){
            $data[] = $row;
        }
            return $data;
    }

    public function getLast()
    {
        $result = $this->db->konek()->query("SELECT * FROM tarikdata_log  ORDER BY tgl DESC LIMIT 1");
        $row = $result->fetch_assoc();
        if($row > 0):
            $data = $row['tgl'];
            return $data;
        else :
            return FALSE;
        endif;
        return $data;
    }
}
