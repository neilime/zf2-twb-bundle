<?php

//File to process
$sFileToProcess = __DIR__ . DIRECTORY_SEPARATOR . 'index.html';


// Heading tags regex
$sHeadingTagsRegex = '/<(h([1-6])[^>]*)>(.+?)<\/h\2>/si';

function getAnchor($sHtml) {
    return strtolower(preg_replace('/-+/si', '-', trim(preg_replace('/[^a-z0-9-_]/si', '-', strip_tags($sHtml)), ' -')));
}

//Set id to heading tags
file_put_contents($sFileToProcess, preg_replace_callback($sHeadingTagsRegex, function($aMatches) {
            $sAnchor = getAnchor($aMatches[3]);
            $aIdMatch = null;
            if (preg_match('/id=([\'|"])(.+?)\1/si', $aMatches[1], $aIdMatch)) {
                return str_replace($aIdMatch[0], 'id="' . $sAnchor . '"', $aMatches[0]);
            } else {
                return str_replace('<h' . $aMatches[2], '<h' . $aMatches[2] . ' id="' . $sAnchor . '"', $aMatches[0]);
            }
        }, file_get_contents($sFileToProcess)));

if (preg_match_all($sHeadingTagsRegex, file_get_contents($sFileToProcess), $aHeadings)) {
    $sMenu = '<ul class="nav">' . PHP_EOL;
    $iCurrentTag = 1;
    foreach ($aHeadings[1] as $iKey => $sHTag) {
        if ($iCurrentTag < $aHeadings[2][$iKey]) {
            for ($iIterator = $iCurrentTag; $iIterator < $aHeadings[2][$iKey]; $iIterator++) {
                $sMenu .= str_repeat('    ', ($iIterator * 2) - 1) . '<li>' . PHP_EOL . str_repeat('    ', $iIterator * 2) . '<ul class="nav">' . PHP_EOL;
            }
        } elseif ($iCurrentTag > $aHeadings[2][$iKey]) {
            for ($iIterator = $iCurrentTag; $iIterator > $aHeadings[2][$iKey]; $iIterator--) {
                $sMenu .= str_repeat('    ', ($iIterator * 2) - 2) . '</ul>' . PHP_EOL . str_repeat('    ', ($iIterator * 2) - 3) . '</li>' . PHP_EOL;
            }
        }
        $iCurrentTag = $aHeadings[2][$iKey];

        //Define anchor
        $sAnchor = getAnchor($aHeadings[3][$iKey]);

        //Define label
        $sLabel = trim(preg_replace('/\s+/si', ' ', strip_tags($aHeadings[3][$iKey])));

        $sMenu .= str_repeat('    ', ($iCurrentTag * 2) - 1) . '<li><a href="#' . $sAnchor . '" style="font-size:' . (16 - $iCurrentTag) . 'px;' . ($iCurrentTag == 1 ? 'font-weight:bold' : ($iCurrentTag > 4 ? 'font-style:italic;' : '')) . '">' . str_repeat('&nbsp;', ($iCurrentTag - 1) * 2) . $sLabel . '</a></li>' . PHP_EOL;
    }

    if ($iCurrentTag > 1) {
        for ($iIterator = $iCurrentTag; $iIterator > 1; $iIterator--) {
            $sMenu .= str_repeat('    ', ($iIterator * 2) - 2) . '</ul>' . PHP_EOL . str_repeat('    ', ($iIterator * 2) - 3) . '</li>' . PHP_EOL;
        }
    }


    $sMenu .= '</ul>';
    file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'generated-menu.html', $sMenu);
}


