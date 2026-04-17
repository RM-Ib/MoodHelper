<?php

function detectRiskLevel(string $message): string
{
    $text = mb_strtolower($message);
    $text = preg_replace('/[^\w\s]/u', '', $text);
    $text = preg_replace('/\s+/', ' ', $text);

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
        'cant go on'
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


// 🚨 HIGH RISK RESPONSE
function getHighRiskResponse(): string
{
    return "Hey… I’m really glad you said something. You don’t have to carry this alone. If things feel intense right now, please reach out to someone you trust or a local support service — it can really make a difference. If you want, tell me where you are and I’ll help you find support near you.";
}


// 🟡 MEDIUM RISK RESPONSE
function getMediumRiskPrompt(): string
{
    return "The user seems emotionally vulnerable. Be more supportive, gentle, and attentive. Ask thoughtful follow-up questions.";
}