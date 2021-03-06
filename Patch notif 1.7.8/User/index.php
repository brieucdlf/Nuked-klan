<?php 
// -------------------------------------------------------------------------//
// Nuked-KlaN - PHP Portal                                                  //
// http://www.nuked-klan.org                                                //
// -------------------------------------------------------------------------//
// This program is free software. you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License.           //
// -------------------------------------------------------------------------//
if (!defined("INDEX_CHECK"))
{
    die ("<div style=\"text-align: center;\">You cannot open this page directly</div>");
} 

global $language, $user, $cookie_captcha;
translate("modules/User/lang/" . $language . ".lang.php");
translate("modules/Members/lang/" . $language . ".lang.php");

// Inclusion syst�me Captcha
include_once("Includes/nkCaptcha.php");

// On determine si le captcha est actif ou non
if (_NKCAPTCHA == "off") $captcha = 0;
else if (_NKCAPTCHA == "auto" && $user[1] > 0)  $captcha = 0;
else if (_NKCAPTCHA == "auto" && isset($_COOKIE[$cookie_captcha])) $captcha = 0;
else $captcha = 1;

function index()
{
    global $user, $nuked, $bgcolor1, $bgcolor2, $bgcolor3;

    if ($user)
    {
        opentable();

        echo "<div style=\"text-align: center;\"><br /><big><b>" . _YOURACCOUNT . "</b></big><br /><br />\n"
	. _INFO . "<b> | "
	. "<a href=\"index.php?file=User&amp;op=edit_account\">" . _PROFIL . "</a> | "
	. "<a href=\"index.php?file=User&amp;op=edit_pref\">" . _PREF . "</a> | "
	. "<a href=\"index.php?file=User&amp;op=change_theme\">" . _THEMESELECT . "</a> | "
	. "<a href=\"index.php?file=User&amp;nuked_nude=index&amp;op=logout\">" . _USERLOGOUT . "</a></b></div><br />\n";

        $sql3 = mysql_query("SELECT U.pseudo, U.url, U.mail, U.date, U.avatar, U.count, S.last_used FROM " . USER_TABLE . " AS U LEFT OUTER JOIN " . SESSIONS_TABLE . " AS S ON U.id = S.user_id WHERE U.id = '" . $user[0] . "'"); 
		list($pseudo, $url, $mail, $date, $avatar, $nb_post, $last_used) = mysql_fetch_array($sql3);
        $pseudo = stripslashes($pseudo);
        $date = strftime("%x", $date);
		$last_used > 0 ? $last_used=strftime("%x %X", $last_used) : $last_used='';

        if ($url != "")
        {
            $website = $url;
        } 
        else
        {
            $website = "N/A";
        } 

        echo "<table style=\"margin-left:auto; margin-right:auto; text-align:left; background:" . $bgcolor2 . "; border:1px solid " . $bgcolor3 . "; width:75%;\" cellpadding=\"0\" cellspacing=\"1\">\n"
	. "<tr style=\"background: ". $bgcolor3 . "\"><td colspan=\"2\" align=\"center\" style=\"padding:2px;\"><b>" . _ACCOUNT . "</b></td></tr>\n"
	. "<tr style=\"background: ". $bgcolor1 . "\"><td align=\"left\" valign=\"top\" style=\"width:100%;\">\n"
	. "<table style=\"width:100%;\" cellpadding=\"2\" cellspacing=\"0\">\n"
	. "<tr style=\"background: ". $bgcolor2 . "\"><td>&nbsp;<b>" . _NICK . " :</b> " . $pseudo . "</td></tr>\n"
	. "<tr style=\"background: ". $bgcolor1 . "\"><td>&nbsp;<b>" . _WEBSITE . " :</b> " . $website . "</td></tr>\n"
	. "<tr style=\"background: ". $bgcolor2 . "\"><td>&nbsp;<b>" . _MAIL . " :</b> " . $mail . "</td></tr>\n"
	. "<tr style=\"background: ". $bgcolor1 . "\"><td>&nbsp;<b>" . _DATEUSER . " : </b> " . $date . "</td></tr>\n"
	. "<tr style=\"background: ". $bgcolor2 . "\"><td>&nbsp;<b>" . _LASTVISIT . " : </b> " . $last_used . "</td></tr>\n"
	. "</table>\n"
	. "</td>\n"
	. "<td align=\"right\" valign=\"middle\" style=\"padding:5px;\">\n";

        if ($avatar != "")
        {
            echo "<img style=\"border: 0; overflow: auto; max-width: 100px;  width: expression(this.scrollWidth >= 100? '100px' : 'auto');\" src=\"" . checkimg($avatar) . "\" alt=\"\" />";
        } 
        else
        {
            echo "<img src=\"modules/User/images/noavatar.gif\" alt=\"\" />";
        } 

        echo "</td></tr></table><br />\n"
		. "<table style=\"margin-left: auto;margin-right: auto;text-align: left;background: " . $bgcolor2 . ";border: 1px solid " . $bgcolor3 . ";\" width=\"75%\" cellpadding=\"2\" cellspacing=\"1\">\n"
		. "<tr style=\"background: ". $bgcolor3 . "\"><td align=\"center\"><b>" . _MESSPV . "</b></td></tr>\n";


        $sql2 = mysql_query("SELECT mid FROM " . USERBOX_TABLE . " WHERE user_for = '" . $user[0] . "' AND status = 1");
        $nb_mess_lu = mysql_num_rows($sql2);

        if ($user[5] > 0)
        {
            echo "<tr style=\"background: ". $bgcolor2 . "\"><td>" . _NOTREAD . " : <a href=\"index.php?file=Userbox\"><b>" . $user[5] . "</b></a></td></tr>\n";
        } 
        else
        {
            echo "<tr style=\"background: ". $bgcolor2 . "\"><td>" . _NOTREAD . " : <b>" . $user[5] . "</b></td></tr>\n";
        } 

        if ($nb_mess_lu > 0)
        {
            echo "<tr style=\"background: ". $bgcolor1 . "\"><td>" . _READ . " : <a href=\"index.php?file=Userbox\"><b>" . $nb_mess_lu . "</b></a></td></tr>\n";
        } 
        else
        {
            echo "<tr style=\"background: ". $bgcolor1 . "\"><td>" . _READ . " : <b>" . $nb_mess_lu . "</b></td></tr>\n";
        } 

        echo "<tr style=\"background: ". $bgcolor3 . "\"><td align=\"center\">\n"
		. "<input type=\"button\" value=\"" . _READPV . "\" onclick=\"document.location='index.php?file=Userbox'\" />&nbsp;"
		. "<input type=\"button\" value=\"" . _POSTPV . "\" onclick=\"document.location='index.php?file=Userbox&amp;op=post_message'\" />\n"
        . "</td></tr></table><br /><div style=\"text-align: center;\"><big>" . _YOURSTATS . "</big></div>\n"
		. "<table style=\"margin-left: auto;margin-right: auto;text-align: left;background: " . $bgcolor2 . ";border: 1px solid " . $bgcolor3 . ";\" width=\"75%\" cellpadding=\"2\" cellspacing=\"1\">\n"
		. "<tr style=\"background: ". $bgcolor3 . "\"><td align=\"center\"><b>" . _NAME . "</b></td><td align=\"center\"><b>" . _COUNT . "</b></td></tr>\n";

        $sql4 = mysql_query("SELECT id FROM " . COMMENT_TABLE . " WHERE autor_id = '" . $user[0] . "'");
        $nb_comment = mysql_num_rows($sql4);

        $sql5 = mysql_query("SELECT id FROM " . SUGGEST_TABLE . " WHERE user_id = '" . $user[0] . "'");
        $nb_suggest = mysql_num_rows($sql5);

        echo "<tr style=\"background: ". $bgcolor2 . "\"><td>" . _MESSINFORUM . "</td><td align=\"center\">" . $nb_post . "</td></tr>\n"
		. "<tr style=\"background: ". $bgcolor1 . "\"><td>" . _USERCOMMENT . "</td><td align=\"center\">" . $nb_comment . "</td></tr>\n"
		. "<tr style=\"background: ". $bgcolor2 . "\"><td>" . _USERSUGGEST . "</td><td align=\"center\">" . $nb_suggest . "</td></tr>\n"
		. "</table><br /><div style=\"text-align: center;\"><big>" . _LASTUSERMESS . "</big></div>\n"
		. "<table style=\"margin-left: auto;margin-right: auto;text-align: left;background: " . $bgcolor2 . ";border: 1px solid " . $bgcolor3 . ";\" width=\"75%\" cellpadding=\"2\" cellspacing=\"1\">\n"
		. "<tr style=\"background: ". $bgcolor3 . "\">\n"
		. "<td style=\"width: 10%;\" align=\"center\"><b>#</b></td>\n"
		. "<td style=\"width: 50%;\" align=\"center\"><b>" . _TITLE . "</b></td>\n"
		. "<td style=\"width: 40%;\" align=\"center\"><b>" . _DATE . "</b></td></tr>\n";

        if ($nb_post == 0)
        {
            echo "<tr><td align=\"center\" colspan=\"3\">" . _NOUSERMESS . "</td></tr>\n";
        } 
        else
        {
            $iforum = 0;
            $sql_forum = mysql_query("SELECT id, titre, date, thread_id, forum_id FROM " . FORUM_MESSAGES_TABLE . " WHERE auteur_id = '" . $user[0] . "' ORDER BY id DESC LIMIT 0, 10");
            while (list($mid, $subject, $date, $tid, $fid) = mysql_fetch_array($sql_forum))
            {
                $subject = stripslashes($subject);
                $subject = htmlentities($subject);
                $subject = nk_CSS($subject);
                $date = strftime("%x %H:%M", $date);

                $iforum++;

                if ($j == 0)
                {
                    $bg = $bgcolor2;
                    $j++;
                } 
                else
                {
                    $bg = $bgcolor1;
                    $j = 0;
                } 

                $sql_page = mysql_query("SELECT id FROM " . FORUM_MESSAGES_TABLE . " WHERE thread_id = '" . $tid . "'");
                $nb_rep = mysql_num_rows($sql_page);

                if ($nb_rep > $nuked['mess_forum_page'])
                {
                    $topicpages = $nb_rep / $nuked['mess_forum_page'];
                    $topicpages = ceil($topicpages);
                    $link_post = "index.php?file=Forum&amp;page=viewtopic&amp;forum_id=" . $fid . "&amp;thread_id=" . $tid . "&amp;p=" . $topicpages . "#" . $mid;
                } 
                else
                {
                    $link_post = "index.php?file=Forum&amp;page=viewtopic&amp;forum_id=" . $fid . "&amp;thread_id=" . $tid . "#" . $mid;
                } 

                echo "<tr style=\"background: ". $bg . "\">\n"
                . "<td style=\"width: 10%;\" align=\"center\">" . $iforum . "</td>\n"
                . "<td style=\"width: 50%;\"><a href=\"" . $link_post . "\">" . $subject . "</a></td>\n"
                . "<td style=\"width: 40%;\" align=\"center\">" . $date . "</td></tr>\n";
            } 

            if ($iforum == 0)
            {
                echo "<tr><td align=\"center\" colspan=\"3\">" . _NOUSERMESS . "</td></tr>\n";
            }
        } 

        echo "</table><br /><div style=\"text-align: center;\"><big>" . _LASTUSERCOMMENT . "</big></div>\n"
		. "<table style=\"margin-left: auto;margin-right: auto;text-align: left;background: " . $bgcolor2 . ";border: 1px solid " . $bgcolor3 . ";\" width=\"75%\" cellpadding=\"2\" cellspacing=\"1\">\n"
		. "<tr style=\"background: ". $bgcolor3 . "\">\n"	
		. "<td style=\"width: 10%;\" align=\"center\"><b>#</b></td>\n"
		. "<td style=\"width: 50%;\" align=\"center\"><b>" . _TITLE . "</b></td>\n"
		. "<td style=\"width: 40%;\" align=\"center\"><b>" . _DATE . "</b></td></tr>\n";

        if ($nb_comment == 0)
        {
            echo "<tr><td align=\"center\" colspan=\"3\">" . _NOUSERCOMMENT . "</td></tr>\n";
        } 
        else
        {
            $icom = 0;
            $sql_com = mysql_query("SELECT im_id, titre, module, date FROM " . COMMENT_TABLE . " WHERE autor_id = '" . $user[0] . "' ORDER BY id DESC LIMIT 0, 10");
            while (list($im_id, $titre, $module, $date) = mysql_fetch_array($sql_com))
            {
                $titre = stripslashes($titre);
                $titre = htmlentities($titre);
                $titre = nk_CSS($titre);

                if ($titre != "")
                {
                    $title = $titre;
                } 
                else
                {
                    $title = $module;
                } 

                $date = strftime("%x %H:%M", $date);

                $icom++;

                if ($j1 == 0)
                {
                    $bg1 = $bgcolor2;
                    $j1++;
                } 
                else
                {
                    $bg1 = $bgcolor1;
                    $j1 = 0;
                } 

                if ($module == "news")
                {
                    $link_title = "<a href=\"index.php?file=News&amp;op=index_comment&amp;news_id=" . $im_id . "\">" . $title . "</a>";
                } 
                else if ($module == "Gallery")
                {
                    $link_title = "<a href=\"index.php?file=Gallery&amp;op=description&amp;sid=" . $im_id . "\">" . $title . "</a>";
                } 
                else if ($module == "Wars")
                {
                    $link_title = "<a href=\"index.php?file=Wars&amp;op=detail&amp;war_id=" . $im_id . "\">" . $title . "</a>";
                } 
                else if ($module == "Links")
                {
                    $link_title = "<a href=\"index.php?file=Links&amp;op=description&amp;link_id=" . $im_id . "\">" . $title . "</a>";
                } 
                else if ($module == "Download")
                {
                    $link_title = "<a href=\"index.php?file=Download&amp;op=description&amp;dl_id=" . $im_id . "\">" . $title . "</a>";
                } 
                else if ($module == "Survey")
                {
                    $link_title = "<a href=\"index.php?file=Survey&amp;op=affich_res&amp;sid=" . $im_id . "\">" . $title . "</a>";
                } 
                else if ($module == "Sections")
                {
                    $link_title = "<a href=\"index.php?file=Sections&amp;op=article&amp;artid=" . $im_id . "\">" . $title . "</a>";
                } 

                echo "<tr style=\"background: ". $bg1 . "\">\n"
				. "<td style=\"width: 10%;\" align=\"center\">" . $icom . "</td>\n"
				. "<td style=\"width: 50%;\">" . $link_title . "</td>\n"
				. "<td style=\"width: 40%;\" align=\"center\">" . $date . "</td></tr>\n";
            } 
        } 

        echo "</table><br />\n";

        closetable();
    } 
    else
    {
        redirect("index.php?file=User&op=login_screen", 0);
    } 
} 

