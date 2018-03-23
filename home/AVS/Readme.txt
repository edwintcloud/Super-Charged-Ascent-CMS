1.  Create a new DB Called Vote and extract Vote.sql into it
2.  Extract Logon.sql into your account database
3.  If you also have AAPDRDS installed, it uses the same "realms" table.
4.  In the realms table, enter the name and MySQL information for each realm of your server.
5.  In the votemodules table, enter name, vote, and image urls for each topsite your server is on.
6.  In the voterewards table, enter the information of each reward you want to offer. The realm is the id of the realm in the realms table. The itemid is the id of the item to be sent via mail. The points are the amount of points required to purchase the item.
7.  Leave the votes table as-is.
8.  Proceed to edit the configuration information located at the top of the index.php file.