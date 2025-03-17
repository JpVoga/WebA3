<?php
    // Whether or not to cache the files, should be false when website is not live yet.
    const CACHE_ENABLED = false;

    // Result is to be appended at the end of css file paths, so that files will only be cached if cache is
    // enabled. Append it by putting a "?" character and then echoing the cache time.
    function getCacheTime()
    {
        if (CACHE_ENABLED) return "";
        else return (string)time();
    }

    const DATABASE_IP = "127.0.0.1";
    const DATABASE_USER_NAME = "joaoWebA3";
    const DATABASE_PASSWORD = "ecommerce";
    const DATABASE_NAME = "WebA3";
    const DATABSE_PORT = 3306;

    const MAX_SEARCH_RESULTS_IN_PAGE = 10;

    const MIN_CARD_NUMBER_DIGITS = 15;
    const MAX_CARD_NUMBER_DIGITS = 20;

    const HOME_PAGE_RECOMENDATIONS = 20;
    const RANDOM_RECOMENDATION_CHANCE = 0.25; // Chance of random recomendation
?>