function reg_screen()
{
    global $nuked, $user, $language, $charte_agree, $captcha;

    if ($user)
    {
        redirect("index.php?file=User&op=edit_account", 0);
    } 

    if ($nuked['inscription'] != "off")
    {
        if ($nuked['inscription_charte'] != "" && !isset($charte_agree))
        {
            $disclaimer = $nuked['inscription_charte'];
            $disclaimer = BBcode($disclaimer);

            echo "<br /><table style=\"margin-left: auto;margin-right: auto;text-align: left;\" width=\"90%\" cellspacing=\"1\" cellpadding=\"1\" border=\"0\">\n"
            . "<tr><td align=\"center\"><big><b>" . _NEWUSERREGISTRATION . "</b></big></td></tr>\n"
            . "<tr><td>&nbsp;</td></tr><tr><td>" . $disclaimer . "</td></tr></table>\n"
            . "<form method=\"post\" action=\"index.php?file=User&amp;op=reg_screen\">\n"
            . "<div style=\"text-align: center;\"><input type=\"hidden\" name=\"charte_agree\" value=\"1\" />\n"
            . "<input type=\"submit\" value=\"" . _IAGREE . "\" />&nbsp;<input type=\"button\" value=\"" . _IDESAGREE . "\" onclick=\"javascript:history.back()\" /></div></form><br />\n";
        } 
        else
        {
            echo "<script type=\"text/javascript\">\n"
            ."<!--\n"
            . "\n"
			."function trim(string)\n"
			."{"
			."return string.replace(/(^\s*)|(\s*$)/g,'');"
			."}\n"
            ."\n"	
            . "function verifchamps()\n"
            . "{\n"
            . "pseudo = trim(document.getElementById('reg_pseudo').value);\n"
            ."\n"	
            . "if (pseudo.length < 3)\n"
            . "{\n"
            . "alert('" . _3TYPEMIN . "');\n"
            . "return false;\n"
            . "}\n";

            if ($nuked['inscription'] != "mail")
            {
			echo "\n"
	        . "pass = trim(document.getElementById('reg_pass').value);\n"
			. "if (pass.length < 4)\n"
			. "{\n"
			. "alert('" . _4TYPEMIN . "');\n"
			. "return false;\n"
			. "}\n"
			. "\n"
			. "if (document.getElementById('reg_pass').value != document.getElementById('conf_pass').value)\n"
			. "{\n"
			. "alert('" . _PASSFAILED . "');\n"
			. "return false;\n"
			. "}\n";
	            }

            echo "if (document.getElementById('reg_mail').value.indexOf('@') == -1)\n"
            . "{\n"
            . "alert('" . _MAILFAILED . "');\n"
            . "return false;\n"
            . "}\n"
            . "\n"
            . "return true;\n"
            . "}\n"
            ."\n"
            . "// -->\n"
            . "</script>\n";

            echo "<link rel=\"stylesheet\" href=\"css/checkSecurityPass.css\" type=\"text/css\" media=\"screen\" />\n"
			. "<script type=\"text/javascript\" src=\"js/checkSecurityPass.js\"></script>\n"			
			. "<br /><div style=\"text-align: center;\"><big><b>" . _NEWUSERREGISTRATION . "</b></big></div><br /><br />\n"
            . "<form method=\"post\" action=\"index.php?file=User&amp;op=reg\" onsubmit=\"return verifchamps();\">\n"
            . "<table style=\"margin-left:auto;margin-right:auto;text-align:left;width:70%;\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">\n"
            . "<tr><td><b>" . _NICK . "</b> (" . _REQUIRED . ")</td><td><input id=\"reg_pseudo\" type=\"text\" name=\"pseudo\" size=\"30\" maxlength=\"30\" /> *</td></tr>\n";

            if ($nuked['inscription'] != "mail")
            {
                echo "<tr><td><b>" . _USERPASSWORD . "</b> (" . _REQUIRED . ")</td><td><input id=\"reg_pass\" type=\"password\" onkeyup=\"evalPwd(this.value);\" name=\"pass_reg\" size=\"10\" maxlength=\"15\" /> * \n"
				. "<div id=\"sm\">" . _PASSCHECK ." <ul><li id=\"weak\" class=\"nrm\">" ._PASSWEAK . "</li><li id=\"medium\" class=\"nrm\">" ._PASSMEDIUM . "</li><li id=\"strong\" class=\"nrm\">" ._PASSHIGH . "</li></ul></div></td></tr>\n"
				. "<tr><td><b>" . _PASSCONFIRM . "</b> (" . _REQUIRED . ")</td><td><input id=\"conf_pass\" type=\"password\" name=\"pass_conf\" size=\"10\" maxlength=\"15\" /> *</td></tr>\n";
            } 

            echo "<tr><td><b>" . _MAIL . " " . _PRIVATE . "</b> (" . _REQUIRED . ")</td><td><input id=\"reg_mail\" type=\"text\" name=\"mail\" size=\"30\" maxlength=\"80\" /> *</td></tr>\n"
            . "<tr><td><b>" . _MAIL . " " . _PUBLIC . "</b> (" . _OPTIONAL . ")</td><td><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"80\" /></td></tr>\n"
            . "<tr><td><b>" . _COUNTRY . "</b> (" . _OPTIONAL . ")</td><td><select name=\"country\">";

            if ($language == "french")
            {
            	$pays = "France.gif";
            } 

            $rep = Array();
            $handle = @opendir("images/flags");
            while (false !== ($f = readdir($handle)))
            {
            	if ($f != ".." && $f != "." && $f != "index.html" && $f != "Thumbs.db")
            	{
                    $rep[] = $f;
            	}
            }

            closedir($handle);
            sort ($rep);
            reset ($rep);

            while (list ($key, $filename) = each ($rep)) 
            {
            	if ($filename == $pays)
            	{
                    $checked = "selected=\"selected\"";
            	} 
            	else
            	{
                    $checked = "";
            	} 

            	list ($country, $ext) = split ('[.]', $filename);
            	echo "<option value=\"" . $filename . "\" " . $checked . ">" . $country . "</option>\n";
            } 

            echo "</select></td></tr>\n"
            . "<tr><td><b>" . _GAME . "</b> (" . _OPTIONAL . ")</td><td><select name=\"game\">\n";

            $sql = mysql_query("SELECT id, name FROM " . GAMES_TABLE . " ORDER BY name");
            while (list($game_id, $nom) = mysql_fetch_array($sql))
            {
                $nom = stripslashes($nom);
                $nom = htmlentities($nom);
                echo "<option value=\"" . $game_id . "\">" . $nom . "</option>\n";
            } 

            echo "</select></td></tr>\n";
			
			if ($captcha == 1) create_captcha(2);
			
			echo "<tr><td colspan=\"2\">&nbsp;</td></tr>\n"
            . "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"" . _USERREGISTER . "\" /></td></tr></table></form><br />\n";
        } 
    } 
    else
    {
        echo "<br /><br /><div style=\"text-align: center;\">" . _REGISTRATIONCLOSE . "<br /><br /><a href=\"javascript:history.back()\"><b>" . _BACK . "</b></a></div><br /><br />\n";
    } 
} 

function edit_account()
{
    global $nuked, $user;

    if ($user)
    {
        $sql = mysql_query("SELECT pseudo, pass, url, mail, email, icq, msn, aim, yim, avatar, signature, country, game FROM " . USER_TABLE . " WHERE id = '" . $user[0] . "'");
        list($nick, $pass, $url, $mail, $email, $icq, $msn, $aim, $yim, $avatar, $signature, $pays, $jeu) = mysql_fetch_array($sql);
        $signature = stripslashes($signature);

        echo "<br /><div style=\"text-align: center;\"><big><b>" . _YOURACCOUNT . "</b></big></div><br />\n"
	. "<div style=\"text-align: center;\"><b><a href=\"index.php?file=User\">" . _INFO . "</a> |"
	. "</b>" . _PROFIL . "<b> | "
	. "<a href=\"index.php?file=User&amp;op=edit_pref\">" . _PREF . "</a> | "
	. "<a href=\"index.php?file=User&amp;op=change_theme\">" . _THEMESELECT . "</a> | "
	. "<a href=\"index.php?file=User&amp;nuked_nude=index&amp;op=logout\">" . _USERLOGOUT . "</a></b></div><br />\n";

        echo "<script type=\"text/javascript\">\n"
	."<!--\n"
	."\n"
	. "function verifchamps()\n"
	. "{\n"
	. "\n"
	. "if (document.getElementById('edit_pseudo').value.length < 3)\n"
	. "{\n"
	. "alert('" . _3TYPEMIN . "');\n"
	. "return false;\n"
	. "}\n"
	. "\n"
	. "if (document.getElementById('edit_mail').value.indexOf('@') == -1)\n"
	. "{\n"
	. "alert('" . _MAILFAILED . "');\n"
   	. "return false;\n"
	. "}\n"
	. "\n"
	. "return true;\n"
	. "}\n"
	."\n"
	. "// -->\n"
	. "</script>\n";

        echo "<div style=\"text-align: center;\"><small><i>" . _PASSFIELD . "</i></small></div><br />\n"
	. "<form method=\"post\" action=\"index.php?file=User&amp;op=update\" enctype=\"multipart/form-data\" onsubmit=\"return verifchamps();\">\n"
	. "<table style=\"margin-left: auto;margin-right: auto;text-align: left;\" cellspacing=\"1\" cellpadding=\"2\">\n"
    	. "<tr><td><b>" . _NICK . " : </b></td><td><input id=\"edit_pseudo\" type=\"text\" name=\"nick\" size=\"30\" maxlength=\"30\" value=\"" . $nick . "\" /> *</td></tr>\n"
	. "<tr><td><b>" . _USERPASSWORD . " : </b></td><td><input type=\"password\" name=\"pass_reg\" size=\"10\" maxlength=\"15\" /> *</td></tr>\n"
	. "<tr><td><b>" . _PASSCONFIRM . " : </b></td><td><input type=\"password\" name=\"pass_conf\" size=\"10\" maxlength=\"15\" /> *</td></tr>\n"
	. "<tr><td><b>" . _MAIL . " " . _PRIVATE . " : </b></td><td><input id=\"edit_mail\" type=\"text\" name=\"mail\" size=\"30\" maxlength=\"80\" value=\"" . $mail. "\" /> *</td></tr>\n"
	. "<tr><td colspan=\"2\">&nbsp;</td></tr>\n"
	. "<tr><td><b>" . _USERPASSWORD . " (" . _PASSOLD . ") :</b></td><td><input type=\"password\" name=\"pass_old\" size=\"10\" maxlength=\"15\" /> *</td></tr>\n"
	. "<tr><td colspan=\"2\">&nbsp;</td></tr>\n"
	. "<tr><td><b>" . _MAIL . " " . _PUBLIC . " : </b></td><td><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"80\" value=\"" . $email . "\" /></td></tr>\n"
	. "<tr><td><b>" . _ICQ . " : </b></td><td><input type=\"text\" name=\"icq\" size=\"15\" maxlength=\"15\" value=\"" . $icq . "\" /></td></tr>\n"
	. "<tr><td><b>" . _MSN . " : </b></td><td><input type=\"text\" name=\"msn\" size=\"30\" maxlength=\"80\" value=\"" . $msn . "\" /></td></tr>\n"
	. "<tr><td><b>" . _AIM . " : </b></td><td><input type=\"text\" name=\"aim\" size=\"30\" maxlength=\"30\" value=\"" . $aim . "\" /></td></tr>\n"
	. "<tr><td><b>" . _YIM . " : </b></td><td><input type=\"text\" name=\"yim\" size=\"30\" maxlength=\"30\" value=\"" . $yim . "\" /></td></tr>\n"
	. "<tr><td><b>" . _WEBSITE . " : </b></td><td><input type=\"text\" name=\"url\" size=\"40\" maxlength=\"80\" value=\"" . $url . "\" /></td></tr>\n"
	. "<tr><td><b>" . _COUNTRY . " : </b></td><td><select name=\"country\">\n";

        $rep = Array();
        $handle = @opendir("images/flags");
        while (false !== ($f = readdir($handle)))
        {
            if ($f != ".." && $f != "." && $f != "index.html" && $f != "Thumbs.db")
            {
                $rep[] = $f;
            }
	}

        closedir($handle);
	sort ($rep);
	reset ($rep);

	while (list ($key, $filename) = each ($rep)) 
	{
            if ($filename == $pays)
            {
                $checked = "selected=\"selected\"";
            } 
            else
            {
                $checked = "";
            } 

            list ($country, $ext) = split ('[.]', $filename);
            echo "<option value=\"" . $filename . "\" " . $checked . ">" . $country . "</option>\n";
	} 

        echo "</select></td></tr>"
	. "<tr><td><b>" . _GAME . " :</b></td><td><select name=\"game\">\n";

        $sql = mysql_query("SELECT id, name FROM " . GAMES_TABLE . " ORDER BY name");
        while (list($game_id, $nom) = mysql_fetch_array($sql))
        {
            if ($jeu == $game_id)
            {
                $checked1 = "selected=\"selected\"";;
            } 
            else
            {
                $checked1 = "";
            } 
            echo "<option value=\"" . $game_id . "\" " . $checked1 . ">" . $nom . "</option>\n";
        } 

        echo "</select></td></tr><tr><td colspan=\"2\">&nbsp;</td></tr>\n";
	
	if ($nuked['avatar_upload'] == "on" || $nuked['avatar_url'] == "on") 
	{
		
            echo "<tr><td><b>" . _AVATAR . " : </b></td>\n";

            if($nuked['avatar_url'] != "on") $disable = "DISABLED=\"DISABLED\"";
			else $disable = "";
			
				echo"<td><input type=\"text\" id=\"edit_avatar\" name=\"avatar\" size=\"40\" maxlength=\"100\" value=\"" . $avatar . "\" ".$disable." />"
				. "&nbsp;[ <a  href=\"#\" onclick=\"javascript:window.open('index.php?file=User&amp;nuked_nude=index&amp;op=show_avatar','Avatar','toolbar=0,location=0,directories=0,status=0,scrollbars=1,resizable=0,copyhistory=0,menuBar=0,width=350,height=450,top=30,left=0');return(false)\">" . _SEEAVATAR . "</a> ]</td></tr><tr><td>&nbsp;</td>\n";


            if ($nuked['avatar_upload'] == "on")
            {
				echo "<td><input type=\"file\" name=\"fichiernom\" /></td></tr><tr><td colspan=\"2\">&nbsp;</td></tr>\n";
            }
            else
            {
				echo "<td>&nbsp;</td></tr>\n";
            }
	}

	echo "<tr><td><b>" . _SIGN . " :</b></td><td><textarea name=\"signature\" rows=\"10\" cols=\"60\">" . $signature . "</textarea></td></tr>\n";


	if ($nuked['user_delete'] == "on")
	{
            echo "<tr><td colspan=\"2\">&nbsp;</td></tr><tr><td colspan=\"2\" align=\"center\">"._DELMYACCOUNT." <input class=\"checkbox\" type=\"checkbox\" name=\"remove\" value=\"ok\" /></td></tr>\n";
	}

	echo"<tr><td colspan=\"2\">&nbsp;</td></tr><tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"" . _MODIF . "\" />\n"
	. "<input type=\"hidden\" name=\"pass\" value=\"" . $pass . "\" /></td></tr></table></form><br />\n";
    } 
    else
    {
        echo "<br /><br /><div style=\"text-align: center;\">" . _USERENTRANCE . "</div><br /><br />";
        redirect("index.php?file=User&op=login_screen", 2);
    } 
} 


