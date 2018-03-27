<?php
    session_start();
    header("Content-type: text/css; charset: UTF-8");

    require_once "../../shared/scripts/php/main.php";

    $_SESSION["scheme"] = "gray";
    $scheme = $_SESSION["scheme"] ?? "gray";
    $configs = parseJsonFile("{$scheme}/config.json");

    foreach ($configs as $shclass => $properties) {
        $selector = "[data-shclass~='{$shclass}']";
        if (!isLinkOnlyConfiguration($properties)) {
            // izbegavamo prazne skupove pravila
            echo "{$selector} {";
            echoSectionProperties($properties);
            echo "}" . PHP_EOL;
        }
        if (isset($properties->Link)) {
            foreach ($properties->Link as $pseudo => $linkProperties) {
                echo "{$selector} {$pseudo} {";
                echoSectionProperties($linkProperties);
                echo "}" . PHP_EOL;
            }
        }
    }
?>

.header-top > .logo {
    background-image : url(<?=$scheme?>/images/logo.png);
}

#btn-messages span[data-newmessages="0"] {
    background-image : url(<?=$scheme?>/images/icons/newmessage_no.png);
}

#btn-messages span:not([data-newmessages="0"]) {
    background-image : url(<?=$scheme?>/images/icons/newmessage_yes.png);
}

#btn-profile span {
    background-image : url(<?=$scheme?>/images/icons/profile.png);
}

#btn-back2top span {
    background-image : url(<?=$scheme?>/images/icons/triangle_top.png);
}

#btn-mark-read {
    background-image : url(<?=$scheme?>/images/icons/check.png);
}

#btn-follow span {
    background : url(<?=$scheme?>/images/icons/users.png) no-repeat 5px center;
}

.icon-forum-new {
    background-image : url(<?=$scheme?>/images/icons/newposts_yes.png);
}

.icon-forum-old {
    background-image : url(<?=$scheme?>/images/icons/newposts_no.png);
}

.icon-forum-redirect {
    background-image : url(<?=$scheme?>/images/icons/redirect.png);
}

.icon-post-old {
    background-image : url(<?=$scheme?>/images/icons/post-old.gif);
}

.icon-post-new {
    background-image : url(<?=$scheme?>/images/icons/post-new.gif);
}

.searchbtn.-submitsearch {
    background-image : url(<?=$scheme?>/images/icons/search.png);
}

.searchbtn.-customsearch {
    background-image : url(<?=$scheme?>/images/icons/gear.png);
}

<?php
    function isLinkOnlyConfiguration($section) {
        $array = get_object_vars($section);
        $properties = array_keys($array);
        return (count($properties) === 1 && $properties[0] === "Link");
    }

    function echoSectionProperties($properties) {
        foreach ($properties as $name => $value) {
            if ($name !== "Link" && $name !== "Background") {
                echo "{$name}: {$value};";
            }
        }
        if (isset($properties->Background)) {
            echo getBackgroundCode($properties->Background);
        }
    }

    function getBackgroundCode($background) {
        $style = $background->{"style"};
        $start = $background->{"start-color"};
        $end = $background->{"end-color"} ?? "";

        $code = "background: {$start};";

        if ($style === "radial-gradient") {
            $code .= "background: -moz-radial-gradient(center, ellipse cover, {$start} 0%, {$end} 100%);";
            $code .= "background: -webkit-radial-gradient(center, ellipse cover, {$start} 0%, {$end} 100%);";
            $code .= "background: radial-gradient(ellipse at center, {$start} 0%, {$end} 100%);";
            $code .= "filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#{"
                . $start . "}', endColorstr='#{" . $end . "}', GradientType=1);";
        }

        if ($style === "linear-gradient") {
            $code .= "background: -moz-linear-gradient(top, {$start} 0%, {$end} 100%);";
            $code .= "background: -webkit-linear-gradient(top, {$start} 0%, {$end} 100%);";
            $code .= "background: linear-gradient(to bottom, {$start} 0%, {$end} 100%);";
            $code .= "filter: progid:DXImageTransform.Microsoft.gradient($end='#{"
                . $start . "}', endColorstr='#{" . $end . "}', GradientType=0);";
        }

        return $code;
    }
