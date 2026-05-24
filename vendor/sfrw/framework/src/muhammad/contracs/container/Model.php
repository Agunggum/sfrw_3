<?php

namespace muhammad\contracs\container;

class Model{

    public function dbget($order = 'ASC') {
        $tampil = permintaanMysql("SELECT ".$this->schemafilable()." FROM ".$this->schematable()." ORDER BY ".$this->schematable().".id ".$order);
        $data = [];
        if(barisAngkaMysql($tampil)){
            while($row = mysqlAmbilArray($tampil)) {
                $data[] = $row;
            }
        }
        return $data;
    }

}