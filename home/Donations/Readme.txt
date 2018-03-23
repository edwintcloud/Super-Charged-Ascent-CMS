1.  Create a new database called donations and execute Donation Database.sql into it
2.  In the "realms" table, enter each realm that the system will serve. Entries should start at 1. The "sql" fields should point to the realm's character database.
3.  In the "rewards" table, enter the rewards you want to be delivered on each realm. Entries should start at 1. The "realm" field should be the entry in the "realms" table of the realm you want the reward to be on. "item" and "quantity" fields are the item id and quantity. Leave them blank for no item. "gold" is the amount of money in copper included in the reward. "price" is the donation amount to receive the reward. It is counted in the currency you define in the config file.
4.  Open your config.php file and edit the data between quotation marks. More information is inside. Keep this file secure.
5.  Once you are finished, go to the index.php file in your web browser to make sure everything works.
6.  It is recommended that you test the system before using it. You can do this with a real PayPal purchase, or you can use the PayPal sandbox.

    DONE!

    Operation
    Users donate from the index.php file.
    If the payment is successfully processed, the reward will be delivered to the players mailbox.
    To check the logs, go to /acp and log in with the information in your config file. There you can search through payment logs.
    If a donation fails, possibly due to a nonexistant character or tampering with the form information, you can check the "failed" payment logs.
    Invalid payments should be treated as suspicious and investigated.