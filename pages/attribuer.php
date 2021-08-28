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
 *		\ingroup    mymodule othermodule1 othermodule2
 *		\brief      This file is an example of a php page
 *		\version    $Id: skeleton_page.php,v 1.19 2011/07/31 22:21:57 eldy Exp $
 *		\author		Put author name here
 *		\remarks	Put here some comments
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
$res=0;
if (! $res && file_exists("../main.inc.php")) $res=@include("../main.inc.php");
if (! $res && file_exists("../../main.inc.php")) $res=@include("../../main.inc.php");
if (! $res && file_exists("../../../main.inc.php")) $res=@include("../../../main.inc.php");
if (! $res && file_exists("../../../dolibarr/htdocs/main.inc.php")) $res=@include("../../../dolibarr/htdocs/main.inc.php");     // Used on dev env only
if (! $res && file_exists("../../../../dolibarr/htdocs/main.inc.php")) $res=@include("../../../../dolibarr/htdocs/main.inc.php");   // Used on dev env only
if (! $res && file_exists("../../../../../dolibarr/htdocs/main.inc.php")) $res=@include("../../../../../dolibarr/htdocs/main.inc.php");   // Used on dev env only
if (! $res) die("Include of main fails");
// Change this following line to use the correct relative path from htdocs (do not remove DOL_DOCUMENT_ROOT)
require_once(DOL_DOCUMENT_ROOT."/../htdocs/flightballoon/bbc_ballons.class.php");
require_once(DOL_DOCUMENT_ROOT."/../htdocs/flightballoon/bbc_baskets.class.php");
require_once(DOL_DOCUMENT_ROOT."/../htdocs/flightballoon/bbc_burners_sn.class.php");
require_once(DOL_DOCUMENT_ROOT."/../htdocs/flightballoon/bbc_burners.class.php");
require_once(DOL_DOCUMENT_ROOT."/../htdocs/flightballoon/bbc_compositions.class.php");
require_once(DOL_DOCUMENT_ROOT."/../htdocs/flightballoon/bbc_enveloppes.class.php");
require_once(DOL_DOCUMENT_ROOT."/../htdocs/flightballoon/bbc_fuels.class.php");
require_once(DOL_DOCUMENT_ROOT."/../htdocs/flightballoon/bbc_instruments.class.php");

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

// Get parameters
$myparam = isset($_GET["myparam"])?$_GET["myparam"]:'';

// Protection if external user
if ($user->societe_id  > 0)
{
	//accessforbidden();
}
if(!$user->rights->flightballoon->bal->add){
	accessforbidden();
}


/*******************************************************************
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 ********************************************************************/
$msg = ''; // init de la variable de messages
$error = 0;
/*
 * type du formulaire � afficher
 * ce type correspond aux types pr�sent dans la table "bbc_types_pieces"
 * 1 = Enveloppe
 * 2 = bruleurs
 * 3 = nacelle
 * 4 = bonbonnes
 * 5 = instruments
 */


if ($_GET["action"] == 'attribuer' || $_POST["action"] == 'attribuer')
{
	$error = 0;
	$select = 0; //savoir si une piece a �t� selectionn�e
	
	if (! $_POST["cancel"]){
		if($error == 0 && isset($_POST['enveloppe']) && $_POST['enveloppe'] != -1){
			$select++;
			$attribution = new Bbc_compositions($db);
			$attribution->fk_balloon = $_POST['ballon'];
			$attribution->piece_type = 1;
			$attribution->fk_piece = $_POST['enveloppe'];
			
			if($attribution->create($user)<0){
				$msg = '<div class="error">Error lors de l\'insert </div>';
				$error++;
			}
		}
		if($error == 0 && isset($_POST['burner']) && $_POST['burner'] != -1){
			$select++;
			$attribution = new Bbc_compositions($db);
			$attribution->fk_balloon = $_POST['ballon'];
			$attribution->piece_type = 2;
			$attribution->fk_piece = $_POST['burner'];
			
			if($attribution->create($user)<0){
				$msg = '<div class="error">Error lors de l\'insert </div>';
				$error++;
			}
		}
		if($error == 0 && isset($_POST['basket']) && $_POST['basket'] != -1){
			$select++;
			$attribution = new Bbc_compositions($db);
			$attribution->fk_balloon = $_POST['ballon'];
			$attribution->piece_type = 3;
			$attribution->fk_piece = $_POST['basket'];
			
			if($attribution->create($user)<0){
				$msg = '<div class="error">Error lors de l\'insert </div>';
				$error++;
			}
		}
		if($error == 0 && isset($_POST['instrument']) && $_POST['instrument'] != -1){
			$select++;
			$attribution = new Bbc_compositions($db);
			$attribution->fk_balloon = $_POST['ballon'];
			$attribution->piece_type = 4;
			$attribution->fk_piece = $_POST['instrument'];
				
			if($attribution->create($user)<0){
				$msg = '<div class="error">Error lors de l\'insert </div>';
				$error++;
			}
		}
		
		//aucune piece n'a ete selectionn�e => erreur
		if($select== 0){
			$msg = '<div class="error">Aucune piece n\'a ete selectionn�e</div>';
			$error++;
		}
	}
}


/***************************************************
 * PAGE
 *
 * Put here all code to build page
 ****************************************************/

llxHeader('','Ajout Piece � Ballon','');
if($msg){
	print $msg;
}
$form=new Form($db);

$html = new Form($db);
// Put here content of your page
print "<form name='add' action=\"attribuer.php\" method=\"post\">\n";
print '<input type="hidden" name="action" value="attribuer"/>';
print '<table class="border" width="100%">';
	//Balloon
	print "<tr>";
	print '<td class="fieldrequired"> Balloon </td><td>';
	$html->select_balloons();
	print '</td></tr>';
	
	//enveloppe
	print "<tr>";
	print '<td class="fieldrequired"> Enveloppe </td><td>';
	$html->select_bbc_enveloppe();
	print '</td></tr>';
	
	//bruleur
	print "<tr>";
	print '<td class="fieldrequired"> Burner </td><td>';
	$html->select_bbc_burner();
	print '</td></tr>';
	
	//nacelle
	print "<tr>";
	print '<td class="fieldrequired"> Basket </td><td>';
	$html->select_bbc_basket();
	print '</td></tr>';
	
	//instruments
	print "<tr>";
	print '<td class="fieldrequired"> Instrument </td><td>';
	$html->select_bbc_instruments();
	print '</td></tr>';
	
	//TODO ajouter les remorques
	//TODO ajouter les ventilateurs
	
print '</table>';

print '<br><center><input class="button" type="submit" value="'.$langs->trans("Save").'"> &nbsp; &nbsp; ';
print '<input class="button" type="submit" name="cancel" value="'.$langs->trans("Cancel").'"></center';

print '</form>';
/*
 * En fonction du type de la piece � ajouter, choix du formulaire
 * FINISH
 */
/***************************************************
 * LINKED OBJECT BLOCK
 *
 * Put here code to view linked object
 ****************************************************/
//$somethingshown=$myobject->showLinkedObjectBlock();

// End of page
$db->close();
llxFooter('$Date: 2011/07/31 22:21:57 $ - $Revision: 1.19 $');
?>
