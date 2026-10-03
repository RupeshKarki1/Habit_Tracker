<?php

function calculateHabitStreaks(array $completedDates, string $frequency): array
{
    $today = new DateTimeImmutable('today');

    // Normalize dates and remove duplicates.
    $periods = [];

    foreach (array_unique($completedDates) as $date) {
        $dateObject = new DateTimeImmutable($date);

        if ($frequency === 'weekly') {
            $dateObject = $dateObject->modify('monday this week');
        }

        $periods[] = $dateObject;
    }

    // Sort periods chronologically.
    usort($periods, fn($a, $b) => $a <=> $b);

    if (empty($periods)) {
        return [
            'current' => 0,
            'longest' => 0
        ];
    }

    // Calculate longest streak.
    $longest = 1;
    $sequence = 1;

    for ($i = 1; $i < count($periods); $i++) {
        $difference = $periods[$i - 1]
            ->diff($periods[$i])->days;

        $expectedDifference = $frequency === 'weekly' ? 7 : 1;

        if ($difference === $expectedDifference) {
            $sequence++;
        } else {
            $sequence = 1;
        }

        $longest = max($longest, $sequence);
    }

    // Determine the latest period that can count toward a current streak.
    $currentPeriod = $frequency === 'weekly'
        ? $today->modify('monday this week')
        : $today;

    $previousPeriod = $frequency === 'weekly'
        ? $currentPeriod->modify('-1 week')
        : $currentPeriod->modify('-1 day');

    $lastPeriod = end($periods);

    if ($lastPeriod != $currentPeriod && $lastPeriod != $previousPeriod) {
        return [
            'current' => 0,
            'longest' => $longest
        ];
    }

    // Count consecutive periods backward from the latest completion.
    $currentStreak = 1;

    for ($i = count($periods) - 1; $i > 0; $i--) {
        $difference = $periods[$i - 1]
            ->diff($periods[$i])->days;

        $expectedDifference = $frequency === 'weekly' ? 7 : 1;

        if ($difference === $expectedDifference) {
            $currentStreak++;
        } else {
            break;
        }
    }

    return [
        'current' => $currentStreak,
        'longest' => $longest
    ];
}