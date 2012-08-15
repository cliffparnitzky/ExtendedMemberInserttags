<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Cliff Parnitzky 2012
 * @author     Cliff Parnitzky
 * @package    ExtendedMemberInserttags
 * @license    LGPL
 */

/**
 * Class ExtendedMemberInserttags
 *
 * InsertTag hook class.
 * @copyright  Cliff Parnitzky 2012
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class ExtendedMemberInserttags extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}
	
	/**
	 * Replaces the additional member inserttags 
	 */
	public function replaceExtendedMemberInserttags($strTag)
	{
		$strTag = explode('::', $strTag);
		switch ($strTag[0])
		{
			case 'member':
				$member = null;
				$attributeIndex = 0;
				if (is_numeric($strTag[1]) && intval($strTag[1]) > 0) {
					$objMember = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")
									   ->limit(1)
									   ->execute($strTag[1]);
					if ($objMember->numRows > 0 && $objMember->id == $strTag[1])
					{
						$member = $objMember;
					}
					$attributeIndex = 2;
				
				} elseif (FE_USER_LOGGED_IN) {
					$this->import('FrontendUser', 'User');
					$member = $this->User;
					$attributeIndex = 1;
				}
				
				if ($member != null) {
					$value = $member->$strTag[$attributeIndex];

					$this->loadDataContainer('tl_member');

					if ($GLOBALS['TL_DCA']['tl_member']['fields'][$strTag[$attributeIndex]]['inputType'] == 'password')
					{
						// do not allow extracting the password
						return "";
					}
					

					$value = deserialize($value);
					$rgxp = $GLOBALS['TL_DCA']['tl_member']['fields'][$strTag[$attributeIndex]]['eval']['rgxp'];
					$opts = $GLOBALS['TL_DCA']['tl_member']['fields'][$strTag[$attributeIndex]]['options'];
					$rfrc = $GLOBALS['TL_DCA']['tl_member']['fields'][$strTag[$attributeIndex]]['reference'];
					$fkey = $GLOBALS['TL_DCA']['tl_member']['fields'][$strTag[$attributeIndex]]['foreignKey'];

					$returnValue = '';
					if ($rgxp == 'date' || $rgxp == 'time' || $rgxp == 'datim')
					{
						$dateFormat = $GLOBALS['TL_CONFIG'][$rgxp . 'Format'];
						// check if custom format was set
						if (count($strTag) == $attributeIndex + 2 && strlen($strTag[$attributeIndex + 1]) > 0) {
							$dateFormat = $strTag[$attributeIndex + 1];
						}
						$returnValue = $this->parseDate($dateFormat, $value);
					}
					elseif (is_array($value))
					{
						$returnValue = implode(', ', $value);
						if (strlen($fkey) > 0)
						{
							$returnValue = $this->getArrayValueAsList($fkey, $returnValue);
						}
					}
					elseif (is_array($opts) && array_is_assoc($opts))
					{
						$returnValue = isset($opts[$value]) ? $opts[$value] : $value;
					}
					elseif (is_array($rfrc))
					{
						$returnValue = isset($rfrc[$value]) ? ((is_array($rfrc[$value])) ? $rfrc[$value][0] : $rfrc[$value]) : $value;
					}
					elseif ($strTag[$attributeIndex] == 'age')
					{
						$returnValue = date("Y") - date("Y", $member->dateOfBirth);
					}
					elseif ($strTag[$attributeIndex] == 'name')
					{
						$returnValue = $member->firstname . " " . $member->lastname;
					}
					elseif ($strTag[$attributeIndex] == 'salutation')
					{
						$salutation = $GLOBALS['TL_LANG']['MSC']['salutation_' . $member->gender];
						if (strlen($salutation) == 0)
						{
							$salutation = $GLOBALS['TL_LANG']['MSC']['salutation'];
						}
						$returnValue = $salutation;
					}
					elseif ($strTag[$attributeIndex] == 'welcoming')
					{
						$returnValue = $GLOBALS['TL_LANG']['MSC']['welcoming_' . $member->gender];
					}
					else
					{
						$returnValue = $value;
					}

					// Convert special characters (see #1890)
					return specialchars($returnValue);
				}
		}
		return false;
	}
	
	/**
	 * get all values of the given array
	 */
	private function getArrayValueAsList($foreignKey, $valueIds)
	{
		$foreignKey = explode('.', $foreignKey);
		$table = $foreignKey[0];
		$fieldname = $foreignKey[1];
		if (strlen($table) > 0 && strlen($valueIds) > 0)
		{
			$values = $this->Database->prepare("SELECT " . $fieldname . " FROM " . $table . " WHERE id IN (" . $valueIds . ") ORDER BY name ASC")
								->execute();
			$list = array();
			while ($values->next())
			{
				$list[] = $values->$fieldname;
			}
			return implode(", ", $list);
		}
		return "";
	}
}
?>