function edit_pref()
{
    global $user, $nuked, $bgcolor3, $bgcolor2, $bgcolor1;

    if ($user)
    {

	$sql = mysql_query("SELECT prenom, age, sexe, ville, motherboard, cpu, ram, video, resolution, son, ecran, souris, clavier, connexion, system, photo, pref_1, pref_2, pref_3, pref_4, pref_5 FROM " . USER_DETAIL_TABLE . " WHERE user_id = '" . $user[0] . "'");
	list($prenom, $age, $sexe, $ville, $motherboard, $cpu, $ram, $video, $resolution, $sons, $ecran, $souris, $clavier, $connexion, $osystem, $photo, $pref1, $pref2, $pref3, $pref4, $pref5) = mysql_fetch_array($sql);

	$prenom = stripslashes($prenom);
	$ville = stripslashes($ville);
	$motherboard = stripslashes($motherboard);
	$cpu = stripslashes($cpu);
	$ram = stripslashes($ram);
	$video = stripslashes($video);
	$resolution = stripslashes($resolution);
	$sons = stripslashes($sons);
	$ecran = stripslashes($ecran);
	$souris = stripslashes($souris);
	$clavier = stripslashes($clavier);
	$connexion = stripslashes($connexion);
	$osystem = stripslashes($osystem);
	$photo = stripslashes($photo);
	$pref1 = stripslashes($pref1);
	$pref2 = stripslashes($pref2);
	$pref3 = stripslashes($pref3);
	$pref4 = stripslashes($pref4);
	$pref5 = stripslashes($pref5);

	if ($age != "")
	{
            list ($jour, $mois, $an) = split ('[/]', $age);
	} 

	echo "<br /><div style=\"text-align: center;\"><big><b>" . _YOURACCOUNT . "</b></big></div><br />\n"
	. "<div style=\"text-align: center;\"><b><a href=\"index.php?file=User\">" . _INFO . "</a> |"
	. "<a href=\"index.php?file=User&amp;op=edit_account\">" . _PROFIL . "</a> | "
	. "</b>" . _PREF . "<b> | "
	. "<a href=\"index.php?file=User&amp;op=change_theme\">" . _THEMESELECT . "</a> | "
	. "<a href=\"index.php?file=User&amp;nuked_nude=index&amp;op=logout\">" . _USERLOGOUT . "</a></b></div><br />\n";

	echo "<form method=\"post\" action=\"index.php?file=User&amp;op=update_pref\" enctype=\"multipart/form-data\">\n"
	. "<table style=\"margin-left: auto;margin-right: auto;text-align: left;background: " . $bgcolor2 . ";border: 1px solid " . $bgcolor3 . ";\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">\n"
	. "<tr style=\"background: " . $bgcolor3 . ";\"><td align=\"center\" colspan=\"2\"><b>" . _INFOPERSO . "</b></td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _LASTNAME . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"prenom\" value=\"" . $prenom . "\" size=\"20\" /></td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _BIRTHDAY . " :</b></td><td style=\"width: 70%;\" align=\"left\"><select name=\"jour\">\n";

	if ($jour != "")
	{
            echo "<option>" . $jour . "</option>\n";
	} 
	else
	{
            $checked1 = "selected=\"selected\"";
	} 

	$day = 1;
	while ($day < 32)
	{
            if ($day == date("d"))
            {
                echo "<option value=\"" . $day . "\" " . $checked1 . ">" . $day . "</option>\n";
            } 
            else
            {
                echo "<option value=\"" . $day . "\">" . $day . "</option>\n";
            } 
            $day++;
	} 

    	echo "</select>&nbsp;<select name=\"mois\">\n";

    	if ($mois != "")
    	{
            echo "<option value=\"" . $mois . "\">" . $mois . "</option>\n";
    	} 
    	else
    	{
            $checked2 = "selected=\"selected\"";
    	} 

    	$month = 1;
    	while ($month < 13)
    	{
            if ($month == date("m"))
            {
		echo "<option value=\"" . $month . "\" " . $checked2 . ">" . $month . "</option>\n";
            } 
            else
            {
		echo "<option value=\"" . $month . "\">" . $month . "</option>\n";
            } 
            $month++;
    	} 

	echo "</select>&nbsp;<select name=\"an\">\n";

	if ($an != "")
	{
            echo "<option value=\"" . $an . "\">" . $an . "</option>\n";
	} 
	else
	{
            $checked3 = "selected=\"selected\"";
	} 

	$year = 1900;
	$lastyear = date("Y") + 1;

	while ($year < $lastyear)
	{
            if ($year == date("Y"))
            {
		echo "<option value=\"" . $year . "\" " . $checked3 . ">" . $year . "</option>\n";
            } 
            else
            {
		echo "<option value=\"" . $year . "\">" . $year . "</option>\n";
            } 
            $year++;
	} 

	echo "</select></td></tr>";

        if ($sexe == "male")
	{ 
            $checked4 = "checked=\"checked\"";
	}
        else if ($sexe == "female")
	{ 
            $checked5 = "checked=\"checked\"";
	}
        else
	{ 
            $checked4 = "";
            $checked5 = "";
	}

	echo "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _SEXE . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"radio\" class=\"checkbox\" name=\"sexe\" value=\"male\" " . $checked4 . " /> " . _MALE . " <input type=\"radio\" class=\"checkbox\" name=\"sexe\" value=\"female\" " . $checked5 . " /> " . _FEMALE . "</td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _CITY . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"ville\" value=\"" . $ville . "\" size=\"20\" /></td></tr>\n";
	
	
		if ($nuked['avatar_upload'] == "on" || $nuked['avatar_url'] == "on") 
	{
		
            echo "<tr><td><b>" . _PHOTO . " (100x100) : </b></td>\n";
			
			

            if($nuked['avatar_url'] != "on") $disable = "DISABLED=\"DISABLED\"";
			else $disable = "";
			
				echo"<td align=\"left\"><input type=\"text\" id=\"photo\" name=\"photo\" size=\"40\" maxlength=\"150\" value=\"" . $photo . "\" " . $disable . " /></td></tr>\n";


            if ($nuked['avatar_upload'] == "on")
            {
				echo "<tr><td style=\"width: 30%;\">&nbsp;</td><td style=\"width: 70%;\" align=\"left\"><input type=\"file\" name=\"fichiernom\" /></td></tr>\n";
            }

	}
	
	echo "<tr style=\"background: " . $bgcolor3 . ";\"><td align=\"center\" colspan=\"2\"><b>" . _HARDCONFIG . "</b></td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _MOTHERBOARD . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"motherboard\" value=\"" . $motherboard . "\" size=\"25\" /></td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _PROCESSOR . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"cpu\" value=\"" . $cpu . "\" size=\"25\" /></td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _MEMORY . " :</b></td><td style=\"width: 70%;\" align=\"left\"><select name=\"ram\"><option>" . $ram . "</option>\n"
	. "<option>128 Mo</option>\n"
	. "<option>256 Mo</option>\n"
	. "<option>512 Mo</option>\n"
	. "<option>1 Go</option>\n"
	. "<option>1,5 Go</option>\n"
	. "<option>2 Go</option>\n"
	. "<option>3 Go</option>\n"
	. "<option>4 Go +</option>\n"
	. "</select></td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _VIDEOCARD . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"video\" value=\"" . $video . "\" size=\"25\" /></td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _RESOLUTION . " :</b></td><td style=\"width: 70%;\" align=\"left\"><select name=\"resolution\"><option>" . $resolution . "</option>\n"
	. "<option>640/480</option>\n"
	. "<option>800/600</option>\n"
	. "<option>1024/768</option>\n"
	. "<option>1152/864</option>\n"
	. "<option>1280/1024</option>\n"
	. "<option>1600/1200</option>\n"
	. "<option>1920/1200</option>\n"
	. "<option>2048/1536</option></select></td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _SOUNDCARD . " : </b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"sons\" value=\"" . $sons . "\" size=\"25\" /></td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _MONITOR . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"ecran\" value=\"" . $ecran . "\" size=\"25\" /></td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _MOUSE . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"souris\" value=\"" . $souris . "\" size=\"25\" /></td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _KEYBOARD . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"clavier\" value=\"" . $clavier . "\" size=\"25\" /></td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _CONNECT . " :</b></td><td style=\"width: 70%;\" align=\"left\"><select name=\"connexion\"><option>" . $connexion . "</option>\n"
	. "<option>Modem 56K</option>\n"
	. "<option>Modem 128K</option>\n"
	. "<option>ADSL 128K</option>\n"
	. "<option>ADSL 512K</option>\n"
	. "<option>ADSL 1024K</option>\n"
	. "<option>ADSL 2048K</option>\n"
	. "<option>ADSL 3M</option>\n"
	. "<option>ADSL 4M</option>\n"
	. "<option>ADSL 5M</option>\n"
	. "<option>ADSL 8M</option>\n"
	. "<option>ADSL 20M +</option>\n"
	. "<option>Cable 128K</option>\n"
	. "<option>Cable 512K</option>\n"
	. "<option>Cable 1024K</option>\n"
	. "<option>Cable 2048K</option>\n"
	. "<option>Cable 8M</option>\n"
	. "<option>Cable 20M +</option>\n"
	. "<option>T1 1,5M</option>\n"
	. "<option>T2 6M</option>\n"
	. "<option>T3 45M</option>\n"
	. "<option>" . _OTHER . "</option></select></td></tr>\n"
	. "<tr><td style=\"width: 30%;\" align=\"left\"><b> " . _SYSTEMOS . " :</b></td><td style=\"width: 70%;\" align=\"left\"><select name=\"osystem\"><option>" . $osystem . "</option>\n"
	. "<option>Windows 98</option>\n"
	. "<option>Windows ME</option>\n"
	. "<option>Windows 2000</option>\n"
	. "<option>Windows XP</option>\n"
	. "<option>Windows Vista</option>\n"
	. "<option>Linux</option>\n"
	. "<option>Mac Os</option></select></td></tr>\n";

    	$sql2 = mysql_query("SELECT team, team2, team3, game FROM " . USER_TABLE . " WHERE id = '" . $user[0] . "'");
    	list($team, $team2, $team3, $game_id) = mysql_fetch_array($sql2);

            if ($team != "" || $team2 != "" || $team3 != "")
            {
		$i = 0;

        	if ($team != "")
        	{
		    $sql_game1 = mysql_query("SELECT game FROM " . TEAM_TABLE . " WHERE cid = '" . $team . "'");
            	    list($game1) = mysql_fetch_array($sql_game1);

		    if ($game1 > 0)
		    {
			$sql3 = mysql_query("SELECT titre, pref_1, pref_2, pref_3, pref_4, pref_5 FROM " . GAMES_TABLE . " WHERE id = '" . $game1 . "'");
			list($g1_titre, $g1_pref_1, $g1_pref_2, $g1_pref_3, $g1_pref_4, $g1_pref_5) = mysql_fetch_array($sql3);

			$g1_titre = stripslashes($g1_titre);
			$g1_pref_1 = stripslashes($g1_pref_1);
			$g1_pref_2 = stripslashes($g1_pref_2);
			$g1_pref_3 = stripslashes($g1_pref_3);
			$g1_pref_4 = stripslashes($g1_pref_4);
			$g1_pref_5 = stripslashes($g1_pref_5);

			$g1_titre = htmlentities($g1_titre);
			$g1_pref_1 = htmlentities($g1_pref_1);
			$g1_pref_2 = htmlentities($g1_pref_2);
			$g1_pref_3 = htmlentities($g1_pref_3);
			$g1_pref_4 = htmlentities($g1_pref_4);
			$g1_pref_5 = htmlentities($g1_pref_5);

			$sql4 = mysql_query("SELECT pref_1, pref_2, pref_3, pref_4, pref_5 FROM " . GAMES_PREFS_TABLE . " WHERE id = '" . $game1 . "' AND user_id = '" . $user[0] . "'");
			$test1 = mysql_num_rows($sql4);

			if ($test1 > 0)
			{
			    list($g1_pref1, $g1_pref2, $g1_pref3, $g1_pref4, $g1_pref5) = mysql_fetch_array($sql4);
			} 
			else if ($game1 == $game_id)
			{
			    $g1_pref1 = $pref1;
			    $g1_pref2 = $pref2;
			    $g1_pref3 = $pref3;
			    $g1_pref4 = $pref4;
			    $g1_pref5 = $pref5;
			} 

			$g1_pref1 = stripslashes($g1_pref1);
			$g1_pref2 = stripslashes($g1_pref2);
			$g1_pref3 = stripslashes($g1_pref3);
			$g1_pref4 = stripslashes($g1_pref4);
			$g1_pref5 = stripslashes($g1_pref5);

			echo "<tr style=\"background: " . $bgcolor3 . ";\"><td align=\"center\" colspan=\"2\"><b>" . $g1_titre . "</b></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g1_pref_1 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref1[" . $i . "]\" value=\"" . $g1_pref1 . "\" size=\"25\" /></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g1_pref_2 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref2[" . $i . "]\" value=\"" . $g1_pref2 . "\" size=\"25\" /></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g1_pref_3 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref3[" . $i . "]\" value=\"" . $g1_pref3 . "\" size=\"25\" /></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g1_pref_4 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref4[" . $i . "]\" value=\"" . $g1_pref4 . "\" size=\"25\" /></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g1_pref_5 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref5[" . $i . "]\" value=\"" . $g1_pref5 . "\" size=\"25\" /><input type=\"hidden\" name=\"game_id[" . $i . "]\" value=\"" . $game1 . "\" /></td></tr>\n";

			$i++;
		    } 
        	} 
        	else
        	{
		    $game1 = 0;
        	} 

        	if ($team2 != "")
        	{
		    $sql_game2 = mysql_query("SELECT game FROM " . TEAM_TABLE . " WHERE cid = '" . $team2 . "'");
		    list($game2) = mysql_fetch_array($sql_game2);

		    if ($game2 > 0 && $game2 <> $game1)
		    {
			$sql5 = mysql_query("SELECT titre, pref_1, pref_2, pref_3, pref_4, pref_5 FROM " . GAMES_TABLE . " WHERE id = '" . $game2 . "'");
			list($g2_titre, $g2_pref_1, $g2_pref_2, $g2_pref_3, $g2_pref_4, $g2_pref_5) = mysql_fetch_array($sql5);

			$g2_titre = stripslashes($g2_titre);
			$g2_pref_1 = stripslashes($g2_pref_1);
			$g2_pref_2 = stripslashes($g2_pref_2);
			$g2_pref_3 = stripslashes($g2_pref_3);
			$g2_pref_4 = stripslashes($g2_pref_4);
			$g2_pref_5 = stripslashes($g2_pref_5);

			$g2_titre = htmlentities($g2_titre);
			$g2_pref_1 = htmlentities($g2_pref_1);
			$g2_pref_2 = htmlentities($g2_pref_2);
			$g2_pref_3 = htmlentities($g2_pref_3);
			$g2_pref_4 = htmlentities($g2_pref_4);
			$g2_pref_5 = htmlentities($g2_pref_5);

			$sql6 = mysql_query("SELECT pref_1, pref_2, pref_3, pref_4, pref_5 FROM " . GAMES_PREFS_TABLE . " WHERE id = '" . $game2 . "' AND user_id = '" . $user[0] . "'");
			$test2 = mysql_num_rows($sql6);

			if ($test2 > 0)
			{
			    list($g2_pref1, $g2_pref2, $g2_pref3, $g2_pref4, $g2_pref5) = mysql_fetch_array($sql6);
			} 
			else if ($game2 == $game_id)
			{
			    $g2_pref1 = $pref1;
			    $g2_pref2 = $pref2;
			    $g2_pref3 = $pref3;
			    $g2_pref4 = $pref4;
			    $g2_pref5 = $pref5;
			} 

			$g2_pref1 = stripslashes($g2_pref1);
			$g2_pref2 = stripslashes($g2_pref2);
			$g2_pref3 = stripslashes($g2_pref3);
			$g2_pref4 = stripslashes($g2_pref4);
			$g2_pref5 = stripslashes($g2_pref5);

			echo "<tr style=\"background: " . $bgcolor3 . ";\"><td align=\"center\" colspan=\"2\"><b>" . $g2_titre . "</b></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g2_pref_1 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref1[" . $i . "]\" value=\"" . $g2_pref1 . "\" size=\"25\" /></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g2_pref_2 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref2[" . $i . "]\" value=\"" . $g2_pref2 . "\" size=\"25\" /></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g2_pref_3 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref3[" . $i . "]\" value=\"" . $g2_pref3 . "\" size=\"25\" /></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g2_pref_4 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref4[" . $i . "]\" value=\"" . $g2_pref4 . "\" size=\"25\" /></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g2_pref_5 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref5[" . $i . "]\" value=\"" . $g2_pref5 . "\" size=\"25\" /><input type=\"hidden\" name=\"game_id[" . $i . "]\" value=\"" . $game2 . "\" /></td></tr>\n";

			$i++;
		    } 
        	} 
        	else
        	{
		    $game2 = 0;
        	} 

		if ($team3 != "")
		{
		    $sql_game3 = mysql_query("SELECT game FROM " . TEAM_TABLE . " WHERE cid = '" . $team3 . "'");
		    list($game3) = mysql_fetch_array($sql_game3);

		    if ($game3 > 0 && $game3 <> $game2 && $game3 <> $game1)
		    {
			$sql7 = mysql_query("SELECT titre, pref_1, pref_2, pref_3, pref_4, pref_5 FROM " . GAMES_TABLE . " WHERE id = '" . $game3 . "'");
			list($g3_titre, $g3_pref_1, $g3_pref_2, $g3_pref_3, $g3_pref_4, $g3_pref_5) = mysql_fetch_array($sql7);

			$g3_titre = stripslashes($g3_titre);
			$g3_pref_1 = stripslashes($g3_pref_1);
			$g3_pref_2 = stripslashes($g3_pref_2);
			$g3_pref_3 = stripslashes($g3_pref_3);
			$g3_pref_4 = stripslashes($g3_pref_4);
			$g3_pref_5 = stripslashes($g3_pref_5);

			$g3_titre = htmlentities($g3_titre);
			$g3_pref_1 = htmlentities($g3_pref_1);
			$g3_pref_2 = htmlentities($g3_pref_2);
			$g3_pref_3 = htmlentities($g3_pref_3);
			$g3_pref_4 = htmlentities($g3_pref_4);
			$g3_pref_5 = htmlentities($g3_pref_5);

			$sql8 = mysql_query("SELECT pref_1, pref_2, pref_3, pref_4, pref_5 FROM " . GAMES_PREFS_TABLE . " WHERE id = '" . $game3 . "' AND user_id = '" . $user[0] . "'");
			$test3 = mysql_num_rows($sql8);

			if ($test3 > 0)
			{
			    list($g3_pref1, $g3_pref2, $g3_pref3, $g3_pref4, $g3_pref5) = mysql_fetch_array($sql8);
			} 
			else if ($game3 == $game_id)
			{
			    $g3_pref1 = $pref1;
			    $g3_pref2 = $pref2;
			    $g3_pref3 = $pref3;
			    $g3_pref4 = $pref4;
			    $g3_pref5 = $pref5;
			} 

			$g3_pref1 = stripslashes($g3_pref1);
			$g3_pref2 = stripslashes($g3_pref2);
			$g3_pref3 = stripslashes($g3_pref3);
			$g3_pref4 = stripslashes($g3_pref4);
			$g3_pref5 = stripslashes($g3_pref5);

			echo "<tr style=\"background: " . $bgcolor3 . ";\"><td align=\"center\" colspan=\"2\"><b>" . $g3_titre . "</b></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g3_pref_1 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref1[" . $i . "]\" value=\"" . $g3_pref1 . "\" size=\"25\" /></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g3_pref_2 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref2[" . $i . "]\" value=\"" . $g3_pref2 . "\" size=\"25\" /></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g3_pref_3 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref3[" . $i . "]\" value=\"" . $g3_pref3 . "\" size=\"25\" /></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g3_pref_4 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref4[" . $i . "]\" value=\"" . $g3_pref4 . "\" size=\"25\" /></td></tr>\n"
			. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $g3_pref_5 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref5[" . $i . "]\" value=\"" . $g3_pref5 . "\" size=\"25\" /><input type=\"hidden\" name=\"game_id[" . $i . "]\" value=\"" . $game3 . "\" /></td></tr>\n";

			$i++;
		    } 
		} 
		else
		{
		    $game3 = 0;
		} 

		if ($game1 == 0 && $game2 == 0 && $game3 == 0)
		{
		    $sql3 = mysql_query("SELECT titre, pref_1, pref_2, pref_3, pref_4, pref_5 FROM " . GAMES_TABLE . " WHERE id = '" . $game_id . "'");
		    list($titre, $pref_1, $pref_2, $pref_3, $pref_4, $pref_5) = mysql_fetch_array($sql3);

		    $titre = stripslashes($titre);
		    $pref_1 = stripslashes($pref_1);
		    $pref_2 = stripslashes($pref_2);
		    $pref_3 = stripslashes($pref_3);
		    $pref_4 = stripslashes($pref_4);
		    $pref_5 = stripslashes($pref_5);

		    $titre = htmlentities($titre);
		    $pref_1 = htmlentities($pref_1);
		    $pref_2 = htmlentities($pref_2);
		    $pref_3 = htmlentities($pref_3);
		    $pref_4 = htmlentities($pref_4);
		    $pref_5 = htmlentities($pref_5);

		    echo "<tr style=\"background: " . $bgcolor3 . ";\"><td align=\"center\" colspan=\"2\"><b>" . $titre . "</b></td></tr>"
		    . "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $pref_1 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref1\" value=\"" . $pref1 . "\" size=\"25\" /></td></tr>\n"
		    . "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $pref_2 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref2\" value=\"" . $pref2 . "\" size=\"25\" /></td></tr>\n"
		    . "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $pref_3 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref3\" value=\"" . $pref3 . "\" size=\"25\" /></td></tr>\n"
		    . "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $pref_4 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref4\" value=\"" . $pref4 . "\" size=\"25\" /></td></tr>\n"
		    . "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $pref_5 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref5\" value=\"" . $pref5 . "\" size=\"25\" /></td></tr>\n";
		} 

            } 
            else
            {
		$sql3 = mysql_query("SELECT titre, pref_1, pref_2, pref_3, pref_4, pref_5 FROM " . GAMES_TABLE . " WHERE id = '" . $game_id . "'");
		list($titre, $pref_1, $pref_2, $pref_3, $pref_4, $pref_5) = mysql_fetch_array($sql3);

		$titre = stripslashes($titre);
		$pref_1 = stripslashes($pref_1);
		$pref_2 = stripslashes($pref_2);
		$pref_3 = stripslashes($pref_3);
		$pref_4 = stripslashes($pref_4);
		$pref_5 = stripslashes($pref_5);

		$titre = htmlentities($titre);
		$pref_1 = htmlentities($pref_1);
		$pref_2 = htmlentities($pref_2);
		$pref_3 = htmlentities($pref_3);
		$pref_4 = htmlentities($pref_4);
		$pref_5 = htmlentities($pref_5);

		echo "<tr style=\"background: " . $bgcolor3 . ";\"><td align=\"center\" colspan=\"2\"><b>" . $titre . "</b></td></tr>\n"
		. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $pref_1 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref1\" value=\"" . $pref1 . "\" size=\"25\" /></td></tr>\n"
		. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $pref_2 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref2\" value=\"" . $pref2 . "\" size=\"25\" /></td></tr>\n"
		. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $pref_3 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref3\" value=\"" . $pref3 . "\" size=\"25\" /></td></tr>\n"
		. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $pref_4 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref4\" value=\"" . $pref4 . "\" size=\"25\" /></td></tr>\n"
		. "<tr><td style=\"width: 30%;\" align=\"left\"><b>" . $pref_5 . " :</b></td><td style=\"width: 70%;\" align=\"left\"><input type=\"text\" name=\"pref5\" value=\"" . $pref5 . "\" size=\"25\" /></td></tr>\n";
            } 

	echo "</table><div style=\"text-align: center;\"><br /><input type=\"submit\" value=\"" . _MODIFPREF . "\" /></div></form><br />\n";
    } 
    else
    {
        echo "<br /><br /><div style=\"text-align: center;\">" . _USERENTRANCE . "</div><br /><br />";
        redirect("index.php?file=User&op=login_screen", 2);
    }  

} 

