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
require_once(DOL_DOCUMENT_ROOT . "/../htdocs/flightballoon/bbc_ballons.class.php");

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

// Get parameters
$myparam = isset($_GET["myparam"]) ? $_GET["myparam"] : '';

if (!$user->rights->flightBook->book->edit) {
    accessforbidden();
}


/* * *****************************************************************
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 * ****************************************************************** */
$msg = '';

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


/* * *************************************************
 * PAGE
 *
 * Put here all code to build page
 * ************************************************** */

llxHeader('', 'Ajout reservation', '');

$html = new Form($db);
$datec = dol_mktime(12, 0, 0, $_POST["remonth"], $_POST["reday"], $_POST["reyear"]);
if ($msg) {
    print $msg;
}
$html = new Form($db);

print "<form name='add' action=\"fiche.php\" method=\"post\">\n";

print '<input type="hidden" name="action" value="add"/>';

print '<table class="border" width="100%">';

print '<tr><td class="fieldrequired"> Nom </td><td><input type="text" name="nom" calss="flat" /></td></tr>';
print '<tr><td class="fieldrequired"> Prenom </td><td><input type="text" name="prenom" calss="flat" /></td></tr>';
print '<tr><td class="fieldrequired"> mail </td><td><input type="text" name="mail" calss="flat" /></td></tr>';
print '<tr><td> phone </td><td><input type="text" name="phone" calss="flat" /></td></tr>';

//TODFO !!!
print '<tr><td> Type </td><td><input type="radio" value="1" name="type"/> Info - <input type="radio" value="0" name="type"/> Vol</td></tr>';
print '<tr><td> Nbr Pax </td><td><input type="text" name="nbrpax" class="flat" /></td></tr>';
print '<tr><td> Region </td><td><input type="text" name="region" class="flat" /></td></tr>';
print '<tr><td> Message du client </td><td><textarea name="message"></textarea></td></tr>';
print '<tr><td> Responsable </td><td>';
print $html->select_users(($reservation->pilote ? $reservation->pilote : -1), 'pilote', true);
print '</td></tr>';
print '<tr><td> Commentaire </td><td><textarea name="commentaire"></textarea></td></tr>';
print '</table>';

print '<br><center><input class="button" type="submit" value="' . $langs->trans("Save") . '"> &nbsp; &nbsp; ';
print '<input class="button" type="submit" name="cancel" value="' . $langs->trans("Cancel") . '"></center';

print '</form>';


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
