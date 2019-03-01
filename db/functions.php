<?php
/**
 * Soubor s funkcemi pro prezentační část
 *
 * @package Bandsite
 */

/**
 * Vykreslí koncerty podle šablony
 *
 * @param int $howmany Počet koncertů k vykleslení (LIMIT)
 * @param int $page Číslo stránky (OFFSET)
 * @param bool $link Má se za koncerty vykleslit link an další koncerty?
 */
function loadGigs($howmany=0,$page=1,$link=true) {

	$query = "SELECT date, time, venue, event, poster, fb, web, tickets FROM tour WHERE date>=CURRENT_DATE AND public ORDER BY date, time ASC";

	$query .=" LIMIT ".(int)$howmany; //vybere následující koncerty
	$query .=" OFFSET ".(int)(($page-1)*$howmany);

	$result = query($query);

	if(mysqli_num_rows($result)>0) {

		// Načtení šablony
		$temp = file_get_contents('templates/gig.html');

		while($gig = mysqli_fetch_assoc($result)) {
			$template = $temp;
			$template = str_replace("%date%",formDate($gig["date"]),$template);
			$template = str_replace("%time%",formTime($gig["time"]),$template);
			$template = str_replace("%venue%",$gig["venue"],$template);
			$template = str_replace("%event%",$gig["event"],$template);
			$posterFile = $gig["poster"];
			$posterCss = "style=\"background-image: url('img/posters/";
			if(file_exists("img/posters/".$posterFile)){
				$posterCss .= $posterFile;
			} else {
				$posterCss .= "0.jpg";
			}
			$posterCss .= "');\"";
			$template = str_replace("%poster%",$posterCss,$template);

			// Kontrola zda-li má koncert odkaz na Facebook
			if(strlen($gig["fb"])>0) {
				$fblink = $gig["fb"];
				$template = str_replace("%fb%","<a href=\"$fblink\" title=\"Facebook event\" target=\"_blank\" class=\"fb\"><i class=\"fa fa-facebook-official\" aria-hidden=\"true\"></i></a>",$template);
			} else {
				$template = str_replace("%fb%","",$template);
			}

			// Kontrola zda-li má koncert odkaz na web
			if(strlen($gig["web"])>0) {
				$weblink = $gig["web"];
				$template = str_replace("%web%","<a href=\"$weblink\" title=\"Event website\" target=\"_blank\" class=\"web\"><i class=\"fa fa-globe\" aria-hidden=\"true\"></i></a>",$template);
			} else {
				$template = str_replace("%web%","",$template);
			}

			// Kontrola zda-li má koncert odkaz na vstupenky
			if(strlen($gig["tickets"])>0) {
				$ticketslink = $gig["tickets"];
				$template = str_replace("%tickets%","<a href=\"$ticketslink\" title=\"Buy tickets\" target=\"_blank\" class=\"web\"><i class=\"fa fa-ticket\" aria-hidden=\"true\"></i></a>",$template);
			} else {
				$template = str_replace("%tickets%","",$template);
			}
			echo $template;
		}

		// Vykreslení linku na další koncerty
		if($link) {
			echo '</div><div class="more"><div class="link"><a href="tour.php"><i class="fa fa-plus-square-o"></i> more shows</a></div>';
		}
	}

	//Jestliže neexistují koncerty, vypíše hlášku
	else {
		echo '<span class="error">No upcoming shows</span>';
	}
}


/**
 * Vrátí počet nadcházejích veřejných koncertů v databázi
 *
 * @return int Počet nadcházejích veřejných koncertů v databázi
 */
function howManyGigs() {
	$sql = "SELECT COUNT(id) FROM tour WHERE date>=CURRENT_DATE AND public";
	if($result = query($sql)) {
		return (int)mysqli_fetch_assoc($result)["COUNT(id)"];
	}
}

/**
 * Podle hodnot v tabulce settings vykreslí přehrávač
 * Možnosti:
 ** bandcamp album
 ** spotify album
 */
function loadMusicPlayer() {
  $template = array(
    'bandcamp' => '<iframe src="https://bandcamp.com/EmbeddedPlayer/album=%id%/size=large/bgcol=333333/linkcol=e32c14/artwork=small/transparent=true/"></iframe>',
    'spotify'  => '<iframe src="https://open.spotify.com/embed?uri=spotify:album:%id%" width=1808 height=816 allowtransparency="true"></iframe>'
  );

  $selectedService = getValue("section_music_service");
  $albumId = getValue($selectedService."_album");

  echo str_replace("%id%",$albumId,$template[$selectedService]);
}

/**
 * Podle hodnot v tabulce settings vloží iframe Youtube
 * Možnosti:
 ** youtube video
 ** youtube playlist
 */
function loadVideoPlayer() {
  $template = '<iframe src="https://www.youtube.com/embed/%settings%" allowfullscreen></iframe>';

  $settings = getValue("youtube_video");
  $settings .= "?rel=0&showinfo=0&autoplay=".getValue("section_latest_autoplay");

  echo str_replace("%settings%",$settings,$template);
}

/**
 * Z tabulky milestones načte data a vykreslí je
 */
function loadMilestones() {
  $query = "SELECT date, value FROM milestones";

	$result = query($query);

	if(mysqli_num_rows($result)>0) {
		while($milestone = mysqli_fetch_assoc($result)) {
			$template = "<strong>%date%</strong> - %value%<br/>";
			$template = str_replace("%date%",$milestone["date"],$template);
			$template = str_replace("%value%",$milestone["value"],$template);
			echo $template;
		}
	}

	else {
		echo "<strong>No</strong> milestones<br/>";
	}

}

/**
 * Z tabulky members načte data a vykreslí je
 */
function loadMembers() {
  $query = "SELECT nickname, fullname, role FROM members WHERE public = 1";

	$result = query($query);

	if(mysqli_num_rows($result)>0) {
		while($member = mysqli_fetch_assoc($result)) {
			$template = "<strong>%name%</strong> - %role%<br/>";
			$template = str_replace("%name%",$member[getValue("section_band_name_mode")],$template);
			$template = str_replace("%role%",$member["role"],$template);
			echo $template;
		}
	}

	else {
		echo "<strong>No</strong> members<br/>";
	}
}

/**
 * Naformátuje datum
 *
 * @param string $date Datum ve formátu Y-m-d
 *
 * @return string Datum ve formátu j.n.Y
 */
function formDate($date) {
  return date_format(date_create_from_format("Y-m-d",$date),"j.n.Y");
}

/**
 * Naformátuje čas
 *
 * @param string $time Čas ve formátu H:i:s
 *
 * @return string Čas ve formátu G:i
 */
function formTime($time) {
  return date_format(date_create_from_format("H:i:s",$time),"G:i");
}