function login_screen()
{
    global $error, $nuked, $user;

    if ($user)
    {
        redirect("index.php?file=User", 0);
    }
    else
    {
    opentable();


    if ($error == 1)
    {
        $error = "<br /><div style=\"text-align: center;\">" . _NOFIELD . "</div><br />\n";
    } 
    else if ($error == 2)
    {
        $error = "<br /><div style=\"text-align: center;\">" . _BADLOG . "</div><br />\n";
    } 
    else
    {
        $error = "";
    } 

    echo $error . "<br /><div style=\"text-align: center;\"><big><b>" . _LOGINUSER . "</b></big></div><br /><br />\n"
    . "<form action=\"index.php?file=User&amp;nuked_nude=index&amp;op=login\" method=\"post\">\n"
    . "<table style=\"margin-left: auto;margin-right: auto;text-align: left;\">\n"
    . "<tr><td><b>" . _NICK . " :</b></td><td><input type=\"text\" name=\"pseudo\" size=\"15\" maxlength=\"180\" /></td></tr>\n"
    . "<tr><td><b>" . _PASSWORD . " :</b></td><td><input type=\"password\" name=\"pass\" size=\"15\" maxlength=\"15\" /></td></tr>\n"
    . "<tr><td colspan=\"2\"><input type=\"checkbox\" class=\"checkbox\" name=\"remember_me\" value=\"ok\" checked=\"checked\" /><small>&nbsp;" . _REMEMBERME . "</small></td></tr>\n"
    . "<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"" . _TOLOG . "\" /></td></tr><tr><td colspan=\"2\">&nbsp;</td></tr>\n"
    . "<tr><td colspan=\"2\"><b><a href=\"index.php?file=User&amp;op=reg_screen\">" . _USERREGISTER . "</a> | <a href=\"index.php?file=User&amp;op=oubli_pass\">" . _LOSTPASS . "</a></b></td></tr></table></form><br />\n";

    closetable();
    }
} 


