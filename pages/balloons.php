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
//require_once(DOL_DOCUMENT_ROOT."/skeleton/skeleton_class.class.php");
require_once(DOL_DOCUMENT_ROOT."/../htdocs/user/class/user.class.php");
// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

// Get parameters
$myparam = isset($_GET["myparam"])?$_GET["myparam"]:'';

// Protection if external user
if ($user->societe_id > 0)
{
	//accessforbidden();
}



/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/

//if ($_GET["action"] == 'add' || $_POST["action"] == 'add')
//{
//	$myobject=new Skeleton_class($db);
//	$myobject->prop1=$_POST["field1"];
//	$myobject->prop2=$_POST["field2"];
//	$result=$myobject->create($user);
//	if ($result > 0)
//	{
//		// Creation OK
//	}
//	{
//		// Creation KO
//		$mesg=$myobject->error;
//	}
//}





/***************************************************
* PAGE
*
* Put here all code to build page
****************************************************/


llxHeader('','Carnet de vol - readFlight','');
// Put here content of your page
$data =array();// array(array('abs1',valA1,valB1), array('abs2',valA2,valB2), ...)
$tmp = array();
$legend = array();

$sql="SELECT *";
$sql .= " FROM llx_bbc_ballons AS BAL";
$resql = $db->query($sql);
if($resql && $user->rights->flightlog->vol->detail){
	print '<table class="border" width="100%">';

	$num = $db->num_rows($resql);
	$i = 0;
	if($num){
		print '<tr class="liste_titre">';

		print '<td class="liste_titre" > Ballon </td>';
		print '<td class="liste_titre"> marraine </td>';
		print '<td class="liste_titre"> responsable </td>';
		print'</tr>';
		while($i <$num){
			$obj = $db->fetch_object($resql); //vol
			print '<tr>';
			if($obj){
				//recup�ration du responsable
				$responsable = New User($db); //pilot
				$responsable->fetch($obj->fk_responsable);
//				print '<td><a href="ficheBalloon.php?bal='.$obj->idBal.'">'.strtoupper($obj->ballon).'</a></td>';
				print '<td><a href="fiche.php?bal='.$obj->rowid.'">'.strtoupper($obj->immat).'</a></td>';
				print '<td>'.$obj->marraine.'</td>';
				print '<td><a href="'.DOL_URL_ROOT.'/user/fiche.php?id='.$obj->fk_responsable.'">'.img_object($langs->trans("ShowUser"),"user").' '.$responsable->getFullName($langs).'</a></td>';
			}
			print'</tr>';
			$i++;
		}
	}
	print'</table><br/>';
}


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
