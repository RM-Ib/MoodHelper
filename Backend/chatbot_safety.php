
<?php

function normalizeText($text) {
    $text = strtolower($text);

    $text = str_replace(['1', '!', '@'], ['i', 'i', 'a'], $text);


    $text = preg_replace('/[^a-z\s]/', '', $text);

    $text = preg_replace('/\s+/', ' ', $text);

    return trim($text);
}

function detectRiskLevel($message)
{
   $text = normalizeText($message);
   $highRisk = [
    'kill myself',
    'suicide',
    'end my life',
    'want to die',
    'wanna die',
    'wish i was dead',
    'better off dead',
    'overdose',
    'take all my pills',
    'nothing to live for',
    'cant go on',
    'kms',
    'harm myself'
    
];
    $mediumRisk = [
        'i feel empty',
        'i feel worthless',
        'no one cares',
        'i hate my life',
        'im tired of everything',
        'i feel lost',
        'i feel broken'
    ];

    foreach ($highRisk as $pattern) {
        if (strpos($text, $pattern) !== false) {
            return "high";
        }
    }

    foreach ($mediumRisk as $pattern) {
        if (strpos($text, $pattern) !== false) {
            return "medium";
        }
    }

    return "low";
}
function getHighRiskResponse(): string
{
    return "Hey… I’m really sorry you’re feeling this way. It sounds like things might be really heavy right now. You don’t have to handle this alone — there are people who genuinely care and want to help. If you can, please consider reaching out to someone you trust or a support service. I’m here to listen too — what’s been on your mind?";
}


function getMediumRiskPrompt(): string
{
    return "The user seems emotionally vulnerable. Be more supportive, gentle, and attentive. Ask thoughtful follow-up questions.";
}