function reg($pseudo, $mail, $email, $pass_reg, $pass_conf, $game, $country)
{
    global $nuked, $captcha;

    // Verification code captcha
    if ($captcha == 1 && $_POST['code_confirm'] != crypt_captcha($_POST['code']))
    {
	echo "<br /><br /><div style=\"text-align: center;\">" . _BADCODECONFIRM . "<br /><br /><a href=\"javascript:history.back()\">[ <b>" . _BACK . "</b> ]</a></div><br /><br />";
        closetable();
        footer();
        exit();
    } 

    $pseudo = htmlentities($pseudo, ENT_QUOTES);
    $pseudo = verif_pseudo($pseudo);

    $mail = addslashes($mail);
    $mail = htmlentities($mail);

    if ($pseudo == "error1")
    {
        echo "<br /><br /><div style=\"text-align: center;\">" . _BADUSERNAME . "</div><br /><br />";
        redirect("index.php?file=User&op=reg_screen", 2);
        closetable();
        footer();
        exit();
    } 

    if ($pseudo == "error2")
    {
        echo "<br /><br /><div style=\"text-align: center;\">" . _NICKINUSE . "</div><br /><br />";
        redirect("index.php?file=User&op=reg_screen", 2);
        closetable();
        footer();
        exit();
    } 

    if ($pseudo == "error3")
    {
        echo "<br /><br /><div style=\"text-align: center;\">" . _NICKBANNED . "</div><br /><br />";
        redirect("index.php?file=User&op=reg_screen", 2);
        closetable();
        footer();
        exit();
    } 

    if (strlen($pseudo) > 30)
    {
        echo "<br /><br /><div style=\"text-align: center;\">" . _NICKTOLONG . "</div><br /><br />";
        redirect("index.php?file=User&op=reg_screen", 2);
        closetable();
        footer();
        exit();
    } 

    $sql2 = mysql_query("SELECT mail FROM " . USER_TABLE . " WHERE mail = '" . $mail . "'");
    $reserved_email = mysql_num_rows($sql2);

    $sql3 = mysql_query("SELECT email FROM " . BANNED_TABLE . " WHERE email = '" . $mail . "'");
    $banned_email = mysql_num_rows($sql3);

    if ($reserved_email > 0)
    {
        echo "<br /><br /><div style=\"text-align: center;\">" . _MAILINUSE . "</div><br /><br />";
        redirect("index.php?file=User&op=reg_screen", 2);
        closetable();
        footer();
        exit();
    } 


    if ($banned_email > 0)
    {
        echo "<br /><br /><div style=\"text-align: center;\">" . _MAILBANNED . "</div><br /><br />";
        redirect("index.php?file=User&op=reg_screen", 2);
        closetable();
        footer();
        exit();
    } 


    if ($nuked['inscription'] == "mail")
    {
        $lettres = "abCdefGhijklmNopqrstUvwXyz0123456789";
        srand(time());
        for ($i = 0;$i < 5;$i++)
        {
            $rand_pass .= substr($lettres, (rand() % (strlen($lettres))), 1);
        } 
        $pass_reg = $rand_pass;
        $pass_conf = $rand_pass;
    } 

    if ($pass_reg != $pass_conf)
    {
        echo "<br /><br /><div style=\"text-align: center;\">" . stripslashes(_PASSFAILED) . "</div><br /><br />";
        redirect("index.php?file=User&op=reg_screen", 1);
        closetable();
        footer();
        exit();
    } 

    else if ($pass_reg == $pass_conf)
    {
        $date = time();
        if ($system == 0)
        {
            $cryptpass = md5($pass_reg);
        } 
        else
        {
            $cryptpass = $pass_reg;
        } 

        $user_id = "";
        $taille = 20;
        $lettres = "abCdefGhijklmNopqrstUvwXyz0123456789";
        srand(time());
        for ($i = 0;$i < $taille;$i++)
        {
            $user_id .= substr($lettres, (rand() % (strlen($lettres))), 1);
        } 

        $email = addslashes($email);
        $email = htmlentities($email);

        if ($nuked['validation'] == auto)
        {
            $niveau = 1;
        } 
        else
        {
            $niveau = 0;
        } 

        $date2 = strftime("%x %H:%M", time());
        $add = mysql_query("INSERT INTO " . USER_TABLE . " ( `id` , `team` , `team2` , `team3` , `rang` , `ordre` , `pseudo` , `mail` , `email` , `icq` , `msn` , `aim` , `yim` , `url` , `pass` , `niveau` , `date` , `avatar` , `signature` , `user_theme` , `user_langue` , `game` , `country` , `count` ) VALUES ( '" . $user_id . "' , '' , '' , '' , '' , '' , '" . $pseudo . "' , '" . $mail . "' , '" . $email . "' , '' , '' , '' , '' , '' , '" . $cryptpass . "' , '" . $niveau . "' , '" . $date . "' , '' , '' , '' , '' , '" . $game . "' , '" . $country . "' , '' )");

        if ($nuked['validation'] == "mail" && $nuked['inscription'] == "on")
        {
            $subject = _USERREGISTER . ", " . $date2;
            $corps = _USERVALID . "\r\n" . $nuked['url'] . "/index.php?file=User&op=validation&id_user=" . $user_id . "\r\n\r\n" . _USERMAIL . "\r\n" . _NICK . " : " . $pseudo . "\r\n" . _PASSWORD . " : " . $pass_reg . "\r\n\r\n\r\n" . $nuked['name'] . " - " . $nuked['slogan'];
            $from = "From: " . $nuked['name'] . " <" . $nuked['mail'] . ">\r\nReply-To: " . $nuked['mail'];

            $subject = @html_entity_decode($subject);
            $corps = @html_entity_decode($corps);
            $from = @html_entity_decode($from);
            $s_mail = @html_entity_decode($mail);
	
            mail($s_mail, $subject, $corps, $from);
        } 
        else
        {
            if ($nuked['inscription'] == "mail" || ($nuked['inscription_mail'] != "" && $nuked['validation'] == "auto"))
            {
                if ($nuked['inscription_mail'] != "")
                {
                    $inscription_mail = $nuked['inscription_mail'];
                } 
                else
                {
                    $inscription_mail = _USERMAIL;
                } 

                $subject = _USERREGISTER . ", " .$date2;
                $corps = $inscription_mail . "\r\n" . _NICK . " : " . $pseudo . "\r\n" . _PASSWORD . " : " . $pass_reg . "\r\n\r\n\r\n" . $nuked['name'] . " - " . $nuked['slogan'];
                $from = "From: " . $nuked['name'] . " <" . $nuked['mail'] . ">\r\nReply-To: " . $nuked['mail'];	

                $subject = @html_entity_decode($subject);
                $corps = @html_entity_decode($corps);
                $from = @html_entity_decode($from);
		$s_mail = @html_entity_decode($mail);

                mail($s_mail, $subject, $corps, $from);
            } 
        } 

        if ($nuked['inscription_avert'] == "on" || $nuked['validation'] == "admin")
        {

            $subject = _NEWUSER . " : " . $pseudo . ", " .$date2;
            $corps =  $pseudo . " " . _NEWREGISTRATION . " " . $nuked['name'] . " " . _NEWREGSUITE . "\r\n\r\n\r\n" . $nuked['name'] . " - " . $nuked['slogan'];
            $from = "From: " . $nuked['name'] . " <" . $nuked['mail'] . ">\r\nReply-To: " . $nuked['mail'];

            $subject = @html_entity_decode($subject);
            $corps = @html_entity_decode($corps);
            $from = @html_entity_decode($from);	

            mail($nuked['mail'], $subject, $corps, $from);
        } 

        if ($nuked['validation'] == "mail" && $nuked['inscription'] == "on")
        {
            echo "<br /><br /><div style=\"text-align: center;\">" . _VALIDMAILSUCCES . "&nbsp;" . $mail . "</div><br /><br />";
            redirect("index.php?file=User&op=login_screen", 5);
        } 

        else if ($nuked['validation'] == "admin" && $nuked['inscription'] == "on")
        {
            echo "<br /><br /><div style=\"text-align: center;\">" . _VALIDADMIN . "</div><br /><br />";
            redirect("index.php", 5);
        } 

        else if ($nuked['inscription'] == "mail")
        {
            echo "<br /><br /><div style=\"text-align: center;\">" . _USERMAILSUCCES . "&nbsp;" . $mail . "</div><br /><br />";
            redirect("index.php?file=User&op=login_screen", 5);
        } 
        else
        {
            echo "<br /><br /><div style=\"text-align: center;\">" . _REGISTERSUCCES . "</div><br /><br />";
            redirect("index.php?file=User&nuked_nude=index&op=login&pseudo=" . $pseudo . "&pass=" . $pass_reg . "&remember_me=ok", 2);
        } 
    } 
} 


function login($pseudo, $pass, $remember_me)
{
    global $bgcolor3, $bgcolor2, $bgcolor1, $nuked, $theme, $cookie_theme, $cookie_langue, $timelimit, $user;

    $cookiename = $nuked['cookiename'];
    $pass2 = $pass;

    if ($pseudo == "" || $pass == "")
    {
        $error = 1;
        $url = "index.php?file=User&op=login_screen&error=" . $error;
        redirect($url, 0);
    } 

    $sql = mysql_query("SELECT id, pass, user_theme, user_langue, niveau FROM " . USER_TABLE . " WHERE pseudo = '" . htmlentities($pseudo, ENT_QUOTES) . "'");
    $check = mysql_num_rows($sql);

    if($check > 0)
    {
	
	list($id_user, $dbpass, $usertheme, $userlang, $niveau) = mysql_fetch_array($sql);
	$pass = md5($pass);

	if ($niveau > 0)
	{
            if (strcmp($dbpass, $pass))
            {
		$error = 2;
		$url = "index.php?file=User&op=login_screen&error=" . $error;
		redirect($url, 0);
            } 
            else
            {
		session_new($id_user, $remember_me);

		if ($usertheme != "")
		{
                    setcookie($cookie_theme, $usertheme, $timelimit);
		} 

		if ($userlang != "")
		{
                    setcookie($cookie_langue, $userlang, $timelimit);
		} 

		$referer = $_SERVER['HTTP_REFERER'];
		list($url_ref, $redirect) = split('\?', $referer);

		if (!ereg("User", $redirect))
		{
                    $redirect = base64_encode($redirect);
		} 
		else
		{
                    $redirect = "";
		} 
		
		$url = "index.php?file=User&nuked_nude=index&op=login_message&uid=" . $id_user . "&referer=" . $redirect;
		redirect($url, 0);
            } 
	} 
	else
	{
            echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n"
            . '<script type="text/javascript" src="modules/User/user.js"></script>'
                    . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\">\n"
                    . "<head><title>" . $nuked['name'] . " :: " . $nuked['slogan'] . " ::</title>\n"
                    . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n"
                    . "<meta http-equiv=\"content-style-type\" content=\"text/css\" />\n"
                    . "<link title=\"style\" type=\"text/css\" rel=\"stylesheet\" href=\"themes/" . $theme . "/style.css\" /></head>\n"
                    . "<body style=\"background: " . $bgcolor2 . ";\"><div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /></div>\n"
                    . "<table width=\"400\" style=\"margin-left: auto;margin-right: auto;text-align: center;\" cellspacing=\"1\" cellpadding=\"20\">\n"
					. "<tr><td><div class=\"login_error\" style=\"align=\"center\"><big><b>" . _NOVALIDUSER . "</b></big></div></td></tr></table></body></html>\n";

            redirect("index.php", 2);
	} 

    }
    else
    {
        echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n"
        . '<script type="text/javascript" src="modules/User/user.js"></script>'
                . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\">\n"
                . "<head><title>" . $nuked['name'] . " :: " . $nuked['slogan'] . " ::</title>\n"
                . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n"
                . "<meta http-equiv=\"content-style-type\" content=\"text/css\" />\n"
                . "<link title=\"style\" type=\"text/css\" rel=\"stylesheet\" href=\"themes/" . $theme . "/style.css\" /></head>\n"
                . "<body style=\"background: " . $bgcolor2 . ";\"><div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /></div>\n"
				. "<table width=\"400\" style=\"margin-left: auto;margin-right: auto;text-align: center;\" cellspacing=\"0\" cellpadding=\"20\">\n"
				. "<tr><td><div class=\"login_error\"style=\" align=\"center\"><big><b>" . _UNKNOWNUSER . "</b></big></div></td></tr></table></body></html>\n";

        redirect("index.php", 2);
    }
}

