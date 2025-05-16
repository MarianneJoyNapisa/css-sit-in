<?php
// List of prohibited words (English and Bisaya)
$profanityList = [
    // English profanity
    'fuck', 'shit', 'asshole', 'bitch', 'cunt', 'damn', 'hell', 'dick', 'pussy', 'cock',
    'motherfucker', 'bastard', 'whore', 'slut', 'fag', 'nigger', 'retard', 'crap', 'piss',
    
    // Bisaya profanity
    'piste', 'atay', 'boang', 'iyot', 'amaw', 'pakyu', 'gago', 'bogo', 'tanga', 'buang',
    'yawa', 'kagang', 'ulol', 'kingina', 'pisti', 'tigulang', 'patay', 'bilat', 'oti'
];

function containsProfanity($text, $profanityList) {
    $text = strtolower($text);
    foreach ($profanityList as $word) {
        // Check for exact word matches (with word boundaries)
        if (preg_match("/\b" . preg_quote($word, '/') . "\b/i", $text)) {
            return true;
        }
    }
    return false;
}

// Example usage:
// if (containsProfanity($userInput, $profanityList)) {
//     // Reject the submission
// }
?>