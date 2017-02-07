<?php

/* Copyright (C) 2007-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *      \file       dev/skeletons/bbc_reservations.class.php
 *      \ingroup    mymodule othermodule1 othermodule2
 *      \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 * 		\version    $Id: bbc_reservations.class.php,v 1.32 2011/07/31 22:21:58 eldy Exp $
 * 		\author		Put author name here
 * 		\remarks	Initialy built by build_class_from_table on 2013-03-31 17:11
 */
// Put here all includes required by your class file
//require_once(DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php");
//require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
//require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");

/**
 *      \class      Bbc_reservations
 *      \brief      Put here description of your class
 * 		\remarks	Initialy built by build_class_from_table on 2013-03-31 17:11
 */
class Bbc_reservations {

    var $db;       //!< To store db handler
    var $error;       //!< To return error code (or message)
    var $errors = array();    //!< To return several error codes (or messages)
//var $element='bbc_reservations';			//!< Id that identify managed objects
//var $table_element='bbc_reservations';	//!< Name of table without prefix where object is stored
    var $id;
    var $commentaire;
    var $pilote;
    var $nom;
    var $prenom;
    var $nbrpax;
    var $mail;
    var $region;
    var $phone;
    var $type;
    var $state;
    var $message;

    /**
     *      Constructor
     *      @param      DB      Database handler
     */
    function Bbc_reservations($DB) {
        $this->db = $DB;
        return 1;
    }

