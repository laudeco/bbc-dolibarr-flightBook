<?php

/* Copyright (C) 2007-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *   	\file       dev/skeletons/skeleton_page.php
 * 		\ingroup    mymodule othermodule1 othermodule2
 * 		\brief      This file is an example of a php page
 * 		\version    $Id: skeleton_page.php,v 1.19 2011/07/31 22:21:57 eldy Exp $
 * 		\author		Put author name here
 * 		\remarks	Put here some comments
 */
//if (! defined('NOREQUIREUSER'))  define('NOREQUIREUSER','1');
//if (! defined('NOREQUIREDB'))    define('NOREQUIREDB','1');
//if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');
//if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK','1');
//if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1');
//if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');	// If there is no menu to show
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');	// If we don't need to load the html.form.class.php
//if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
//if (! defined("NOLOGIN"))        define("NOLOGIN",'1');		// If this page is public (can be called outside logged session)
// Change this following line to use the correct relative path (../, ../../, etc)
$res = 0;
if (!$res && file_exists("../main.inc.php"))
    $res = @include("../main.inc.php");
if (!$res && file_exists("../../main.inc.php"))
    $res = @include("../../main.inc.php");
if (!$res && file_exists("../../../main.inc.php"))
    $res = @include("../../../main.inc.php");
if (!$res && file_exists("../../../dolibarr/htdocs/main.inc.php"))
    $res = @include("../../../dolibarr/htdocs/main.inc.php");     // Used on dev env only
if (!$res && file_exists("../../../../dolibarr/htdocs/main.inc.php"))
    $res = @include("../../../../dolibarr/htdocs/main.inc.php");   // Used on dev env only
if (!$res && file_exists("../../../../../dolibarr/htdocs/main.inc.php"))
    $res = @include("../../../../../dolibarr/htdocs/main.inc.php");   // Used on dev env only
if (!$res)
    die("Include of main fails");
// Change this following line to use the correct relative path from htdocs (do not remove DOL_DOCUMENT_ROOT)
require_once(DOL_DOCUMENT_ROOT . "/core/class/dolgraph.class.php");
require_once(DOL_DOCUMENT_ROOT . "/flightBook/bbc_reservations.class.php");
// Load traductions files requiredby by page
$langs->load("other");

// Protection if not allowed !
if (!$user->rights->flightBook->book->list) {
    accessforbidden();
}


/* * ******************
 * Constant
 */
define("STATE_INITIAL", 0);
define("STATE_CONTACTE", 1);
define("STATE_ATTRIBUE", 4);
define("STATE_ERROR", 8);
define("STATE_ANNULE", 2);

/* * *****************************************************************
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 * ****************************************************************** */
if ($_POST['action'] == 'deleteconfirm' && $_POST['confirm'] == 'yes') {
    if ($_GET['res']) {
        $tmp = New Bbc_reservations($db);
        $tmp->fetch($_GET['res']);
        if (isset($tmp->id) && ($user->rights->flightBook->book->edit)) {
            if ($tmp->delete($user) > 0) {
                Header("Location: list.php");
            }
        }
    }
}
//state : Prise de contact
if ($_POST['action'] == 'stateconfirm' && $_POST['confirm'] == 'yes') {
    if ($_GET['res']) {
        $tmp = New Bbc_reservations($db);
        $tmp->fetch($_GET['res']);
        $tmp->state =  intval($_GET['state']);
        if (isset($tmp->id) && ($user->rights->flightBook->book->edit)) {
            if ($tmp->update($user) > 0) {
                Header("Location: list.php");
            }
        }
    }
}

if ($_POST['action'] == 'edit' && !$_POST['cancel']) {
    $res = New Bbc_reservations($db);
    $res->fetch($_POST['res']);

    $res->commentaire = GETPOST('commentaire');
    $res->mail = GETPOST('mail');
    $res->nbrpax = GETPOST('nbrpax');
    $res->nom = GETPOST('nom');
    $res->prenom = GETPOST('prenom');
    $res->phone = GETPOST('phone');
    $res->pilote = GETPOST('pilote');
    $res->region = GETPOST('region');
    $res->type = GETPOST('type');
    $res->message = GETPOST('message');

    //set state
    if (GETPOST('pilote') >= 0) {
        $res->state = STATE_ATTRIBUE;
    }

    if ($res->update($user) < 0) {
        $msg = '<div class="error">Erreur lors de la MAJ</div>';
        $error++;
    } else {
        Header("Location: list.php");
    }
}

