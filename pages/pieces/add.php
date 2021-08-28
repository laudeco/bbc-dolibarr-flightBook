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


if ($_GET["action"] == 'add' || $_POST["action"] == 'add')
{
	$formType = $_POST['typePiece'];
	if (! $_POST["cancel"]){
		/*
		 * Commun � tous
		 */
		$manufacturer = trim($_POST['manufacturer']); //manufacturer
		$model = trim($_POST['model']);//model
		
		//enveloppes
		if($formType == 1){
			$enveloppe = new Bbc_enveloppes($db);
			
			$enveloppe->manufacturer = $manufacturer;
			$enveloppe->model = $model;
			$enveloppe->serialnumber = strtoupper(trim($_POST['sn']));
			$enveloppe->registration = strtoupper(trim($_POST['registration']));
			
			//sn
			if($enveloppe->serialnumber == ""){
				$msg = '<div class="error">Le numero de serie n\'est pas entre </div>';
				$error ++;
			}
			// verification de l'immat
			$patern = '#[A-Z]{2}-[A-Z]{3}#';
			$error = 0;
			if(preg_match($patern,$enveloppe->registration) == 0){
				$msg = '<div class="error">L\'immatriculation ne respecte pas le format XX-XXX </div>';
				$error ++;
			}
			if($error == 0 && $enveloppe->create($user)<0){
				$msg = '<div class="error">Erreur lors de l\'ajout de l\'enveloppe </div>';
				$error ++;
			}
		}
		//bruleur
		if($formType == 2){
			$bruler = new Bbc_burners($db);
			$bruler->framemodel = trim($_POST['frameModel']);
			$bruler->framenumber = trim($_POST['frameNumber']);
			$bruler->manufacturer = $manufacturer;
			$bruler->model = $model;
			
			$sn1 = $_POST['sn1'];
			$sn2 = $_POST['sn2'];
			
			if($sn1 == "" && $sn2 == ""){
				$msg = '<div class="error">Aucunes immatriculation n\'est entree </div>';
				$error ++;
			}
			if($error == 0){
				$idBruleur = $bruler->create($user);
				if($idbruleur < 0){
					$msg = '<div class="error">Erreur lors de l\'ajout du bruleur</div>';
					$error++;
				}
			}
			if($sn1 != "" && $error == 0){
				$bsn1 = new Bbc_burners_sn($db);
				$bsn1->burners_rowid = $idBruleur;
				$bsn1->serialnumber = $sn1;
				
				if($bsn1->create($user)<0){
					$msg = '<div class="error">Erreur lors de l\'ajout du bruleur (sn1) </div>';
					$bruler->delete($user);
					$error++;
				}
			}
			if($sn2 != "" && $error == 0){
				$bsn2 = new Bbc_burners_sn($db);
				$bsn2->burners_rowid = $idBruleur;
				$bsn2->serialnumber = $sn2;
				
				if($bsn2->create($user)<0){
					$msg = '<div class="error">Erreur lors de l\'ajout du bruleur (sn2) </div>';
					$bsn1->delete($user);
					$bruler->delete($user);
					$error++;
				}
			}
		}
		//nacelle
		if($formType == 3){
			$nacelle = new Bbc_baskets($db);
			
			$nacelle->manufacturer = $manufacturer;
			$nacelle->model = $model;
			$nacelle->serialnumber = $_POST['sn'];
			
			if($nacelle->create($user)<0){
				$msg = '<div class="error">Erreur lors de l\'ajout la nacelle</div>';
				$error++;
			}
		}
		//bonbonnes
		if($formType == 4){
			$bonbonne = new Bbc_fuels($db);

			$bonbonne->manufacturer = $manufacturer;
			$bonbonne->model = $model;
			$bonbonne->serialnumber = $_POST['sn'];

			$dated=dol_mktime(12, 0, 0,
			$_POST["remonth"],
			$_POST["reday"],
			$_POST["reyear"]);
			$bonbonne->constructiondate =$dated;
			
			if($bonbonne->create($user)<0){
				$msg = '<div class="error">Erreur lors de l\'ajout la bonbonne</div>';
				$error++;
			}
		}
		//instruments
		if($formType == 5){
			$instrument = new Bbc_instruments($db);
			
			$instrument->manufacturer = $manufacturer;
			$instrument->model = $model;
			$instrument->serialnumber = $_POST['sn'];
			
			if($instrument->create($user)<0){
				$msg = '<div class="error">Erreur lors de l\'ajout de l\'instrument</div>';
				$error++;
			}
		}
		if($error == 0){
			$msg = '<div class="info">L\'ajout de la piece est correcte</div>';
		}
	}
}

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
print "<form name='addPiece' action=\"add.php\" method=\"get\">\n";
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
$html = new Form($db);
// Put here content of your page
print "<form name='add' action=\"add.php\" method=\"post\">\n";
print '<input type="hidden" name="action" value="add"/>';
print '<input type="hidden" name="typePiece" value="'.$formType.'"/>';
print '<table class="border" width="100%">';
	//manufacturer
	print "<tr>";
	print '<td class="fieldrequired"> Manufacturer </td><td>';
	print '<input type="text" name="manufacturer" calss="flat"/>';
	print '</td></tr>';
	//model
	print "<tr>";
	print '<td class="fieldrequired"> Model </td><td>';
	print '<input type="text" name="model" calss="flat"/>';
	print '</td></tr>';
//enveloppes
if($formType == 1){
	//serial number
	print "<tr>";
	print '<td class="fieldrequired"> Serial Number </td><td>';
	print '<input type="text" name="sn" calss="flat"/>';
	print '</td></tr>';
	//registration
	print "<tr>";
	print '<td class="fieldrequired"> Registration </td><td>';
	print '<input type="text" name="registration" calss="flat"/>';
	print '</td></tr>';
}
//bruleur
if($formType == 2){
	//serial number 1
	print "<tr>";
	print '<td class="fieldrequired"> Serial Number 1</td><td>';
	print '<input type="text" name="sn1" calss="flat"/>';
	print '</td>';
	//serial number 2
	print '<td class="fieldrequired"> Serial Number 2</td><td>';
	print '<input type="text" name="sn2" calss="flat"/>';
	print '</td></tr>';
	//frame model
	print "<tr>";
	print '<td class="field"> Frame model </td><td>';
	print '<input type="text" name="frameModel" calss="flat"/>';
	print '</td></tr>';
	//frame number
	print "<tr>";
	print '<td class="field"> Frame number </td><td>';
	print '<input type="text" name="frameNumber" calss="flat"/>';
	print '</td></tr>';
}
//nacelle
if($formType == 3){
	//serial number
	print "<tr>";
	print '<td class="fieldrequired"> Serial Number </td><td>';
	print '<input type="text" name="sn" calss="flat"/>';
	print '</td></tr>';
}
//bonbonnes
if($formType == 4){
	//serial number
	print "<tr>";
	print '<td class="fieldrequired"> Serial Number </td><td>';
	print '<input type="text" name="sn" calss="flat"/>';
	print '</td></tr>';
	//Date de construction
	print "<tr>";
	print '<td class="fieldrequired"> Date de construction </td><td>';
	print $html->select_date($datec?$datec:-1,'','','','','add',1,1);
	print '</td></tr>';
	
}
//instruments
if($formType == 5){
	//serial number
	print "<tr>";
	print '<td class="fieldrequired"> Serial Number </td><td>';
	print '<input type="text" name="sn" calss="flat"/>';
	print '</td></tr>';
}
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