    /**
     *      Create object into database
     *      @param      user        	User that create
     *      @param      notrigger	    0=launch triggers after, 1=disable triggers
     *      @return     int         	<0 if KO, Id of created object if OK
     */
    function create($user, $notrigger = 0) {
        global $conf, $langs;
        $error = 0;

// Clean parameters

        if (isset($this->id))
            $this->id = trim($this->id);
        if (isset($this->commentaire))
            $this->commentaire = trim($this->commentaire);
        if (isset($this->pilote))
            $this->pilote = trim($this->pilote);
        if (isset($this->nom))
            $this->nom = trim($this->nom);
        if (isset($this->prenom))
            $this->prenom = trim($this->prenom);
        if (isset($this->nbrpax))
            $this->nbrpax = trim($this->nbrpax);
        if (isset($this->mail))
            $this->mail = trim($this->mail);
        if (isset($this->region))
            $this->region = trim($this->region);
        if (isset($this->phone))
            $this->phone = trim($this->phone);
        if (isset($this->type))
            $this->type = trim($this->type);
        if (isset($this->state))
            $this->state = trim($this->state);
        if(isset($this->message))
            $this->message = trim($this->message);

// Check parameters
// Put here code to add control on parameters values
// Insert request
        $sql = "INSERT INTO " . MAIN_DB_PREFIX . "bbc_reservations(";

        $sql.= "commentaire,";
        $sql.= "pilote,";
        $sql.= "nom,";
        $sql.= "prenom,";
        $sql.= "nbrpax,";
        $sql.= "mail,";
        $sql.= "region,";
        $sql.= "phone,";
        $sql.= "type,";
        $sql.= "state,";
        $sql.= "message";


        $sql.= ") VALUES (";

        $sql.= " " . (!isset($this->commentaire) ? 'NULL' : "'" . $this->db->escape($this->commentaire) . "'") . ",";
        $sql.= " " . (!isset($this->pilote) ? 'NULL' : "'" . $this->pilote . "'") . ",";
        $sql.= " " . (!isset($this->nom) ? 'NULL' : "'" . $this->db->escape($this->nom) . "'") . ",";
        $sql.= " " . (!isset($this->prenom) ? 'NULL' : "'" . $this->db->escape($this->prenom) . "'") . ",";
        $sql.= " " . (!isset($this->nbrpax) ? 'NULL' : "'" . $this->nbrpax . "'") . ",";
        $sql.= " " . (!isset($this->mail) ? 'NULL' : "'" . $this->db->escape($this->mail) . "'") . ",";
        $sql.= " " . (!isset($this->region) ? 'NULL' : "'" . $this->db->escape($this->region) . "'") . ",";
        $sql.= " " . (!isset($this->phone) ? 'NULL' : "'" . $this->db->escape($this->phone) . "'") . ",";
        $sql.= " " . (!isset($this->type) ? 'NULL' : "'" . $this->type . "'") . ",";
        $sql.= " " . (!isset($this->state) ? 'NULL' : "'" . $this->state . "'") . ",";
        $sql.= " " . (!isset($this->message) ? 'NULL' : "'" . $this->db->escape($this->message) . "'") . "";


        $sql.= ")";

        $this->db->begin();

        dol_syslog(get_class($this) . "::create sql=" . $sql, LOG_DEBUG);
        $resql = $this->db->query($sql);
        if (!$resql) {
            $error++;
            $this->errors[] = "Error " . $this->db->lasterror();
        }

        if (!$error) {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX . "bbc_reservations");

            if (!$notrigger) {
// Uncomment this and change MYOBJECT to your own tag if you
// want this action call a trigger.
//// Call triggers
//include_once(DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php");
//$interface=new Interfaces($this->db);
//$result=$interface->run_triggers('MYOBJECT_CREATE',$this,$user,$langs,$conf);
//if ($result < 0) { $error++; $this->errors=$interface->errors; }
//// End call triggers
            }
        }

// Commit or rollback
        if ($error) {
            foreach ($this->errors as $errmsg) {
                dol_syslog(get_class($this) . "::create " . $errmsg, LOG_ERR);
                $this->error.=($this->error ? ', ' . $errmsg : $errmsg);
            }
            $this->db->rollback();
            return -1 * $error;
        } else {
            $this->db->commit();
            return $this->id;
        }
    }

    /**
     *    Load object in memory from database
     *    @param      id          id object
     *    @return     int         <0 if KO, >0 if OK
     */
    function fetch($id) {
        global $langs;
        $sql = "SELECT";
        $sql.= " t.rowid,";

        $sql.= " t.commentaire,";
        $sql.= " t.pilote,";
        $sql.= " t.nom,";
        $sql.= " t.prenom,";
        $sql.= " t.nbrpax,";
        $sql.= " t.mail,";
        $sql.= " t.region,";
        $sql.= " t.phone,";
        $sql.= " t.type,";
        $sql.= " t.state,";
        $sql.= " t.message";


        $sql.= " FROM " . MAIN_DB_PREFIX . "bbc_reservations as t";
        $sql.= " WHERE t.rowid = " . $id;

        dol_syslog(get_class($this) . "::fetch sql=" . $sql, LOG_DEBUG);
        $resql = $this->db->query($sql);
        if ($resql) {
            if ($this->db->num_rows($resql)) {
                $obj = $this->db->fetch_object($resql);

                $this->id = $obj->rowid;
                $this->commentaire = $obj->commentaire;
                $this->pilote = $obj->pilote;
                $this->nom = $obj->nom;
                $this->prenom = $obj->prenom;
                $this->nbrpax = $obj->nbrpax;
                $this->mail = $obj->mail;
                $this->region = $obj->region;
                $this->phone = $obj->phone;
                $this->type = $obj->type;
                $this->state = $obj->state;
                $this->message = $obj->message;
            }
            $this->db->free($resql);

            return 1;
        } else {
            $this->error = "Error " . $this->db->lasterror();
            dol_syslog(get_class($this) . "::fetch " . $this->error, LOG_ERR);
            return -1;
        }
    }

    /**
     *      Update object into database
     *      @param      user        	User that modify
     *      @param      notrigger	    0=launch triggers after, 1=disable triggers
     *      @return     int         	<0 if KO, >0 if OK
     */
    function update($user = 0, $notrigger = 0) {
        global $conf, $langs;
        $error = 0;

// Clean parameters

        if (isset($this->id))
            $this->id = trim($this->id);
        if (isset($this->commentaire))
            $this->commentaire = trim($this->commentaire);
        if (isset($this->pilote))
            $this->pilote = trim($this->pilote);
        if (isset($this->nom))
            $this->nom = trim($this->nom);
        if (isset($this->prenom))
            $this->prenom = trim($this->prenom);
        if (isset($this->nbrpax))
            $this->nbrpax = trim($this->nbrpax);
        if (isset($this->mail))
            $this->mail = trim($this->mail);
        if (isset($this->region))
            $this->region = trim($this->region);
        if (isset($this->phone))
            $this->phone = trim($this->phone);
        if (isset($this->type))
            $this->type = trim($this->type);
        if (isset($this->state))
            $this->state = trim($this->state);
        if(isset($this->message))
            $this->message = trim($this->message);


// Check parameters
// Put here code to add control on parameters values
// Update request
        $sql = "UPDATE " . MAIN_DB_PREFIX . "bbc_reservations SET";

        $sql.= " commentaire=" . (isset($this->commentaire) ? "'" . $this->db->escape($this->commentaire) . "'" : "null") . ",";
        $sql.= " pilote=" . (isset($this->pilote) ? $this->pilote : "null") . ",";
        $sql.= " nom=" . (isset($this->nom) ? "'" . $this->db->escape($this->nom) . "'" : "null") . ",";
        $sql.= " prenom=" . (isset($this->prenom) ? "'" . $this->db->escape($this->prenom) . "'" : "null") . ",";
        $sql.= " nbrpax=" . (isset($this->nbrpax) ? $this->nbrpax : "null") . ",";
        $sql.= " mail=" . (isset($this->mail) ? "'" . $this->db->escape($this->mail) . "'" : "null") . ",";
        $sql.= " region=" . (isset($this->region) ? "'" . $this->db->escape($this->region) . "'" : "null") . ",";
        $sql.= " phone=" . (isset($this->phone) ? "'" . $this->db->escape($this->phone) . "'" : "null") . ",";
        $sql.= " type=" . (isset($this->type) ? $this->type : "null") . ",";
        $sql.= " state=" . (isset($this->state) ? $this->state : "null") . ",";
        $sql.= " message=" . (isset($this->message) ?"'". $this->db->escape($this->message)."'" : "null") . "";

        $sql.= " WHERE rowid=" . $this->id;

        $this->db->begin();

        dol_syslog(get_class($this) . "::update sql=" . $sql, LOG_DEBUG);
        $resql = $this->db->query($sql);
        if (!$resql) {
            $error++;
            $this->errors[] = "Error " . $this->db->lasterror();
        }

        if (!$error) {
            if (!$notrigger) {
// Uncomment this and change MYOBJECT to your own tag if you
// want this action call a trigger.
//// Call triggers
//include_once(DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php");
//$interface=new Interfaces($this->db);
//$result=$interface->run_triggers('MYOBJECT_MODIFY',$this,$user,$langs,$conf);
//if ($result < 0) { $error++; $this->errors=$interface->errors; }
//// End call triggers
            }
        }

// Commit or rollback
        if ($error) {
            foreach ($this->errors as $errmsg) {
                dol_syslog(get_class($this) . "::update " . $errmsg, LOG_ERR);
                $this->error.=($this->error ? ', ' . $errmsg : $errmsg);
            }
            $this->db->rollback();
            return -1 * $error;
        } else {
            $this->db->commit();
            return 1;
        }
    }

    /**
     *   Delete object in database
     * 	 @param     user        	User that delete
     *   @param     notrigger	    0=launch triggers after, 1=disable triggers
     *   @return	int				<0 if KO, >0 if OK
     */
    function delete($user, $notrigger = 0) {
        global $conf, $langs;
        $error = 0;

        $sql = "DELETE FROM " . MAIN_DB_PREFIX . "bbc_reservations";
        $sql.= " WHERE rowid=" . $this->id;

        $this->db->begin();

        dol_syslog(get_class($this) . "::delete sql=" . $sql);
        $resql = $this->db->query($sql);
        if (!$resql) {
            $error++;
            $this->errors[] = "Error " . $this->db->lasterror();
        }

        if (!$error) {
            if (!$notrigger) {
// Uncomment this and change MYOBJECT to your own tag if you
// want this action call a trigger.
//// Call triggers
//include_once(DOL_DOCUMENT_ROOT . "/core/class/interfaces.class.php");
//$interface=new Interfaces($this->db);
//$result=$interface->run_triggers('MYOBJECT_DELETE',$this,$user,$langs,$conf);
//if ($result < 0) { $error++; $this->errors=$interface->errors; }
//// End call triggers
            }
        }

// Commit or rollback
        if ($error) {
            foreach ($this->errors as $errmsg) {
                dol_syslog(get_class($this) . "::delete " . $errmsg, LOG_ERR);
                $this->error.=($this->error ? ', ' . $errmsg : $errmsg);
            }
            $this->db->rollback();
            return -1 * $error;
        } else {
            $this->db->commit();
            return 1;
        }
    }

    /**
     * 		Load an object from its id and create a new one in database
     * 		@param      fromid     		Id of object to clone
     * 	 	@return		int				New id of clone
     */
    function createFromClone($fromid) {
        global $user, $langs;

        $error = 0;

        $object = new Bbc_reservations($this->db);

        $this->db->begin();

// Load source object
        $object->fetch($fromid);
        $object->id = 0;
        $object->statut = 0;

// Clear fields
// ...
// Create clone
        $result = $object->create($user);

// Other options
        if ($result < 0) {
            $this->error = $object->error;
            $error++;
        }

        if (!$error) {
            
        }

// End
        if (!$error) {
            $this->db->commit();
            return $object->id;
        } else {
            $this->db->rollback();
            return -1;
        }
    }

    /**
     * 		Initialisz object with example values
     * 		Id must be 0 if object instance is a specimen.
     */
    function getType() {
        if($this->type == 0){
            return "vol";
        }
        return "info";
    }
    /**
     * 		Initialisz object with example values
     * 		Id must be 0 if object instance is a specimen.
     */
    function getState($detail = 0) {
		return img_picto($this->state,'statut'.$this->state);
    }
    /**
     * 		Initialisz object with example values
     * 		Id must be 0 if object instance is a specimen.
     */
    function initAsSpecimen() {
        $this->id = 0;

        $this->id = '';
        $this->commentaire = '';
        $this->pilote = '';
        $this->nom = '';
        $this->prenom = '';
        $this->nbrpax = '';
        $this->mail = '';
        $this->region = '';
        $this->phone = '';
        $this->type = '';
        $this->state = '';
    }

}

?>
