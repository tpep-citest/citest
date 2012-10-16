<?php
/**
* ownCloud
*
* @author Robin Appelman
* @copyright 2012 Robin Appelman icewind@owncloud.com
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either
* version 3 of the License, or any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*
* You should have received a copy of the GNU Affero General Public
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
*
*/

abstract class Test_Group_Backend extends UnitTestCase {
	/**
	 * @var OC_Group_Backend $backend
	 */
	protected $backend;

	/**
	 * get a new unique group name
	 * test cases can override this in order to clean up created groups
	 * @return array
	 */
	public function getGroupName() {
		return uniqid('test_');
	}

	/**
	 * get a new unique user name
	 * test cases can override this in order to clean up created user
	 * @return array
	 */
	public function getUserName() {
		return uniqid('test_');
	}

	public function testAddRemove() {
		//get the number of groups we start with, in case there are exising groups
		$startCount=count($this->backend->getGroups());

		$name1=$this->getGroupName();
		$name2=$this->getGroupName();
		$this->backend->createGroup($name1);
		$count=count($this->backend->getGroups())-$startCount;
		$this->assertEqual(1,$count);
		$this->assertTrue((array_search($name1,$this->backend->getGroups())!==false));
		$this->assertFalse((array_search($name2,$this->backend->getGroups())!==false));
		$this->backend->createGroup($name2);
		$count=count($this->backend->getGroups())-$startCount;
		$this->assertEqual(2,$count);
		$this->assertTrue((array_search($name1,$this->backend->getGroups())!==false));
		$this->assertTrue((array_search($name2,$this->backend->getGroups())!==false));

		$this->backend->deleteGroup($name2);
		$count=count($this->backend->getGroups())-$startCount;
		$this->assertEqual(1,$count);
		$this->assertTrue((array_search($name1,$this->backend->getGroups())!==false));
		$this->assertFalse((array_search($name2,$this->backend->getGroups())!==false));
	}

	public function testUser() {
		$group1=$this->getGroupName();
		$group2=$this->getGroupName();
		$this->backend->createGroup($group1);
		$this->backend->createGroup($group2);

		$user1=$this->getUserName();
		$user2=$this->getUserName();

		$this->assertFalse($this->backend->inGroup($user1,$group1));
		$this->assertFalse($this->backend->inGroup($user2,$group1));
		$this->assertFalse($this->backend->inGroup($user1,$group2));
		$this->assertFalse($this->backend->inGroup($user2,$group2));

		$this->assertTrue($this->backend->addToGroup($user1,$group1));

		$this->assertTrue($this->backend->inGroup($user1,$group1));
		$this->assertFalse($this->backend->inGroup($user2,$group1));
		$this->assertFalse($this->backend->inGroup($user1,$group2));
		$this->assertFalse($this->backend->inGroup($user2,$group2));
		
		$this->assertFalse($this->backend->addToGroup($user1,$group1));

		$this->assertEqual(array($user1),$this->backend->usersInGroup($group1));
		$this->assertEqual(array(),$this->backend->usersInGroup($group2));

		$this->assertEqual(array($group1),$this->backend->getUserGroups($user1));
		$this->assertEqual(array(),$this->backend->getUserGroups($user2));

		$this->backend->deleteGroup($group1);
		$this->assertEqual(array(),$this->backend->getUserGroups($user1));
		$this->assertEqual(array(),$this->backend->usersInGroup($group1));
		$this->assertFalse($this->backend->inGroup($user1,$group1));
	}
}
