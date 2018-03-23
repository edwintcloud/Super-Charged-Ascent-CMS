@ECHO off
COLOR 1B
REM #####################################
REM # WDB = World database.		#
REM #####################################
:TOP
cls
echo. 
echo          ษอออออออออออออออออออออออออออออออออออป
echo          บ                                   บ
echo          บ Welcome to Red's Website Database บ
echo          บ       Installation Program        บ
echo          บ                                   บ
echo          ศอออออออออออออออออออออออออออออออออออผ
echo.
ECHO         Please, insert your settings below...
echo.
set /p server=                     Host : 
set /p user=             Database login : 
set /p pass=                   Password :  
set /p ldb= Character/Accounts Database : 
Set port=3306
set ddb=donations
set avsdb=avs
set pdb=portal

Rem #####################################
Rem # Don't Touch Past this point!	#
Rem #####################################
if %user% == CHANGEME GOTO error
if %pass% == CHANGEME GOTO error
:menu
cls
echo.
echo          ษอออออออออออออออออออออออออออออออออออป
echo          บ                                   บ
echo          บ Welcome to Red's Website Database บ
echo          บ       Installation Program        บ
echo          บ                                   บ
echo          ศอออออออออออออออออออออออออออออออออออผ
ECHO.
ECHO	        Thank you. Now select an option :
ECHO.
ECHO            A = Install Website Databases
ECHO            B = Install Rev 13 Update
ECHO.
echo.         
echo           Individual Database Installation 
echo           ออออออออออออออออออออออออออออออออ
echo.
ECHO            C = Install AVS Database
ECHO            D = Install Donations Database
ECHO            E = Install Portal Database 
ECHO            F = Install Logon Database Updates* 
Echo.
ECHO	           G = Change/modify your settings
ECHO	           X = Exit Installer
ECHO.
set /p l=		Enter Option : 
if %l%==* goto error
if %l%==A goto wdb
if %l%==a goto wdb
if %l%==B goto dui
if %l%==b goto dui
if %l%==C goto avs
if %l%==c goto avs
if %l%==D goto don
if %l%==d goto don
if %l%==E goto por
if %l%==e goto por
if %l%==F goto log
if %l%==f goto log
if %l%==G goto TOP
if %l%==g goto TOP
if %l%==X goto quit
if %l%==x goto quit

GOTO error

:wdb
CLS
ECHO.
ECHO [Installing] Started...
ECHO [Installing] Reds Website database...
mysql -h %server% --user=%user% --password=%pass% --port=%port% < donations.sql
ECHO [Installing] Finished Installing Donations...
mysql -h %server% --user=%user% --password=%pass% --port=%port% < avs.sql
ECHO [Installing] Finished Installing AVS...
mysql -h %server% --user=%user% --password=%pass% --port=%port% < portal.sql
ECHO [Installing] Finished Installing Portal...
mysql -h %server% --user=%user% --password=%pass% --port=%port% %ldb% < logon.sql
mysql -h %server% --user=%user% --password=%pass% --port=%port% %ldb% < motd.sql
ECHO [Installing] Finished Installing Logon Updates...
ECHO [Installing] Finished
ECHO.
PAUSE
GOTO MENU

:dui
CLS
ECHO.
ECHO [Updating] Started...
ECHO [Updating] Reds Website database...
mysql -h %server% --user=%user% --password=%pass% --port=%port% < Rev13-Update.sql
ECHO [Updating] Database now at Rev 13 *Latest*...
ECHO [Updating] Finished
ECHO.
PAUSE
GOTO MENU

:avs
CLS
ECHO.
ECHO [Installing] Started...
ECHO [Installing] AVS Database...
mysql -h %server% --user=%user% --password=%pass% --port=%port% < avs.sql
ECHO [Installing] Finished Installing AVS...
ECHO [Installing] Finished
ECHO.
PAUSE
GOTO MENU

:don
CLS
ECHO.
ECHO [Installing] Started...
ECHO [Installing] Donations Database...
mysql -h %server% --user=%user% --password=%pass% --port=%port% < donations.sql
ECHO [Installing] Finished Installing Donations...
ECHO [Installing] Finished
ECHO.
PAUSE
GOTO MENU

:por
CLS
ECHO.
ECHO [Installing] Started...
ECHO [Installing] Portal Database...
mysql -h %server% --user=%user% --password=%pass% --port=%port% < portal.sql
ECHO [Installing] Finished Installing Portal...
ECHO [Installing] Finished
ECHO.
PAUSE
GOTO MENU

:log
CLS
ECHO.
ECHO [Installing] Started...
ECHO [Installing] Logon Updates...
mysql -h %server% --user=%user% --password=%pass% --port=%port% %ldb% < logon.sql
mysql -h %server% --user=%user% --password=%pass% --port=%port% %ldb% < motd.sql
ECHO [Installing] Finished Installing Logon Updates...
ECHO [Installing] Finished
ECHO.
PAUSE
GOTO MENU

:quit