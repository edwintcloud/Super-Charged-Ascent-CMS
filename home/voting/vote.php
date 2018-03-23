<?php
include "../home/mainconfig.php";
?>

<?php echo $vote_popup_style; ?>

        <div id='header' style='font-family: Arial; margin-bottom: 0.5em; border-bottom: 1px solid red; text-align: center; background-color:gray; color:ffffff'>

                Please vote for our server!

                <span align='right' style='position:absolute; left: 305px; text-align: right;'>

                        <a href='javascript:void();' style='font-size:10px; color:white; text-decoration:none;' onclick='javascript:document.getElementById("container").style.display="none";'>

                                [X]

                        </a>

                </span>

        </div>

        <div id='text' style='padding: 5px; padding-left: 7px; font-size: 12px; border-bottom: 1px solid red;'>

                Voting for <?php echo $site_name; ?> helps us grow and evolve,

                and that means more people to play with and help out.

                You can vote once every 12 hours, and you'll get a reward for doing so!

        </div>

        <form style='text-align: center; margin-bottom: 2px; margin-top: 5px;' method='post' action='./AVS'>


                        <?php echo $vote_popup_btn_style; ?>

                </div>

                <div style='font-size: 12px; valign:center; margin-top: 5px; padding-top: 3px;'>

                </div>


        </form>

</div>