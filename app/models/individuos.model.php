<?php
include_once './config/config.php';
require_once './app/models/model.php';

class individuosModel extends model{
    
    function obtenerIndividuos() {
        $query = $this->db->prepare('SELECT * FROM individuos JOIN especies ON individuos.fk_id_especie = especies.id_especie');
        $query->execute();
        
        $individuo = $query->fetchAll(PDO::FETCH_OBJ);
        return $individuo;
    }

    function insertarIndividuo($nombre, $raza, $edad, $color, $personalidad, $fk_id_especie, $imagen) {
        $query = $this->db->prepare('INSERT INTO individuos (nombre, raza, edad, color, personalidad, fk_id_especie, imagen) VALUES(?,?,?,?,?,?,?)');
        $query->execute([$nombre, $raza, $edad, $color, $personalidad, $fk_id_especie, $imagen]);
        return $this->db->lastInsertId();
    }

    function borrarIndividuo($id) {
        $query = $this->db->prepare('DELETE FROM individuos WHERE id = ?');
        $query->execute([$id]);
    }
    
    function obtenerIndividuoPorID($id) {
        $query = $this->db->prepare('SELECT * FROM individuos JOIN especies ON individuos.fk_id_especie = especies.id_especie WHERE id = ?');
        $query->execute([$id]);
        $individuo = $query->fetch(PDO::FETCH_OBJ);
        return $individuo;
    }
    
    function modificarIndividuo($id, $nombre, $raza, $edad, $color, $personalidad, $fk_id_especie){
        $query = $this->db->prepare('UPDATE individuos SET nombre = ?, raza = ?, edad = ?, color = ?, personalidad = ?, fk_id_especie = ? WHERE id = ?');
        $query->execute([$nombre, $raza, $edad, $color, $personalidad, $fk_id_especie, $id]);
    }

    function obtenerIndividuosPorEspecie($id_especie){
        $query= $this->db->prepare('SELECT * FROM individuos i JOIN especies e ON e.id_especie = i.fk_id_especie WHERE fk_id_especie = ?');
        $query->execute([$id_especie]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function obtenerIndividuosOrdenados($orden) {
        $query = $this->db->prepare('SELECT * FROM individuos ORDER BY edad ' . $orden);
        $query->execute();
        
        $individuo = $query->fetchAll(PDO::FETCH_OBJ);
        return $individuo;
    }

    function obtenerIndividuosPorEdad($minEdad, $maxEdad) {
        $query = $this->db->prepare('SELECT * FROM individuos JOIN especies ON individuos.fk_id_especie = especies.id_especie WHERE edad >= ? AND edad <= ?');
        $query->execute([$minEdad, $maxEdad]);
        
        $individuo = $query->fetchAll(PDO::FETCH_OBJ);
        return $individuo;
    } 

    function obtenerIndividuosPaginados($numIndividuos, $offset) {
        $query = $this->db->prepare('SELECT * FROM individuos JOIN especies ON individuos.fk_id_especie = especies.id_especie ORDER BY individuos.id LIMIT :offset, :numIndividuos');
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);
        $query->bindParam(':numIndividuos', $numIndividuos, PDO::PARAM_INT);
        $query->execute();
        $individuos = $query->fetchAll(PDO::FETCH_OBJ);
        return $individuos;
    }
    
    
    function contarIndividuos() {
        $query = $this->db->prepare('SELECT COUNT(*) as totalIndividuos FROM individuos');
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result->totalIndividuos;
    }
}