function login_message()
{
    global $nuked, $theme,  $bgcolor1, $bgcolor2, $bgcolor3, $cookie_session, $sessionlimit, $referer, $user_ip, $uid;

    if (isset($_COOKIE[$cookie_session]) && $_COOKIE[$cookie_session] != "")
    {
        $test_cookie = $_COOKIE[$cookie_session];
    }
    else
    {
        $test_cookie = "";
    }

    $referer = base64_decode($referer);

    if ($referer != "")
    {
        $url = "index.php?" . $referer;
    } 
    else
    {
        $url = "index.php";
    } 

    if ($test_cookie != "")
    {
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n"
				. '<script type="text/javascript" src="modules/User/user.js"></script>'
                . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\">\n"
                . "<head><title>" . $nuked['name'] . " :: " . $nuked['slogan'] . " ::</title>\n"
                . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n"
                . "<meta http-equiv=\"content-style-type\" content=\"text/css\" />\n"
                . "<link title=\"style\" type=\"text/css\" rel=\"stylesheet\" href=\"themes/" . $theme . "/style.css\" /></head>\n"
                . "<body style=\"background: " . $bgcolor2 . ";\"><div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /></div>\n"
				. "<table width=\"400\" style=\"margin-left: auto;margin-right: auto;text-align: center;\" cellspacing=\"0\" cellpadding=\"20\">\n"
				. "<tr><td><div class=\"login_succes\" style=\" align=\"center\"><big><b>" . _LOGINPROGRESS . "</b></big><br />\n"
				. "Vous avez <b>" . $user[5] . "</b> nouveau(x) message(s).<br />\n"
				. "Votre ip : <b>" . $user[3] . "</b><br /></div></td></tr></table></body></html>\n";

        redirect($url, 2);
    } 
    else
    {
        if ($nuked['sess_inactivemins'] > 0 && $user_ip != "" && $user_ip != "127.0.0.1")
        {
            $login_text = _LOGINPROGRESS . "<br /><br />" . _SESSIONIPOPEN . "<br /><br />" . _ERRORCOOKIE;
        } 
        else
        {
            $login_text = _ERRORCOOKIE;
        } 

	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n"
	. "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\">\n"
	. "<head><title>" . $nuked['name'] . " :: " . $nuked['slogan'] . " ::</title>\n"
	. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n"
	. "<meta http-equiv=\"content-style-type\" content=\"text/css\" />\n"
	. "<link title=\"style\" type=\"text/css\" rel=\"stylesheet\" href=\"themes/" . $theme . "/style.css\" /></head>\n"
	. "<body style=\"background: " . $bgcolor2 . ";\"><div><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /></div>\n"
	. "<table width=\"80%\" style=\"margin-left: auto;margin-right: auto;text-align: left;background: " . $bgcolor3 . ";\" cellspacing=\"1\" cellpadding=\"20\">\n"
	. "<tr><td style=\"background: " . $bgcolor1 . ";\" align=\"center\"><big><b>" . $login_text . "</b></big></td></tr></table></body></html>";

        redirect($url, 10);
    } 
} 

function update($nick, $pass, $mail, $email, $url, $pass_reg, $pass_conf, $pass_old, $icq, $msn, $aim, $yim, $avatar, $fichiernom, $signature, $game, $country, $remove)
{
    global $nuked, $user;

    if ($remove == "ok" && $nuked['user_delete'] == "on")
    {
	echo "<br /><form action=\"index.php?file=User&amp;op=del_account\" method=\"post\">\n"
	. "<div style=\"text-align: center;\"><big><b>" . _DELMYACCOUNT . "</b></big></div><br />\n"
	. "<table align=\"center\" border=\"0\">\n"
	. "<tr><td align=\"center\">" . _REMOVECONFIRM . "</td></tr>\n"
	. "<tr><td><b>" . _USERPASSWORD . " :</b> <input type=\"password\" name=\"pass\" size=\"10\" maxlength=\"15\" /></td></tr>\n"
	. "<tr><td>&nbsp;</td></tr><tr><td align=\"center\"><input type=\"submit\" value=\"" . _SEND . "\" />&nbsp;"
	."<input type=\"button\" value=\"" . _CANCEL . "\" onclick=\"document.location='index.php?file=User&amp;op=edit_account'\" /></td></tr></table></form><br />\n";
    }
    else
    {
	$nick = htmlentities($nick, ENT_QUOTES);

	$mail = addslashes($mail);
	$mail = htmlentities($mail);

	$sql = mysql_query("SELECT pseudo, mail, pass FROM " . USER_TABLE . " WHERE id = '" . $user[0] . "'");
	list($old_pseudo, $old_mail, $old_pass) = mysql_fetch_array($sql);
	$pass_old_md5 = md5($pass_old);

	if ($nick != $old_pseudo)
	{
            $sql1 = mysql_query("SELECT pseudo FROM " . BANNED_TABLE . " WHERE pseudo = '" . $nick . "'");
            $banned_nick = mysql_num_rows($sql1);

            $sql2 = mysql_query("SELECT pseudo FROM " . USER_TABLE . " WHERE pseudo = '" . $nick . "' AND id != '" . $user[0] . "'");
            $reserved_name = mysql_num_rows($sql2);

            if (!$nick || ($nick == "") || (ereg("[\$\^\(\)'\"?%#<>,;:]", $nick)))
            {
		echo "<br /><br /><div style=\"text-align: center;\">" . _BADUSERNAME . "</div><br /><br />";
		redirect("index.php?file=User&op=edit_account", 2);
		closetable();
		footer();
		exit();
            } 
            else if (strlen($nick) > 30)
            {
		echo "<br /><br /><div style=\"text-align: center;\">" . _NICKTOLONG . "</div><br /><br />";
		redirect("index.php?file=User&op=reg_screen", 2);
		closetable();
		footer();
		exit();
            } 
            else if ($reserved_name > 0)
            {
		echo "<br /><br /><div style=\"text-align: center;\">" . _NICKINUSE . "</div><br /><br />";
		redirect("index.php?file=User&op=edit_account", 2);
		closetable();
		footer();
		exit();
            } 
            else if ($banned_nick > 0)
            {
		echo "<br /><br /><div style=\"text-align: center;\">" . _NICKBANNED . "</div><br /><br />";
		redirect("index.php?file=User&op=edit_account", 2);
		closetable();
		footer();
		exit();
            } 
            else if ($pass_old_md5 != $old_pass || !$pass_old)
            {
		echo "<br /><br /><div style=\"text-align: center;\">" . _BADOLDPASS . "</div><br /><br />";
		redirect("index.php?file=User&op=edit_account", 2);
		closetable();
		footer();
		exit();
            } 
            else
            {
		$upd = mysql_query("UPDATE " . USER_TABLE . " SET pseudo = '" . $nick . "' WHERE id = '" . $user[0] . "'");
            } 
	} 

	if ($mail != $old_mail)
	{
            $sql3 = mysql_query("SELECT mail FROM " . USER_TABLE . " WHERE mail = '" . $mail . "' AND id != '" .$user[0] . "'");
            $reserved_email = mysql_num_rows($sql3);
	
            $sql4 = mysql_query("SELECT email FROM " . BANNED_TABLE . " WHERE email = '" . $mail . "'");
            $banned_email = mysql_num_rows($sql4);

            if ($reserved_email > 0)
            {
            	echo "<br /><br /><div style=\"text-align: center;\">" . _MAILINUSE . "</div><br /><br />";
		redirect("index.php?file=User&op=edit_account", 2);
		closetable();
		footer();
		exit();
            } 

            if ($banned_email > 0)
            {
		echo "<br /><br /><div style=\"text-align: center;\">" . _MAILBANNED . "</div><br /><br />";
		redirect("index.php?file=User&op=edit_account", 2);
		closetable();
		footer();
		exit();
            } 
            else if ($pass_old_md5 != $old_pass || !$pass_old)
            {
		echo "<br /><br /><div style=\"text-align: center;\">" . _BADOLDPASS . "</div><br /><br />";
		redirect("index.php?file=User&op=edit_account", 2);
		closetable();
		footer();
		exit();
            } 
            else
            {
		$upd1 = mysql_query("UPDATE " . USER_TABLE . " SET mail = '" . $mail . "' WHERE id = '" . $user[0] . "'");
            } 
	} 

	if ($pass_reg != "" || $pass_conf != "")
	{
            if ($pass_reg != $pass_conf)
            {
		echo "<br /><br /><div style=\"text-align: center;\">" . stripslashes(_PASSFAILED) . "</div><br /><br />";
		redirect("index.php?file=User&op=edit_account", 2);
		closetable();
		footer();
		exit();
            } 
            else if ($pass_old_md5 != $old_pass || !$pass_old)
            {
		echo "<br /><br /><div style=\"text-align: center;\">" . _BADOLDPASS . "</div><br /><br />";
		redirect("index.php?file=User&op=edit_account", 2);
		closetable();
		footer();
		exit();
            } 
            else
            {
		$cryptpass = md5($pass_reg);
		$upd2 = mysql_query("UPDATE " . USER_TABLE . " SET pass = '" . $cryptpass . "' WHERE id = '" . $user[0] . "'");
            } 
	} 

	$signature = addslashes($signature);
	$email = addslashes($email);
	$icq = addslashes($icq);
	$msn = addslashes($msn);
	$aim = addslashes($aim);
	$yim = addslashes($yim);
	$url = addslashes($url);
	$game = addslashes($game);
	$country = addslashes($country);
	$avatar = addslashes($avatar);

	$signature = htmlentities($signature);
	$email = htmlentities($email);
	$icq = htmlentities($icq);
	$msn = htmlentities($msn);
	$aim = htmlentities($aim);
	$yim = htmlentities($yim);
	$url = htmlentities($url);
	$game = htmlentities($game);
	$country = htmlentities($country);
	$avatar = htmlentities($avatar);

	if ($url != "" && !eregi("http://", $url))
	{
            $url = "http://" . $url;
	} 

	$filename = $_FILES['fichiernom']['name'];
	$filesize = $_FILES['fichiernom']['size'];
	
	if ($filename != "" && $filesize <= 100000)
	{	
            $f = explode(".", $filename);
            $end = count($f) - 1;
            $ext = $f[$end];

            if ($ext == "jpg" || $ext == "jpeg" || $ext == "JPG" || $ext == "JPEG" || $ext == "gif" || $ext == "GIF" || $ext == "png" || $ext == "PNG")
            {
		$url_avatar = "upload/User/" . time() . "." . $ext;
		move_uploaded_file($_FILES['fichiernom']['tmp_name'], $url_avatar) or die ("<br /><br /><div style=\"text-align: center;\"><b>Upload file failed !!!</b></div><br /><br />");
		@chmod ($url_avatar, 0644);
            }
            else
            {
		echo "<br /><br /><div style=\"text-align: center;\">" . _BADFILEFORMAT . "</div><br /><br />";
		redirect("index.php?file=User&op=edit_account", 5);
		closetable();
		footer();
		exit();
            }	

	}
	else if ($filename != "")
	{
		echo "<br /><br /><div style=\"text-align: center;\">" . _FILETOOBIG . "</div><br /><br />";
		redirect("index.php?file=User&op=edit_account", 5);
		closetable();
		footer();
		exit();
	}
	else if ($avatar != "")
	{
            $ext = strrchr($avatar, '.');
            $ext = substr($ext, 1);

            if (!eregi(".php", $avatar) && !eregi(".htm", $avatar) && (eregi("jpg", $ext) || eregi("jpeg", $ext) || eregi("gif", $ext) || eregi("png", $ext)))
            {
				$url_avatar = $avatar;
            }
            else
            {
		echo "<br /><br /><div style=\"text-align: center;\">" . _BADFILEFORMAT . "</div><br /><br />";
		redirect("index.php?file=User&op=edit_account", 5);
		closetable();
		footer();
		exit();
            }
	}
	else
	{
            $url_avatar = "";
	}

	$upd3 = mysql_query("UPDATE " . USER_TABLE . " SET icq = '" . $icq . "', msn = '" . $msn . "', aim = '" . $aim . "', yim = '" . $yim . "', email = '" . $email . "', url = '" . $url . "', avatar = '" . $url_avatar . "', signature = '" . $signature . "', game = '" . $game . "', country = '" . $country . "' WHERE id = '" . $user[0] . "'");
	echo "<br /><br /><div style=\"text-align: center;\">" . _INFOMODIF . "</div><br /><br />";
	redirect("index.php?file=User", 1);
    }
} 


