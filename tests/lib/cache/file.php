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

class Test_Cache_File extends Test_Cache {
	private $user;
	
	function skip() {
		//$this->skipUnless(OC_User::isLoggedIn());
	}
	
	public function setUp() {
		//clear all proxies and hooks so we can do clean testing
		OC_FileProxy::clearProxies();
		OC_Hook::clear('OC_Filesystem');
		
		//enable only the encryption hook if needed
		if(OC_App::isEnabled('files_encryption')) {
			OC_FileProxy::register(new OC_FileProxy_Encryption());
		}
		
		//set up temporary storage
		OC_Filesystem::clearMounts();
		OC_Filesystem::mount('OC_Filestorage_Temporary',array(),'/');

		OC_User::clearBackends();
		OC_User::useBackend(new OC_User_Dummy());
		
		//login
		OC_User::createUser('test', 'test');
		
		$this->user=OC_User::getUser();
		OC_User::setUserId('test');

		//set up the users dir
		$rootView=new OC_FilesystemView('');
		$rootView->mkdir('/test');
		
		$this->instance=new OC_Cache_File();
	}

	public function tearDown() {
		OC_User::setUserId($this->user);
	}
}
