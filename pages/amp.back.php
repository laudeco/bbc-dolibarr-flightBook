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
require_once(DOL_DOCUMENT_ROOT . "/flightlog/bbc_vols.class.php");
require_once(DOL_DOCUMENT_ROOT . "/flightballoon/bbc_ballons.class.php");
require_once(DOL_DOCUMENT_ROOT . "/flightlog/bbc_types.class.php");
// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

// Get parameters
$myparam = isset($_GET["myparam"]) ? $_GET["myparam"] : '';

// Protection if external user
if ($user->societe_id > 0) {
    //accessforbidden();
}



/* * *****************************************************************
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 * ****************************************************************** */
if ($_POST['action'] == 'deleteconfirm' && $_POST['confirm'] == 'yes') {
    if ($_GET['bal']) {
        $tmp = New Bbc_ballons($db);
        $tmp->fetch($_GET['bal']);
        if (isset($tmp->id) && ($user->rights->flightballoon->bal->del)) {
            if ($tmp->delete($user) > 0) {
                Header("Location: balloons.php");
            }
        }
    }
}

if ($_POST['action'] == 'edit' && !$_POST['cancel']) {
    $bal = New Bbc_ballons($db);
    $bal->fetch($_POST['bal']);
    $dated = dol_mktime(12, 0, 0, $_POST["remonth"], $_POST["reday"], $_POST["reyear"]);

    $bal->date = $dated;
    $bal->init_heure = $_POST['heures'];
    $bal->immat = $_POST['immat'];
    $bal->fk_responsable = $_POST['resp'];

    if ($bal->update($user) < 0) {
        $msg = '<div class="error">Erreur lors de la MAJ</div>';
        $error++;
    } else {
        Header("Location: balloons.php");
    }
}





/* * *************************************************
 * PAGE
 *
 * Put here all code to build page
 * ************************************************** */

llxHeader('', 'Carnet de vol', '');
if ($_GET["action"] == 'delete') {
    $html = New Form($db);
    $ret = $html->form_confirm("fiche.php?bal=" . $_GET['bal'], "Delete Balloon", "Etes vous sure de vouloir supprimer ce ballon?", 'deleteconfirm');
    if ($ret == 'html')
        print '<br>';
}
if ($msg) {
    print $msg;
}
// Put here content of your page
$balloon = New Bbc_ballons($db);
$balloon->fetch(GETPOST('bal'));
$pilot = New User($db);
$pilot->fetch($balloon->fk_responsable);

/** TAB **/
print '<div class="tabs">';
print '<a class="tabTitle"><img src="/theme/eldy/img/object_user.png" border="0" alt="" title=""> Fiche Ballon</a>'; //title
print '<a class="tab" href="fiche.php?bal='.  GETPOST('bal').'">Fiche Ballon</a>';
print '<a id="active" class="tab" href="amp.php?bal='.  GETPOST('bal').'">AMP</a>';
//print '<a class="tab" href="/categories/categorie.php?id=94&amp;type=3">Cat�gories</a>';
//print '<a class="tab" href="/adherents/note.php?id=94">Note</a>';
//print '<a class="tab" href="/adherents/document.php?id=94">Fichiers joints</a>';
//print '<a class="tab" href="/adherents/info.php?id=94">Suivi</a>';
print '</div>';
/** END TAB **/


/***** TAB CONTENT *****/
print '<div class="tabBar">';
//si l'action est edit et que l'utilisateur a le droit de modifier
if (isset($_GET['action']) && $_GET['action'] == 'edit' && $user->rights->flightballoon->bal->edit) {
    $html = new Form($db);
    $datec = dol_mktime(12, 0, 0, $_POST["remonth"], $_POST["reday"], $_POST["reyear"]);


    // Put here content of your page
    print "<form name='add' action=\"fiche.php\" method=\"post\">\n";
    print '<input type="hidden" name="action" value="edit"/>';
    print '<input type="hidden" name="bal" value="' . $balloon->id . '"/>';
    print '<table class="border" width="100%">';
    print '<tr><td>identifiant</td><td>' . $balloon->id . '</td></tr>';
    //date du vol
    print "<tr>";
    print '<td class="fieldrequired"> Date du bapteme</td><td>';
    print $html->select_date($balloon->date, '', '', '', '', 'add', 1, 1);
    print '</td></tr>';
    //nbr heure initiales
    print "<tr>";
    print '<td class="fieldrequired"> Nombre d\'heure initiales</td><td>';
    print '<input type="number" name="heures" value="'.$balloon->init_heure.'"/>';
    print '</td></tr>';
    //Immatriculation
    print "<tr>";
    print '<td class="fieldrequired"> Immatriculation </td><td>';
    print '<input type="text" name="immat" calss="flat" value="' . $balloon->immat . '"/>';
    print '</td></tr>';
    //responsable
    print "<tr>";
    print '<td width="25%" class="fieldrequired">Responsable </td><td>';
    print $html->select_users($pilot->id, 'resp', 1);
    print '</td></tr>';
    print '</table>';

    print '<br><center><input class="button" type="submit" value="' . $langs->trans("Save") . '"> &nbsp; &nbsp; ';
    print '<input class="button" type="submit" name="cancel" value="' . $langs->trans("Cancel") . '"></center';

    print '</form>';
} else {
    print '<table class="border" width="100%">';
    print '<tr><td>identifiant</td><td>' . $balloon->id . '</td></tr>';
    print '<tr><td>Immatriculation </td><td>' . $balloon->immat . '</td></tr>';
    print '<tr><td>Date du bapteme </td><td>' . dol_print_date($balloon->date) . '</td></tr>';
    print '<tr><td>Responsable </td><td><a href="' . DOL_URL_ROOT . '/user/fiche.php?id=' . $pilot->id . '">' . img_object($langs->trans("ShowUser"), "user") . $pilot->getFullName($langs, 0, 0) . '</a></td></tr>';

    print '</table>';
}
print '</div>';



/**** TAB ACTION *****/
print '<div class="tabsAction">';

if (!isset($_GET['action'])) {
    //supprimer
    //si l'utilisateur a le droit de suppression ou que c'est son vol
    if ($user->rights->flightballoon->bal->delete || $user->admin) {
        print '<a class="butActionDelete" href="fiche.php?action=delete&bal=' . $balloon->id . '">' . $langs->trans('Delete') . '</a>';
    } else {
        print '<a class="butActionRefused" href="#" title="' . dol_escape_htmltag($langs->trans("NotAllowed")) . '">' . $langs->trans('Delete') . '</a>';
    }

    //bouton modifier si on a le droit ou si c'est son vol
    if ($user->rights->flightballoon->bal->edit || $user->admin) {
        print '<a class="butAction" href="fiche.php?action=edit&bal=' . $balloon->id . '">' . $langs->trans('Edit') . '</a>';
    } else {
        print '<a class="butActionRefused" href="#" title="' . dol_escape_htmltag($langs->trans("NotAllowed")) . '">' . $langs->trans('Edit') . '</a>';
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
