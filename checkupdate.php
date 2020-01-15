<<?php
	$ch = curl_init(); 
	$headers = array ('Content-Type: application/json',                         'Accept: application/vnd.github.full+json');
	$git_url = 'https://api.github.com/repos/skyhawkxava/fritzco/releases/latest';
	curl_setopt($ch, CURLOPT_URL, $git_url);
	curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$result = curl_exec($ch);
	$git_last_release = (json_decode($result));
	$git_release_html_url = $git_last_release->{'html_url'};
	$git_release_tag_name = $git_last_release->{'tag_name'};
	$git_release_name = $git_last_release->{'name'};
	$git_release_prerelease = $git_last_release->{'prerelease'};
	$git_release_published_at = $git_last_release->{'published_at'};
	curl_close($ch);// create curl resource 
	$result = null;

	$jsonfile = file_get_contents('VERSION.json');
	$local_current_release = json_decode($jsonfile);
	$local_release_tag_name = $local_current_release->{'tag_name'};
	$local_release_name = $local_current_release->{'name'};
	$local_release_prerelease = $local_current_release->{'prerelease'};
	$local_release_published_at = $local_current_release->{'published_at'};
 
	echo "<br /><br />";
	echo "<table>";
	echo "<tr>";
	echo "<th></th><th>Online-Version:</th>";
	echo "<th>Local Version:</th><th>Online-Version:</th>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>tag name:</td>";
	echo "<td>".$local_release_tag_name."</td>";
	echo "<td>".$git_release_tag_name."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>name:</td>";
	echo "<td>".$local_release_name."</td>";
	echo "<td>".$git_release_name."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>prelease:</td>";
	echo "<td>";
	if ($local_release_prerelease) { echo "yes<br />(unstable)"; } else { echo "no<br />(stable)"; }
	echo "</td>";
	echo "<td>";
	if ($git_release_prerelease) { echo "yes<br />(unstable)"; } else { echo "no<br />(stable)"; }
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>released at:</td>";
	echo "<td>".$local_release_published_at."</td>";
	echo "<td>".$git_release_published_at."</td>";
	echo "</tr>";
	echo "</table>";
	echo "<br /><br />";
		
	if ($local_release_tag_name < $git_release_tag_name) {
		echo "<br /><br />Update available => Download: <a href=\"".$git_release_html_url."\">".$git_release_html_url."</a>";
	} else {
		echo "No update available.";
	}
	
?>