if ($_POST['action'] == 'add' && !$_POST['cancel']) {
    $res = New Bbc_reservations($db);

    $res->commentaire = GETPOST('commentaire');
    $res->mail = GETPOST('mail');
    $res->nbrpax = GETPOST('nbrpax');
    $res->nom = GETPOST('nom');
    $res->prenom = GETPOST('prenom');
    $res->phone = GETPOST('phone');
    $res->pilote = GETPOST('pilote');
    $res->region = GETPOST('region');
    $res->type = GETPOST('type');
    $res->message = GETPOST('message');

    //set state
    if (GETPOST('pilote') >= 0) {
        $res->state = STATE_ATTRIBUE;
    }else{
        $res->state = STATE_INITIAL;
    }

    if ($res->create($user) < 0) {
        $msg = '<div class="error">Erreur lors de la MAJ</div>';
        $error++;
    } else {
        Header("Location: list.php");
    }
}
if ($_POST['action'] == 'edit' && !$_POST['cancel']) {
    $res = New Bbc_reservations($db);
    $res->fetch($_POST['res']);

    $res->commentaire = GETPOST('commentaire');
    $res->mail = GETPOST('mail');
    $res->nbrpax = GETPOST('nbrpax');
    $res->nom = GETPOST('nom');
    $res->prenom = GETPOST('prenom');
    $res->phone = GETPOST('phone');
    $res->pilote = GETPOST('pilote');
    $res->region = GETPOST('region');
    $res->type = GETPOST('type');
    $res->message = GETPOST('message');

    //set state
    if (GETPOST('pilote') >= 0) {
        $res->state = STATE_ATTRIBUE;
    }

    if ($res->update($user) < 0) {
        $msg = '<div class="error">Erreur lors de la MAJ</div>';
        $error++;
    } else {
        Header("Location: list.php");
    }
}





/* * *************************************************
 * PAGE
 *
 * Put here all code to build page
 * ************************************************** */

llxHeader('', 'Reservation', '');

if ($_GET["action"] == 'delete') {
    $html = New Form($db);
    $ret = $html->form_confirm("fiche.php?res=" . $_GET['res'], "Suppression d'une reservation", "Etes vous sure de vouloir supprimer cette reservation?", 'deleteconfirm');
    if ($ret == 'html')
        print '<br>';
}

//Action change the state
if ($_GET["action"] == 'state') {
    $html = New Form($db);
    $ret = $html->form_confirm("fiche.php?res=" . $_GET['res'] . "&state=" . $_GET['state'], "Changement de l'etat", "Etes vous sure de vouloir changer l'état de cette reservation?", 'stateconfirm', '', "yes");
    if ($ret == 'html')
        print '<br>';
}

if ($msg) {
    print $msg;
}

//fetch reservation and pilot
$reservation = New Bbc_reservations($db);
$reservation->fetch(GETPOST('res'));

if (isset($reservation->pilote)) {
    $pilot = new User($db);
    $pilot->fetch($reservation->pilote);
}


print '<div class="tabBar">';