function update_pref($prenom, $jour, $mois, $an, $sexe, $ville, $motherboard, $cpu, $ram, $video, $resolution, $sons, $ecran, $souris, $clavier, $connexion, $osystem, $photo, $fichiernom, $game_id, $pref1, $pref2, $pref3, $pref4, $pref5)
{
    global $nuked, $user;

    $prenom = htmlentities($prenom);
    $ville = htmlentities($ville);
    $motherboard = htmlentities($motherboard);
    $cpu = htmlentities($cpu);
    $ram = htmlentities($ram);
    $video = htmlentities($video);
    $resolution = htmlentities($resolution);
    $sons = htmlentities($sons);
    $ecran = htmlentities($ecran);
    $souris = htmlentities($souris);
    $clavier = htmlentities($clavier);
    $connexion = htmlentities($connexion);
    $osystem = htmlentities($osystem);
    $photo = htmlentities($photo);

    $prenom = addslashes($prenom);
    $ville = addslashes($ville);
    $motherboard = addslashes($motherboard);
    $cpu = addslashes($cpu);
    $ram = addslashes($ram);
    $video = addslashes($video);
    $resolution = addslashes($resolution);
    $sons = addslashes($sons);
    $ecran = addslashes($ecran);
    $souris = addslashes($souris);
    $clavier = addslashes($clavier);
    $connexion = addslashes ($connexion);
    $osystem = addslashes($osystem);
    $photo = addslashes($photo);

    $filename = $_FILES['fichiernom']['name'];
    $filesize = $_FILES['fichiernom']['size'];
	
    if ($filename != "" && $filesize <= 100000)
    {       
        $f = explode(".", $filename);
        $end = count($f) - 1;
        $ext = $f[$end];

	if ($ext == "jpg" || $ext == "jpeg" || $ext == "JPG" || $ext == "JPEG" || $ext == "gif" || $ext == "GIF" || $ext == "png" || $ext == "PNG")
	{
            $url_photo = "upload/User/" . time() . "." . $ext;
            move_uploaded_file($_FILES['fichiernom']['tmp_name'], $url_photo) or die ("<br /><br /><div style=\"text-align: center;\"><b>Upload file failed !!!</b></div><br /><br />");
            @chmod ($url_photo, 0644);
	}
	else
	{
            echo "<br /><br /><div style=\"text-align: center;\">" . _BADFILEFORMAT . "</div><br /><br />";
            redirect("index.php?file=User&op=edit_pref", 5);
            closetable();
            footer();
            exit();
	}	

    }
    else if ($photo != "")
    {
	$ext = strrchr($photo, '.');
	$ext = substr($ext, 1);

	if (!eregi(".php", $photo) && !eregi(".htm", $photo) && (eregi("jpg", $ext) || eregi("jpeg", $ext) || eregi("gif", $ext) || eregi("png", $ext)))
	{
	    $url_photo = $photo;
	}
	else
	{
	    echo "<br /><br /><div style=\"text-align: center;\">" . _BADFILEFORMAT . "</div><br /><br />";
	    redirect("index.php?file=User&op=edit_pref", 5);
	    closetable();
	    footer();
	    exit();
	}
    }
    else
    {
	$url_photo = "";
    }

    if ($an < date("Y"))
    {
        $age = $jour . "/" . $mois . "/" . $an;
    } 
    else
    {
        $age = "";
    } 

    $verif = mysql_query("SELECT user_id FROM " . USER_DETAIL_TABLE . " WHERE user_id = '" . $user[0] . "'");
    $res = mysql_num_rows($verif);

    if ($res > 0)
    {
        $upd = mysql_query("UPDATE " . USER_DETAIL_TABLE . " SET prenom = '" . $prenom . "', age = '" . $age . "', sexe = '" . $sexe . "', ville = '" . $ville . "', motherboard = '" . $motherboard . "', cpu = '" . $cpu . "', ram = '" . $ram . "', video = '" . $video . "', resolution = '" . $resolution . "', son = '" . $sons . "', ecran = '" . $ecran . "', souris = '" . $souris . "', clavier = '" . $clavier . "', connexion = '" . $connexion . "', system = '" . $osystem . "', photo = '" . $url_photo . "' WHERE user_id = '" . $user[0] . "'");
    } 
    else
    {
        $sql = mysql_query("INSERT INTO " . USER_DETAIL_TABLE . " ( `user_id` , `prenom` , `age` , `sexe` , `ville` , `photo` , `motherboard` , `cpu` , `ram` , `video` , `resolution` , `son` , `ecran` , `souris` , `clavier` , `connexion` , `system` , `pref_1` , `pref_2` , `pref_3` , `pref_4` , `pref_5` ) VALUES( '" . $user[0] . "' , '" . $prenom . "' , '" . $age . "' , '" . $sexe . "' , '" . $ville . "' , '" . $url_photo . "' , '" . $motherboard . "' , '" . $cpu . "' , '" . $ram . "' , '" . $video . "' , '" . $resolution . "' , '" . $sons . "' , '" . $ecran . "' , '" . $souris . "' , '" . $clavier . "' , '" . $connexion . "' , '" . $osystem . "' , '' , '' , '' , '' , '' )");
    } 

    $sql_game = mysql_query("SELECT game FROM " . USER_TABLE . " WHERE id = '" . $user[0] . "'");
    list($game) = mysql_fetch_array($sql_game);

    if (!$game_id)
    {
        $pref1 = htmlentities($pref1);
        $pref2 = htmlentities($pref2);
        $pref3 = htmlentities($pref3);
        $pref4 = htmlentities($pref4);
        $pref5 = htmlentities($pref5);

        $pref1 = addslashes($pref1);
        $pref2 = addslashes($pref2);
        $pref3 = addslashes($pref3);
        $pref4 = addslashes($pref4);
        $pref5 = addslashes($pref5);

        $upd1 = mysql_query("UPDATE " . USER_DETAIL_TABLE . " SET pref_1 = '" . $pref1 . "', pref_2 = '" . $pref2 . "' , pref_3 = '" . $pref3 . "', pref_4 = '" . $pref4 . "', pref_5 = '" . $pref5 . "' WHERE user_id = '" . $user[0] . "'");
    } 
    else
    {
        if ($game_id[0] != "")
        {
            $pref1[0] = htmlentities($pref1[0]);
            $pref2[0] = htmlentities($pref2[0]);
            $pref3[0] = htmlentities($pref3[0]);
            $pref4[0] = htmlentities($pref4[0]);
            $pref5[0] = htmlentities($pref5[0]);

            $pref1[0] = addslashes($pref1[0]);
            $pref2[0] = addslashes($pref2[0]);
            $pref3[0] = addslashes($pref3[0]);
            $pref4[0] = addslashes($pref4[0]);
            $pref5[0] = addslashes($pref5[0]);

            $verif_game1 = mysql_query("SELECT * FROM " . GAMES_PREFS_TABLE . " WHERE user_id = '" . $user[0] . "' AND game = '" . $game_id[0] . "'");
            $res1 = mysql_num_rows($verif_game1);

            if ($res1 > 0)
            {
                $upd2 = mysql_query("UPDATE " . GAMES_PREFS_TABLE . " SET pref_1 = '" . $pref1[0] . "', pref_2 = '" . $pref2[0] . "', pref_3 = '" . $pref3[0] . "', pref_4 = '" . $pref4[0] . "', pref_5 = '" . $pref5[0] . "' WHERE user_id = '" . $user[0] . "' AND game = '" . $game_id[0] . "'");
            } 
            else
            {
                $sql1 = mysql_query("INSERT INTO " . GAMES_PREFS_TABLE . " ( `id` , `game` , `user_id` , `pref_1` , `pref_2` , `pref_3` , `pref_4` , `pref_5` ) VALUES( '' , '" . $game_id[0] . "' , '" . $user[0] . "' , '" . $pref1[0] . "' , '" . $pref2[0] . "' , '" . $pref3[0] . "' , '" . $pref4[0] . "' , '" . $pref5[0] . "' )");
            } 

            if ($game_id[0] == $game)
            {
                $upd3 = mysql_query("UPDATE " . USER_DETAIL_TABLE . " SET pref_1 = '" . $pref1[0] . "', pref_2 = '" . $pref2[0] . "', pref_3 = '" . $pref3[0]. "', pref_4 = '" . $pref4[0] . "', pref_5 = '" . $pref5[0] . "' WHERE user_id = '" . $user[0] . "'");
            } 
        } 

        if ($game_id[1] != "")
        {
            $pref1[1] = htmlentities($pref1[1]);
            $pref2[1] = htmlentities($pref2[1]);
            $pref3[1] = htmlentities($pref3[1]);
            $pref4[1] = htmlentities($pref4[1]);
            $pref5[1] = htmlentities($pref5[1]);

            $pref1[1] = addslashes($pref1[1]);
            $pref2[1] = addslashes($pref2[1]);
            $pref3[1] = addslashes($pref3[1]);
            $pref4[1] = addslashes($pref4[1]);
            $pref5[1] = addslashes($pref5[1]);

            $verif_game2 = mysql_query("SELECT * FROM " . GAMES_PREFS_TABLE . " WHERE user_id = '" . $user[0] . "' AND game = '" . $game_id[1] . "'");
            $res2 = mysql_num_rows($verif_game2);

            if ($res2 > 0)
            {
                $upd4 = mysql_query("UPDATE " . GAMES_PREFS_TABLE . " SET pref_1 = '" . $pref1[1] . "', pref_2 = '" . $pref2[1] . "', pref_3 = '" . $pref3[1] . "', pref_4 = '" . $pref4[1] . "', pref_5 = '" . $pref5[1] . "' WHERE user_id = '" . $user[0] . "' AND game='" . $game_id[1] . "'");
            } 
            else
            {
                $sql2 = mysql_query("INSERT INTO " . GAMES_PREFS_TABLE . " ( `id` , `game` , `user_id` , `pref_1` , `pref_2` , `pref_3` , `pref_4` , `pref_5` ) VALUES( '' , '" . $game_id[1] . "' , '" . $user[0] . "' , '" . $pref1[1] . "' , '" . $pref2[1] . "' , '" . $pref3[1] . "' , '" . $pref4[1] . "' , '" . $pref5[1] . "' )");
            } 

            if ($game_id[1] == $game)
            {
                $upd5 = mysql_query("UPDATE " . USER_DETAIL_TABLE . " SET pref_1 = '" . $pref1[1] . "', pref_2 = '" . $pref2[1] . "', pref_3 = '" . $pref3[1] . "', pref_4 = '" . $pref4[1] . "', pref_5 = '" . $pref5[1] . "' WHERE user_id = '" . $user[0] . "'");
            } 
        } 

        if ($game_id[2] != "")
        {
            $pref1[2] = htmlentities($pref1[2]);
            $pref2[2] = htmlentities($pref2[2]);
            $pref3[2] = htmlentities($pref3[2]);
            $pref4[2] = htmlentities($pref4[2]);
            $pref5[2] = htmlentities($pref5[2]);

            $pref1[2] = addslashes($pref1[2]);
            $pref2[2] = addslashes($pref2[2]);
            $pref3[2] = addslashes($pref3[2]);
            $pref4[2] = addslashes($pref4[2]);
            $pref5[2] = addslashes($pref5[2]);

            $verif_game3 = mysql_query("SELECT * FROM " . GAMES_PREFS_TABLE . " WHERE user_id = '" . $user[0] . "' AND game = '" . $game_id[2] . "'");
            $res3 = mysql_num_rows($verif_game3);

            if ($res3 > 0)
            {
                $upd6 = mysql_query("UPDATE " . GAMES_PREFS_TABLE . " SET pref_1 = '" . $pref1[2] . "', pref_2 = '" . $pref2[2] . "', pref_3 = '" . $pref3[2] . "', pref_4 = '" . $pref4[2] . "', pref_5 = '" . $pref5[2] . "' WHERE user_id = '" . $user[0] . "' AND game = '" . $game_id[2] . "'");
            } 
            else
            {
                $sql3 = mysql_query("INSERT INTO " . GAMES_PREFS_TABLE . " ( `id` , `game` , `user_id` , `pref_1` , `pref_2` , `pref_3` , `pref_4` , `pref_5` ) VALUES( '' , '" . $game_id[2] . "' , '" . $user[0] . "' , '" . $pref1[2] . "' , '" . $pref2[2] . "' , '" . $pref3[2] . "' , '" . $pref4[2] . "' , '" . $pref5[2] . "' )");
            } 

            if ($game_id[2] == $game)
            {
                $upd7 = mysql_query("UPDATE " . USER_DETAIL_TABLE . " SET pref_1 = '" . $pref1[2] . "', pref_2 = '" . $pref2[2] . "', pref_3 = '" . $pref3[2] . "', pref_4 = '" . $pref4[2] . "', pref_5 = '" . $pref5[2] . "' WHERE user_id = '" . $user[0] . "'");
            } 
        } 
    } 
    echo "<br /><br /><div style=\"text-align: center;\">" . _PREFMODIF . "</div><br /><br />";
    redirect("index.php?file=User", 2);
} 

function logout()
{
    global $nuked, $user, $cookie_theme, $cookie_langue, $cookie_session, $cookie_userid, $cookie_forum;

    //$del = mysql_query("DELETE FROM " . SESSIONS_TABLE . " WHERE user_id = '" . $user[0] . "'");
    $del = mysql_query("UPDATE " . SESSIONS_TABLE . " SET ip = '' WHERE user_id = '" . $user[0] . "'");
    
    setcookie($cookie_session, "");
    setcookie($cookie_userid, "");
    setcookie($cookie_theme, "");
    setcookie($cookie_langue, "");
	setcookie($cookie_forum, "");

    header("location:index.php");
} 

