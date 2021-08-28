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
$formType = 1; // 1 par d�faut
if ($_GET["mode"] == 'SELECT')
{
	$formType = $_GET['typePiece'];
}

/***************************************************
 * PAGE
 *
 * Put here all code to build page
 ****************************************************/

llxHeader('','Ajout Ballon','');
if($msg){
	print $msg;
}
$form=new Form($db);
//onglet du dessus pour choisir le type de pi�ce � ajouter
print '<!-- debut cartouche rapport -->
<div class="tabs">
<a id="active" class="tab">Carnet de vol</a>
</div>';
print '<div class="tabBar">';
print "<form name='addPiece' action=\"pieces.php\" method=\"get\">\n";
print '<input type="hidden" name="mode" value="SELECT">';
print '<table width="100%" class="border">';
print '<tr><td>Type de pi&egrave;ces</td><td>';
print $form->select_type_piece($formType);
print'</td></tr>';
print '<tr><td colspan="2" align="center"><input type="submit" class="button" name="submit" value="Rafraichir"></td></tr></table>';
print '</form></div>';
/*
 * En fonction du type de la piece � ajouter, choix du formulaire
 * START
 */
$sql="SELECT *";
//enveloppes
if($formType == 1){
	$sql .= " FROM llx_bbc_enveloppes";
	$resql = $db->query($sql);
	
	if($resql){
		print '<table class="border" width="100%">';
	
		$num = $db->num_rows($resql);
		$i = 0;
		if($num){
			print '<tr class="liste_titre">';
			print '<td class="liste_titre"> manufacturer </td>';
			print '<td class="liste_titre"> model </td>';
			print '<td class="liste_titre"> serial number </td>';
			print '<td class="liste_titre"> Registration </td>';
			print'</tr>';
			while($i <$num){
				$obj = $db->fetch_object($resql); //vol
				print '<tr>';
				if($obj){
					print '<td>'.$obj->manufacturer.'</td>';
					print '<td>'.$obj->model.'</td>';
					print '<td>'.$obj->serialnumber.'</td>';
					print '<td>'.$obj->registration.'</td>';
				}
				print'</tr>';
				$i++;
			}
		}
		print'</table>';
	}
}
//bruleur
if($formType == 2){
	$sql .= " FROM llx_bbc_enveloppes";
	$resql = $db->query($sql);
	
	if($resql){
		print '<table class="border" width="100%">';
	
		$num = $db->num_rows($resql);
		$i = 0;
		if($num){
			print '<tr class="liste_titre">';
			print '<td class="liste_titre"> manufacturer </td>';
			print '<td class="liste_titre"> model </td>';
			print '<td class="liste_titre"> serial number </td>';
			print '<td class="liste_titre"> Registration </td>';
			print'</tr>';
			while($i <$num){
				$obj = $db->fetch_object($resql); //vol
				print '<tr>';
				if($obj){
					print '<td>'.$obj->manufacturer.'</td>';
					print '<td>'.$obj->model.'</td>';
					print '<td>'.$obj->serialnumber.'</td>';
					print '<td>'.$obj->registration.'</td>';
				}
				print'</tr>';
				$i++;
			}
		}
		print'</table>';
	}
}
//nacelle
if($formType == 3){
	$sql .= " FROM llx_bbc_baskets";
	$resql = $db->query($sql);
	
	if($resql){
		print '<table class="border" width="100%">';
	
		$num = $db->num_rows($resql);
		$i = 0;
		if($num){
			print '<tr class="liste_titre">';
			print '<td class="liste_titre"> manufacturer </td>';
			print '<td class="liste_titre"> model </td>';
			print '<td class="liste_titre"> serial number </td>';
			print'</tr>';
			while($i <$num){
				$obj = $db->fetch_object($resql); //vol
				print '<tr>';
				if($obj){
					print '<td>'.$obj->manufacturer.'</td>';
					print '<td>'.$obj->model.'</td>';
					print '<td>'.$obj->serialnumber.'</td>';
				}
				print'</tr>';
				$i++;
			}
		}
		print'</table>';
	}
}
//bonbonnes
if($formType == 4){
	$sql .= " FROM llx_bbc_fuels";
	$resql = $db->query($sql);
	
	if($resql){
		print '<table class="border" width="100%">';
	
		$num = $db->num_rows($resql);
		$i = 0;
		if($num){
			print '<tr class="liste_titre">';
			print '<td class="liste_titre"> manufacturer </td>';
			print '<td class="liste_titre"> model </td>';
			print '<td class="liste_titre"> serial number </td>';
			print '<td class="liste_titre"> construction date </td>';
			print'</tr>';
			while($i <$num){
				$obj = $db->fetch_object($resql); //vol
				print '<tr>';
				if($obj){
					print '<td>'.$obj->manufacturer.'</td>';
					print '<td>'.$obj->model.'</td>';
					print '<td>'.$obj->serialnumber.'</td>';
					print '<td>'.dol_print_date($obj->constructiondate).'</td>';
				}
				print'</tr>';
				$i++;
			}
		}
		print'</table>';
	}
}
//instruments
if($formType == 5){
	$sql .= " FROM llx_bbc_instruments";
	$resql = $db->query($sql);
	
	if($resql){
		print '<table class="border" width="100%">';
	
		$num = $db->num_rows($resql);
		$i = 0;
		if($num){
			print '<tr class="liste_titre">';
			print '<td class="liste_titre"> manufacturer </td>';
			print '<td class="liste_titre"> model </td>';
			print '<td class="liste_titre"> serial number </td>';
			print'</tr>';
			while($i <$num){
				$obj = $db->fetch_object($resql); //vol
				print '<tr>';
				if($obj){
					print '<td>'.$obj->manufacturer.'</td>';
					print '<td>'.$obj->model.'</td>';
					print '<td>'.$obj->serialnumber.'</td>';
				}
				print'</tr>';
				$i++;
			}
		}
		print'</table>';
	}
}
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
