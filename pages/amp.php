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
require_once(DOL_DOCUMENT_ROOT . "/flightballoon/bbc_baskets.class.php");
require_once(DOL_DOCUMENT_ROOT . "/flightballoon/bbc_burners.class.php");
require_once(DOL_DOCUMENT_ROOT . "/flightballoon/bbc_compositions.class.php");
require_once(DOL_DOCUMENT_ROOT . "/flightballoon/bbc_enveloppes.class.php");
require_once(DOL_DOCUMENT_ROOT . "/flightballoon/bbc_fuels.class.php");
require_once(DOL_DOCUMENT_ROOT . "/flightballoon/bbc_instruments.class.php");
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

function update_button($type = -1, $balID) {
    switch ($type) {
        case 1:
            print 'Enveloppe';
            print '<a class="butAction" href="amp.php?bal=' . $balID . '&pieceType=' . $type . '&action=update">Update</a>';
            break;
        case 2:
            print 'Burner';
            print '<a class="butAction" href="amp.php?bal=' . $balID . '&pieceType=' . $type . '&action=update">Update</a>';
            break;
        case 3:
            print 'Basket';
            print '<a class="butAction" href="amp.php?bal=' . $balID . '&pieceType=' . $type . '&action=update">Update</a>';
            break;
        case 4:
            print 'Fuels';
            print '<a class="butAction" href="amp.php?bal=' . $balID . '&pieceType=' . $type . '&action=update">Update</a>';
            break;
        case 5:
            print 'Instruments';
            print '<a class="butAction" href="amp.php?bal=' . $balID . '&pieceType=' . $type . '&action=update">Update</a>';
            break;
    }
}

/* * *****************************************************************
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 * ****************************************************************** */
//Join a piece to a balloon
if (isset($_POST['action']) && $_POST['action'] == 'update' && $_POST['submit'] != 'cancel') {
    $combinaison = new Bbc_compositions($db);
    $combinaison->fk_balloon = $_POST['bal'];
    $combinaison->fk_piece = $_POST['piece'];
    $combinaison->piece_type = $_POST['pieceType'];
    if ($combinaison->update($user) < 0) {
        if ($combinaison->create($user) < 0) {
            $msg = 'Error While connect balloon to a piece.';
        }
    }
}

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
$html = New Form($db);
if ($_GET["action"] == 'delete') {
    $ret = $html->form_confirm("fiche.php?bal=" . $_GET['bal'], "Delete Balloon", "Etes vous sure de vouloir supprimer ce ballon?", 'deleteconfirm');
    if ($ret == 'html')
        print '<br>';
}
if ($msg) {
    print $msg;
}
/** FETCHES * */
$balloon = New Bbc_ballons($db);
$balloon->fetch(GETPOST('bal'));
$pilot = New User($db);
$pilot->fetch($balloon->fk_responsable);
$sqlCompo = 'SELECT rowid FROM llx_bbc_compositions WHERE fk_balloon = ' . $balloon->id . ' ORDER BY piece_type';
$resultSQLCompo = $db->query($sqlCompo);