function oubli_pass()
{
    echo "<br /><form action=\"index.php?file=User&amp;op=envoi_pass\" method=\"post\">\n"
    . "<div style=\"text-align: center;\"><big><b>" . _LOSTPASSWORD . "</b></big></div>\n"
    . "<div style=\"width: 70%;margin-left: auto;margin-right: auto;text-align: left;\"><br />" . _LOSTPASSTXT . "<br /><br /></div>\n"
    . "<table style=\"margin-left: auto;margin-right: auto;text-align: left;\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n"
    . "<tr><td><b>" . _MAIL . " :</b></td><td><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"80\" /></td></tr>\n"
    . "<tr><td><b>" . _CODE . " :</b></td><td><input type=\"text\" name=\"code_conf\" size=\"10\" maxlength=\"20\" /></td></tr>\n"
    . "<tr><td colspan=\"2\">&nbsp;</td></tr><tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"" . _SEND . "\" /></td></tr></table></form><br />\n";
} 

function envoi_pass($email, $code_conf)
{
    global $nuked;

    $email = addslashes($email);
    $email = htmlentities($email);

    $sql = mysql_query("SELECT id, pass, pseudo FROM " . USER_TABLE . " WHERE mail = '" . $email . "'");
    $nb_reponse = mysql_num_rows($sql);
    list($id, $pass, $pseudo) = mysql_fetch_array($sql);

    if ($nb_reponse > 0)
    {
        $areyou = substr($pass, 0, 10);

        if (!$code_conf)
        {
            echo "<br /><br /><div style=\"text-align: center;\">" . _MAILSEND . "</div><br /><br />";
            $message = "\r\n" . _CODEIS . " : " . $areyou . "\r\n\r\n\r\n" . $nuked['name'] . "  - " . $nuked['slogan'];
            $from = "From: " . $nuked['name'] . " <" . $nuked['mail'] . ">\r\nReply-To: " . $nuked['mail'];

            $subject = @html_entity_decode($subject);
            $corps = @html_entity_decode($corps);
            $from = @html_entity_decode($from);
            $s_mail = @html_entity_decode($email);

            mail($s_mail, _LOSTPASSWORD, $message, $from);

            redirect("index.php?file=User&op=oubli_pass", 2);
        } 
        else
        {
            if ($code_conf == $areyou)
            {
                echo "<br><center>" . _MAILSEND . "</center><br>";
                $new_pass = makePass();
                $message = _NICK . " : " . $pseudo . "\r\n" . _NEWPASSIS . " : " . $new_pass . "\r\n\r\n\r\n" . $nuked['name'] . " - " . $nuked['slogan'];
                $from = "From: " . $nuked['name'] . " <" . $nuked['mail'] . ">\r\nReply-To: " . $nuked['mail'];

                $subject = @html_entity_decode($subject);
                $corps = @html_entity_decode($corps);
                $from = @html_entity_decode($from);
		$s_mail = @html_entity_decode($email);	

                mail($s_mail, _LOSTPASSWORD, $message, $from);

                $new_pass = md5($new_pass);
                $upd = mysql_query("UPDATE " . USER_TABLE . " SET pass = '" . $new_pass . "' WHERE id = '" . $id . "'");
                redirect("index.php?file=User&op=login_screen", 2);
            } 
            else
            {
                echo "<br /><br /><div style=\"text-align: center;\">" . _BADCODE . "</div><br /><br />";
                redirect("index.php?file=User&op=oubli_pass", 2);
            } 
        } 
    } 
    else
    {
        echo "<br /><br /><div style=\"text-align: center;\">" . _MAILNOEXIST . "</div><br /><br />";
        redirect("index.php?file=User&op=oubli_pass", 2);
    } 
} 

function makePass()
{
    $makepass = "";
    $syllables = "er,in,tia,wol,fe,pre,vet,jo,nes,al,len,son,cha,ir,ler,bo,ok,tio,nar,sim,ple,bla,ten,toe,cho,co,lat,spe,ak,er,po,co,lor,pen,cil,li,ght,wh,at,the,he,ck,is,mam,bo,no,fi,ve,any,way,pol,iti,cs,ra,dio,sou,rce,sea,rch,pa,per,com,bo,sp,eak,st,fi,rst,gr,oup,boy,ea,gle,tr,ail,bi,ble,brb,pri,dee,kay,en,be,se";
    $syllable_array = explode(",", $syllables);
    srand((double)microtime() * 1000000);
    for ($count = 1;$count <= 4;$count++)
    {
        if (rand() % 10 == 1)
        {
            $makepass .= sprintf("%0.0f", (rand() % 50) + 1);
        } 
        else
        {
            $makepass .= sprintf("%s", $syllable_array[rand() % 62]);
        } 
    } 
    return($makepass);
} 

function show_avatar()
{
    global $bgcolor2, $theme;

    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n"
    . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\">\n"
    . "<head><title>" . _AVATARLIST . "</title>\n"
    . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n"
    . "<meta http-equiv=\"content-style-type\" content=\"text/css\" />\n"
    . "<link title=\"style\" type=\"text/css\" rel=\"stylesheet\" href=\"themes/" . $theme . "/style.css\" /></head>\n"
    . "<body style=\"background: " . $bgcolor2 . ";\">\n"
    . "<table width=\"100%\"><tr><td align=\"center\"><b>" . _CLICAVATAR . "</b></td></tr>\n"
    . "<tr><td>&nbsp;</td></tr><tr><td align=\"center\">\n";

    echo "<script type=\"text/javascript\">\n"
    ."<!--\n"
    ."\n"
    ."function go(img) {\n"
    ."opener.document.getElementById('edit_avatar').value=img;\n"
    ."}\n"
    ."\n"
    . "// -->\n"
    . "</script>\n";

    if ($dir = @opendir("modules/Forum/images/avatar/"))
    {
        while (false !== ($f = readdir($dir)))
        {
            if ($f != "." && $f != ".." && $f != "index.html" && $f != "Thumbs.db")
            {
                $avatar = "modules/Forum/images/avatar/" . $f . "";
                echo " <a href=\"#\" onclick=\"javascript:go('" . $avatar . "');\"><img style=\"border: 0;\" src=\"modules/Forum/images/avatar/" . $f . "\" alt=\"\" title=\"" . $f . "\" /></a>";
            } 
        } 
        closedir($dir);
    } 
    echo "</td></tr><tr><td>&nbsp;</td></tr>\n"
    . "<tr><td align=\"center\"><b>[ <a href=\"#\" onclick=\"self.close()\">" . _CLOSEWINDOWS . "</a> ]</b></td></tr>\n"
    . "<tr><td>&nbsp;</td></tr></table></body></html>";
} 

function change_theme()
{
    global $nuked, $cookie_theme;

    $cookietheme = $_COOKIE[$cookie_theme];

    echo "<br /><div style=\"text-align: center;\"><big><b>" . _YOURACCOUNT . "</b></big></div><br />\n"
    . "<div style=\"text-align: center;\"><b><a href=\"index.php?file=User\">" . _INFO . "</a> |"
    . "<a href=\"index.php?file=User&amp;op=edit_account\">" . _PROFIL . "</a> | "
    . "<a href=\"index.php?file=User&amp;op=edit_pref\">" . _PREF . "</a> | "
    . "</b>" . _THEMESELECT . "<b> | "
    . "<a href=\"index.php?file=User&amp;nuked_nude=index&amp;op=logout\">" . _USERLOGOUT . "</a></b></div>\n"
    . "<br /><form method=\"post\" action=\"index.php?file=User&amp;nuked_nude=index&amp;op=modif_theme\">\n"
    . "<table style=\"margin-left: auto;margin-right: auto;text-align: left;\" cellspacing=\"0\" cellpadding=\"2\">\n"
    . "<tr><td>" . _SELECTTHEME . " :</td></tr>\n"
    . "<tr><td align=\"center\"><select name=\"user_theme\">\n";

    if ($cookietheme != "")
    {
        $mod = $cookietheme;
    } 
    else
    {
        $mod = $nuked['theme'];
    } 

    $handle = opendir('themes');
    while (false !== ($f = readdir($handle)))
    {
        if ($f != "." && $f != ".." && $f != "CVS" && $f != "index.html" && !ereg("[.]", $f))
        {
            if ($mod == $f)
            {
                $checked = "selected=\"selected\"";
            } 
            else
            {
                $checked = "";
            } 
            echo "<option value=\"" . $f . "\" " . $checked . ">" . $f . "</option>\n";
        } 
    } 
    closedir($handle);

    echo "</select></td></tr><tr><td>&nbsp;</td></tr><tr><td align=\"center\"><input type=\"submit\" value=\"" . _CHANGETHEME . "\" /></td></tr></table></form><br />\n";
}

function modif_theme()
{
    global $user, $nuked, $cookie_theme, $timelimit;

    $dir = "themes/" . $_POST['user_theme'];

    if (is_dir($dir) && $_POST['user_theme'])
    {
        setcookie($cookie_theme, $_POST['user_theme'], $timelimit);

        if ($user)
        {
            $upd = mysql_query("UPDATE " . USER_TABLE . " SET user_theme = '" . $_POST['user_theme'] . "' WHERE id = '" . $user[0] . "'");
        } 
    } 

    header("Location:index.php");
} 

function modif_langue()
{
    global $user, $nuked, $cookie_langue, $timelimit;

    if ($_POST['user_langue'] != "")
    {
        setcookie($cookie_langue, $_POST['user_langue'], $timelimit);

        if ($user)
        {
            $upd = mysql_query("UPDATE " . USER_TABLE . " SET user_langue = '" . $_POST['user_langue'] . "' WHERE id = '" . $user[0] . "'");
        } 
    } 

    header("Location:index.php");
} 

function validation($id_user)
{
    global $user, $nuked;

    if ($nuked['validation'] == "mail")
    {
        $sql = mysql_query("SELECT niveau FROM " . USER_TABLE . " WHERE id = '" . $id_user . "'");
        list($niveau) = mysql_fetch_array($sql);

        if ($niveau > 0)
        {
            echo "<br /><br /><div style=\"text-align: center;\">" . _ALREADYVALID . "</div><br /><br />";
            redirect("index.php?file=User", 3);
        } 
        else
        {
            $upd = mysql_query("UPDATE " . USER_TABLE . " SET niveau = 1 WHERE id = '" . $id_user . "'");

            echo "<br /><br /><div style=\"text-align: center;\">" . _VALIDUSER . "</div><br /><br />";
            redirect("index.php?file=User&op=login_screen", 3);
        } 
    } 
    else
    {
        echo "<br /><br /><div style=\"text-align: center;\">" . _NOENTRANCE . "</div><br /><br />";
        redirect("index.php?file=User&op=login_screen", 2);
    } 
} 

function del_account($pass)
{
    global $user, $nuked;

    if ($pass != "" && $nuked[user_delete] == "on")
    {
        $sql = mysql_query("SELECT pass FROM " . USER_TABLE . " WHERE id = '" . $user[0] . "'");
        list($dbpass) = mysql_fetch_array($sql);
	
	$pass_md5 = md5($pass);

        if ($pass_md5 == $dbpass)
        {
            $del1 = mysql_query("DELETE FROM " . SESSIONS_TABLE . " WHERE user_id = '" . $user[0] . "'");
            $del = mysql_query("DELETE FROM " . USER_TABLE . " WHERE id = '" . $user[0] . "'");
            echo "<br /><br /><div style=\"text-align: center;\">" . _ACCOUNTDELETE . "</div><br /><br />";
            redirect("index.php", 2);
        } 
        else
        {
            echo "<br /><br /><div style=\"text-align: center;\">" . _BADPASSWORD . "</div><br /><br />";
            redirect("index.php?file=User&op=edit_account", 2);
        } 
    } 
    else
    {
        echo "<br /><br /><div style=\"text-align: center;\">" . stripslashes(_NOPASSWORD) . "</div><br /><br />";
        redirect("index.php?file=User&op=edit_account", 2);
    } 
} 

switch ($op)
{
    case"edit_account":
        opentable();
        edit_account();
        closetable();
        break;

    case"edit_pref":
        opentable();
        edit_pref();
        closetable();
        break;

    case"index":
        index();
        break;

    case"reg_screen":
        opentable();
        reg_screen();
        closetable();
        break;

    case"login_screen":
        login_screen();
        break;

    case"reg":
        opentable();
        reg($pseudo, $mail, $email, $pass_reg, $pass_conf, $game, $country);
        closetable();
        break;

    case"login":
        login($pseudo, $pass, $remember_me);
        break;

    case"login_message":
        login_message();
        break;

    case"update":
        opentable();
        update($nick, $pass, $mail, $email, $url, $pass_reg, $pass_conf, $pass_old, $icq, $msn, $aim, $yim, $avatar, $fichiernom, $signature, $game, $country, $remove);
        closetable();
        break;

    case"update_pref":
        opentable();
        update_pref($prenom, $jour, $mois, $an, $sexe, $ville, $motherboard, $cpu, $ram, $video, $resolution, $sons, $ecran, $souris, $clavier, $connexion, $osystem, $photo, $fichiernom, $game_id, $pref1, $pref2, $pref3, $pref4, $pref5);
        closetable();
        break;

    case"logout":
        logout();
        break;

    case"oubli_pass":
        opentable();
        oubli_pass();
        closetable();
        break;

    case"envoi_pass":
        opentable();
        envoi_pass($email, $code_conf);
        closetable();
        break;

    case"show_avatar":
        show_avatar();
        break;

    case"change_theme":
        opentable();
        change_theme();
        closetable();
        break;

    case"modif_theme":
        modif_theme($_POST);
        break;

    case"modif_langue":
        modif_langue($_POST);
        break;

    case"validation":
        opentable();
        validation($id_user);
        closetable();
        break;

    case"del_account":
        opentable();
        del_account($pass);
        closetable();
        break;

    default:
        index();
        break;
} 

?>