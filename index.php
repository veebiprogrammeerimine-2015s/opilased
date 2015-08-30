<?php

  $dir = "./_data/";
  $scanned_directory = array_diff(scandir($dir), array('..', '.','.DS_Store'));

  $users = array();

  foreach ($scanned_directory as $user_json) {
    $string = file_get_contents("./_data/".$user_json);
    $user = json_decode($string);
    $username = chop($user_json,".json!");
    $user->username = $username;
    $user = addUserImageAndName($user, $user_json);
    array_push($users, $user);
  }

  //var_dump($users);

  function addUserImageAndName($user, $user_json){

    $github_page = file_get_contents('https://github.com/'.$user->username);

    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($github_page);
    $xpath = new DOMXPath($doc);

    // user image
    $nodes = $xpath->query("//img[@class='avatar']");
    $src = $nodes->item(0)->getAttribute('src');
    $user->img_src = $src ;

    // full name
    $nodes = $xpath->query("//span[@class='vcard-fullname']");
    $fullname = $nodes->item(0)->textContent;
    $user->fullname = $fullname ;

    return $user;
  };

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Õpilaste loend</title>
  <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
  <style media="screen">
    /* used css by afeld @ sample course repo https://education.github.com/ */
    body { font-family: Helvetica, sans-serif; margin: 50px; }
    a { color: black; }
    ul { margin: 0; padding: 0; }
    .student { float: left; list-style-type: none; margin: 0 20px 20px 0; }
    .student a { text-decoration: none; }
    .student .data { display: inline-block; vertical-align: top; width: 300px; }
    .avatar { display: inline-block; height: 135px; margin-right: 10px; width: 135px; }
    .github-username { font-family: Helvetica, 'Segoe UI', Arial, freesans, sans-serif; font-weight: bold; }
    .github-username:hover { text-decoration: underline; }
  </style>
</head>

<body>

  <h1><a href="https://github.com/veebiprogrammeerimine-2015s/kursus">Veebiprogrammeerimine sügis 2015</a> - õpilaste loend</h1>
  <p>
    <a href="https://github.com/veebiprogrammeerimine-2015s/opilased">Lisa ennast!</a>
  </p>


  <!-- based on http://git.io/vvroy -->
  <ul>

    <?php foreach ($users as $user): ?>

    <li class="student" data-username="<?=$user->username?>">
      <a href="https://github.com/<?=$user->username?>">
        <img class="avatar" src="<?=$user->img_src?>"/>
        <div class="data">
          <div>
            <span class="github-username">@<?=$user->username?></span>

            <i class="em em-<?=$user->emoji?>"></i>
          </div>
          <div>
            <span class="name"><?=$user->fullname?></span>
          </div>
          <p>
            <?=$user->introduction?>
          </p>
        </div>
      </a>
    </li>

    <?php endforeach; die; ?>

  </ul>
</body>

</html>