/** TAB * */
print '<div class="tabs">';
print '<a class="tabTitle"><img src="/theme/eldy/img/object_user.png" border="0" alt="" title=""> Fiche Ballon</a>'; //title
print '<a class="tab" href="fiche.php?bal=' . GETPOST('bal') . '">Fiche Ballon</a>';
print '<a id="active" class="tab" href="amp.php?bal=' . GETPOST('bal') . '">AMP</a>';
print '</div>';
/** END TAB * */
/* * *** TAB CONTENT **** */
print '<div class="tabBar">';
//si l'action est edit et que l'utilisateur a le droit de modifier
if (isset($_GET['action']) && $_GET['action'] == 'edit' && $user->rights->flightballoon->bal->edit) {
    
} else {
    //balloon data
    print '<table class="border" width="100%">';
    print '<tr><td>identifiant</td><td>' . $balloon->id . '</td></tr>';
    print '<tr><td>Immatriculation </td><td>' . $balloon->immat . '</td></tr>';
    print '<tr><td>Responsable </td><td><a href="' . DOL_URL_ROOT . '/user/fiche.php?id=' . $pilot->id . '">' . img_object($langs->trans("ShowUser"), "user") . $pilot->getFullName($langs, 0, 0) . '</a></td></tr>';

    if ($resultSQLCompo) {
        $totalCompo = $db->num_rows($resultSQLCompo);
        $i = 0;
        while ($i < $totalCompo || $i < 5) {
            $i++;
            $compositionID = $db->fetch_object($resultSQLCompo); //id composition
            $balComposition = new Bbc_compositions($db);
            $balComposition->fetch($compositionID->rowid);
            print '<tr>';
            print '<td>';
//            if ($balComposition->piece_type) {
//                update_button($balComposition->piece_type, $balloon->id);
//            } else {
                update_button($i, $balloon->id);
//            }
            print '</td>';

            //form table
            print '<td>';
            if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['pieceType']) && $_GET['pieceType'] == $i) {
                print '<form method="post" action="amp.php">';
                print '<input type="hidden" value="update" name="action"/>';
                print '<input type="hidden" value="' . $balloon->id . '" name="bal"/>';
                print '<input type="hidden" value="' . $_GET['pieceType'] . '" name="pieceType"/>';
                print '<table width="100%">';
                print '<tr>';
                print '<td>';
                switch ((int) $_GET['pieceType']) {
                    case 1:
                        $html->select_bbc_enveloppe($balComposition->fk_piece, 'piece');
                        break;
                    case 2:
                        $html->select_bbc_burner($balComposition->fk_piece, 'piece');
                        break;
                    case 3:
                        $html->select_bbc_basket($balComposition->fk_piece, 'piece');
                        break;
                    case 4:
                        $html->select_bbc_fuels($balComposition->fk_piece, 'piece');
                        break;
                    case 5:
                        $html->select_bbc_instruments($balComposition->fk_piece, 'piece');
                        break;
                }
                print '</td>';
                print '<td>';
                print '<input class="button" type="submit" name="submit" value="modifier"></center>';
                print '<input class="button" type="submit" name="submit" value="cancel"></center>';
                print '</td></tr>';
                print '</table>';
                print '</form>';
            }
            elseif ($compositionID) {
                //information table
                print '<table width="100%">';
//                print '<tr><td>HELLO</td><td>WORLD</td></tr>';
//                switch ($balComposition->piece_type) {
                switch ($i) {
                    case 1:
                        $enveloppe = new Bbc_enveloppes($db);
                        $enveloppe->fetch($balComposition->fk_piece);
                        print '<tr><td>Manufacturer</td><td>' . $enveloppe->manufacturer . '</td></tr>';
                        print '<tr><td>Model</td><td>' . $enveloppe->model . '</td></tr>';
                        print '<tr><td>SN</td><td>' . $enveloppe->serialnumber . '</td></tr>';
                        print '<tr><td>Registration</td><td>' . $enveloppe->registration . '</td></tr>';
                        break;
                    case 2:
                        $bruleur = new Bbc_burners($db);
                        $bruleur->fetch($balComposition->fk_piece);
                        print '<tr><td>Manufacturer</td><td>' . $bruleur->manufacturer . '</td></tr>';
                        print '<tr><td>Model</td><td>' . $bruleur->model . '</td></tr>';
                        print '<tr>';
                        print '<td>SN</td><td>';
                        foreach ($bruleur->sn as $snID) {
                            $snBruleur = new Bbc_burners_sn($db);
                            $snBruleur->fetch($snID);
                            print $snBruleur->serialnumber . ' ';
                        }
                        print '</td>';
                        print '</tr>';
                        print '<tr><td>Frame model</td><td>' . $bruleur->framemodel . '</td></tr>';
                        print '<tr><td>Frame Number</td><td>' . $bruleur->framenumber . '</td></tr>';
                        break;
                    case 3:
                        $nacelle = new Bbc_baskets($db);
                        $nacelle->fetch($balComposition->fk_piece);
                        print '<tr><td>Manufacturer</td><td>' . $nacelle->manufacturer . '</td></tr>';
                        print '<tr><td>Model</td><td>' . $nacelle->model . '</td></tr>';
                        print '<tr><td>SN</td><td>' . $nacelle->serialnumber . '</td></tr>';
                        break;
                    case 4:
                        $bonbonne = new Bbc_fuels($db);
                        $bonbonne->fetch($balComposition->fk_piece);
                        break;
                    case 5:
                        $instrument = new Bbc_instruments($db);
                        $instrument->fetch($balComposition->fk_piece);
                        print '<tr><td>Manufacturer</td><td>' . $instrument->manufacturer . '</td></tr>';
                        print '<tr><td>Model</td><td>' . $instrument->model . '</td></tr>';
                        print '<tr><td>SN</td><td>' . $instrument->serialnumber . '</td></tr>';
                        break;
                }
                print '</table>';
            }
            print '</td>';
            print '</tr>';
        }
    }
    print '</table>';
}
print '</div>';


/* * ** TAB ACTION **** */
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
