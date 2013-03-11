#Installation
***
1.	Clone GitHub Repo: `git clone git://github.com/opengovfoundation/the-madison-project.git`
2.	Create MySQL database and user for Madison
3.  Update Madison configuration file 'inc/config.php' with database credentials
4.  Create facebook app and add credentials to 'inc/facebook.php' for facebook logins
5.  Import `madison.sql` into Madison database
6.  Create initial Admin user
	* Generate Admin user password
		* The password encryption scheme uses the salt value found in 'inc/user.class.php'
			* The password should be generated using md5((salt)(password)(salt))
	* Insert email, password, fname, lname into 'users' table to create initial Admin user
7.  Log in as Admin user and create first document by going to 'Admin->View/Edit Docs' and creating the first document.
8.  Refresh the page, go to created document page, and add content
9.  Add first document to the navigation bar using the nav bar admin page