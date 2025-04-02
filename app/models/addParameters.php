<?php

function url(string $params): string {
    // Récupérer l'URL actuelle et découpage
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $parsedUrl = parse_url($url);

    // Construire la nouvelle URL sans tenir compte des anciens paramètres
    $newUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];

    // Ajouter les nouveaux paramètres
    $newUrl .= '?' . $params;

    return $newUrl;
}
