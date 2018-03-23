@ECHO off
REM ############################################################################
REM #
REM #      B A S I C   U S E R   C O N F I G U R A T I O N   A R E A
REM #
REM ############################################################################
REM #########################################
REM # server - Base Table host
REM # user - Base Table login
REM # pass - Base Table login password
REM # wdb  -  Base Table name
REM #########################################
set user=root
set pass=ascent
REM ############################################################################
REM #
REM #    A D V A N C E D   U S E R   C O N F I G U R A T I O N   A R E A
REM #
REM ############################################################################
set server=localhost
set port=3306
REM ############################################################################
REM #
REM #     D O   N O T   M O D I F Y   B E Y O N D   T H I S   P O I N T
REM #
REM ############################################################################
if %user% == CHANGEME GOTO error2
if %pass% == CHANGEME GOTO error2
:menu
cls
ECHO.
ECHO.
ECHO		####################################
ECHO		#######     Reds Website     #######
ECHO		######        Database        ######
ECHO		######      Updater Tool      ######
ECHO		####################################
ECHO.
ECHO.
ECHO         Please note that this tool will lock your tables   
ECHO.
ECHO.
ECHO		Please type the letter for the option:
ECHO.
ECHO		 i = Update to Rev 13 Database
ECHO.
ECHO.
ECHO		 x - Exit
ECHO.
set /p l=            Enter Letter:
if %l%==* goto error
if %l%==i goto dbimp
if %l%==I goto dbimp
if %l%==x goto quit
if %l%==X goto quit
goto error

:dbimp
CLS
ECHO.
ECHO.
ECHO [Updating] Started...
ECHO [Updating] Reds Website database...
mysql -h %server% --user=%user% --password=%pass% --port=%port% < Rev13-Update.sql
ECHO [Updating] Finished
ECHO.
PAUSE    
GOTO menu

:error
CLS
ECHO.
ECHO.
ECHO [ERROR] An error has occured, you will be directed back to the
ECHO [ERROR] main menu.
PAUSE    
GOTO menu

:error2
CLS
ECHO.
ECHO.
ECHO [FAILURE] You did not change the proper directives in this file.
ECHO [FAILURE] Please edit this script and fill in the proper MYSQL Information.
ECHO [FAILURE] When the information is correct: Please Try Again.
PAUSE    
GOTO quit  
:quit