//si l'action est ajouter et que l'utilisateur a les droits d'ajout
if (isset($_GET['action']) && $_GET['action'] == 'add' && $user->rights->flightBook->book->edit) {
    $html = new Form($db);

    print "<form name='add' action=\"fiche.php\" method=\"post\">\n";

    print '<input type="hidden" name="action" value="add"/>';
    print '<input type="hidden" name="res" value="' . $reservation->id . '"/>';
    print '<input type="hidden" name="type" value="' . $reservation->type . '"/>';
    print '<input type="hidden" name="message" value="' . $reservation->message . '"/>';

    print '<table class="border" width="100%">';

    print '<tr><td>identifiant</td><td>' . $reservation->id . '</td></tr>';
    print '<tr><td class="fieldrequired"> Nom </td><td><input type="text" name="nom" calss="flat" /></td></tr>';
    print '<tr><td class="fieldrequired"> Prenom </td><td><input type="text" name="prenom" calss="flat" /></td></tr>';
    print '<tr><td class="fieldrequired"> mail </td><td><input type="text" name="mail" calss="flat" /></td></tr>';
    print '<tr><td> phone </td><td><input type="text" name="phone" calss="flat" /></td></tr>';
    
    //TODFO !!!
    print '<tr><td> Type </td><td>' . $reservation->getType() . '</td></tr>';
    print '<tr><td> Nbr Pax </td><td><input type="text" name="nbrpax" class="flat" /></td></tr>';
    print '<tr><td> Region </td><td><input type="text" name="region" class="flat" /></td></tr>';
    print '<tr><td> Message du client </td><td><textarea name="message"></textarea></td></tr>';
    print '<tr><td> Responsable </td><td>';
    print $html->select_users(($reservation->pilote?$reservation->pilote:-1), 'pilote', true);
    print '</td></tr>';
    print '<tr><td> Commentaire </td><td><textarea name="commentaire"></textarea></td></tr>';
    print '</table>';

    print '<br><center><input class="button" type="submit" value="' . $langs->trans("Save") . '"> &nbsp; &nbsp; ';
    print '<input class="button" type="submit" name="cancel" value="' . $langs->trans("Cancel") . '"></center';

    print '</form>';
}

//si l'action est edit et que l'utilisateur a le droit de modifier
if (isset($_GET['action']) && $_GET['action'] == 'edit' && $user->rights->flightBook->book->edit) {
    $html = new Form($db);

    print "<form name='add' action=\"fiche.php\" method=\"post\">\n";

    print '<input type="hidden" name="action" value="edit"/>';
    print '<input type="hidden" name="res" value="' . $reservation->id . '"/>';
    print '<input type="hidden" name="type" value="' . $reservation->type . '"/>';
    print '<input type="hidden" name="message" value="' . $reservation->message . '"/>';

    print '<table class="border" width="100%">';

    print '<tr><td>identifiant</td><td>' . $reservation->id . '</td></tr>';
    print '<tr><td class="fieldrequired"> Nom </td><td><input type="text" name="nom" calss="flat" value="' . $reservation->nom . '"/></td></tr>';
    print '<tr><td class="fieldrequired"> Prenom </td><td><input type="text" name="prenom" calss="flat" value="' . $reservation->prenom . '"/></td></tr>';
    print '<tr><td class="fieldrequired"> mail </td><td><input type="text" name="mail" calss="flat" value="' . $reservation->mail . '"/></td></tr>';
    print '<tr><td> phone </td><td><input type="text" name="phone" calss="flat" value="' . $reservation->phone . '"/></td></tr>';
    print '<tr><td> Type </td><td>' . $reservation->getType() . '</td></tr>';
    print '<tr><td> Nbr Pax </td><td><input type="text" name="nbrpax" calss="flat" value="' . $reservation->nbrpax . '"/></td></tr>';
    print '<tr><td> Region </td><td><input type="text" name="region" calss="flat" value="' . $reservation->region . '"/></td></tr>';
    print '<tr><td> Message du client </td><td>' . $reservation->message. '</td></tr>';
    print '<tr><td> Responsable </td><td>';
    print $html->select_users(($reservation->pilote?$reservation->pilote:-1), 'pilote', true);
    print '</td></tr>';
    print '<tr><td> Commentaire </td><td><textarea name="commentaire">' . $reservation->commentaire . '</textarea></td></tr>';
    print '</table>';

    print '<br><center><input class="button" type="submit" value="' . $langs->trans("Save") . '"> &nbsp; &nbsp; ';
    print '<input class="button" type="submit" name="cancel" value="' . $langs->trans("Cancel") . '"></center';

    print '</form>';
} else {

    //DEFAULT VIEW !
    print '<table class="border" width="100%">';
    print '<tr><td> Nom </td><td>' . $reservation->nom . '</td></tr>';
    print '<tr><td> Prenom </td><td>' . $reservation->prenom . '</td></tr>';
    print '<tr><td> mail </td><td>' . $reservation->mail . '</td></tr>';
    print '<tr><td> phone </td><td>' . $reservation->phone . '</td></tr>';
    print '<tr><td> Type </td><td>' . $reservation->getType() . '</td></tr>';
    print '<tr><td> Nbr Pax </td><td>' . $reservation->nbrpax . '</td></tr>';
    print '<tr><td> Region </td><td>' . $reservation->region . '</td></tr>';
    print '<tr><td> State </td><td>' . $reservation->getState() . '</td></tr>';
    print '<tr><td> Message du client </td><td>' . $reservation->message . '</td></tr>';
    print '<tr><td> Responsable </td><td>' . $pilot->prenom . ' ' . $pilot->nom . '</td></tr>';
    print '<tr><td> Commentaire </td><td>' . $reservation->commentaire . '</td></tr>';
    print '</table>';
}
print '</div>';

