--->README for Red's Website---

The config is located in: 

/home/mainconfig.php  *[used to configure everything but the realm status panel(s)]*
/home/realmconfig.php *[used to configure up to six different realm status panels which can be enabled via the admin panel on the site]*



Make sure the settings inside match your server's settings.


How to install:
1. Setup configs as mentioned above.
2. Create the following databases in your sql editor: portal, armory, avs, donations
3. Navigate to the /SQL directory in this site package and Right-click then edit the dbinstall.bat
4. Where it says set user= and set pass= type your coresponding settings and save the file
5. Run the batch file and when prompted press i
6. if everything works fine it should says [Importing Finished] and some db names otherwise your settings were not correct; however if it says something about a column ignore it

How to update:
1. Right click your folder you checked the svn out to and select SVN Update
2. Navigate to the sql/Updates folder and right-click then select edit on the db-update.bat
3. Where it says set user= and set pass= type your coresponding settings and save the file
4. Run the db-updater.bat and press i when prompted

**REV 12 UPDATE WIL DROP THE PANELS TABLE, SO BACK IT UP BEFORE INSTALLING THE UPDATE, FUTURE UPDATES WILL USE THE UPDATE FUNCTION**