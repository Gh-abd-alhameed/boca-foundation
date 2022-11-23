	=== Boca Foundation ===
Contributors:      Ghadeer Abd Alhameed
Tags:              development,fast
Tested up to:      6.0
Requires PHP:      7.4.1
Stable tag:        1.0.1
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html
========= Description =========
<p style="font-size:30px;color:red;"><b>Very important alert</b></p>
This plugin is for developers only.
It can be added to your projects.
It gives you new features like switching language with adding object for response and object for web requests.
 Session management and caching can be managed
 <br>
 <a href="https://github.com/Gh-abd-alhameed/wordpress-foundation.git">Read More</a>

<p style="font-size:18px;">Example Cache use</p>
<pre>
<code>
 global $Cache;
 $posts = $Cache->getItem("posts");
 if (!$posts->isHit()) {
 	echo "not cache";
 	$data = array("id" => 15, "post_name" => "post_title");
 	$posts->set($data)->expiresAfter(3600);
 	$Cache->save($posts);
 } else {
 	var_dump($posts);
 	echo "from cache";
 }
 </code>
</pre>