/* * START BUTTONS* */
print '<div class="tabsAction">';
if (!isset($_GET['action'])) {
    //supprimer
    if ($user->rights->flightBook->book->edit) {
        print '<a class="butActionDelete" href="fiche.php?action=delete&res=' . $reservation->id . '">' . $langs->trans('Delete') . '</a>';
    } else {
        print '<a class="butActionRefused" href="#" title="' . dol_escape_htmltag($langs->trans("NotAllowed")) . '">' . $langs->trans('Delete') . '</a>';
    }

    if ($user->rights->flightBook->book->edit) {
        print '<a class="butAction" href="fiche.php?action=edit&res=' . $reservation->id . '">' . $langs->trans('Edit') . '</a>';
    } else {
        print '<a class="butActionRefused" href="#" title="' . dol_escape_htmltag($langs->trans("NotAllowed")) . '">' . $langs->trans('Edit') . '</a>';
    }

    /*     * ******
     * States
     */
    if ($reservation->state == STATE_CONTACTE) {
        if ($user->rights->flightBook->book->edit) {
            print '<a class="butAction" href="fiche.php?action=state&res=' . $reservation->id . '&state=' . STATE_ERROR . '">' . $langs->trans('Error') . '</a>';
        }
    }
    if ($reservation->state == STATE_INITIAL) {
        if ($user->rights->flightBook->book->edit) {
            print '<a class="butAction" href="fiche.php?action=state&res=' . $reservation->id . '&state=' . STATE_CONTACTE . '">' . $langs->trans('Contacte') . '</a>';
        }
    }
    if ($reservation->state == STATE_ATTRIBUE) {
        if ($user->rights->flightBook->book->edit) {
            print '<a class="butAction" href="fiche.php?action=state&res=' . $reservation->id . '&state=' . STATE_ERROR . '">' . $langs->trans('Error') . '</a>';
        }
    }
    if ($reservation->state == STATE_ANNULE) {
        if ($user->rights->flightBook->book->edit) {
            print '<a class="butAction" href="fiche.php?action=state&res=' . $reservation->id . '&state=' . STATE_CONTACTE . '">' . $langs->trans('Contacte') . '</a>';
        }
        if ($user->rights->flightBook->book->edit) {
            print '<a class="butAction" href="fiche.php?action=state&res=' . $reservation->id . '&state=' . STATE_ERROR . '">' . $langs->trans('Error') . '</a>';
        }
    }else{
        if ($user->rights->flightBook->book->edit) {
            print '<a class="butAction" href="fiche.php?action=state&res=' . $reservation->id . '&state=' . STATE_ANNULE . '">' . $langs->trans('Cancel') . '</a>';
        }
    }
}

print '</div>';
/* * *************************************************
 * LINKED OBJECT BLOCK
 *
 * Put here code to view linked object
 * ************************************************** */
//$somethingshown=$myobject->showLinkedObjectBlock();
// End of page
$db->close();
llxFooter('$Date: 2011/07/31 22:21:57 $ - $Revision: 1.19 $');
?>
