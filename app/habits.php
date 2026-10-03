
<?php

function calculateHabitStreaks(array $completedDates, string $frequency): array
{
    $today = new DateTimeImmutable('today');

    // Remove duplicate dates and sort them.
    $completedDates = array_values(array_unique($completedDates));
    sort($completedDates);

    if (empty($completedDates)) {
        return [
            'current' => 0,
            'longest' => 0
        ];
    }

    // Convert dates into comparable periods.
    $periods = [];

    foreach ($completedDates as $date) {
        $dateObject = new DateTimeImmutable($date);

        if ($frequency === 'weekly') {
            $periods[] = $dateObject->format('o-W');
        } else {
            $periods[] = $dateObject->format('Y-m-d');
        }
    }

    $periods = array_values(array_unique($periods));

    // Calculate longest streak.
    $longest = 1;
    $currentSequence = 1;

    for ($i = 1; $i < count($periods); $i++) {
        $previous = new DateTimeImmutable($periods[$i - 1]);
        $current = new DateTimeImmutable($periods[$i]);

        if ($frequency === 'weekly') {
            $previous = $previous->modify('monday this week');
            $current = $current->modify('monday this week');
        }

        $difference = $previous->diff($current)->days;

        $expectedDifference = $frequency === 'weekly' ? 7 : 1;

        if ($difference === $expectedDifference) {
            $currentSequence++;
        } else {
            $currentSequence = 1;
        }

        $longest = max($longest, $currentSequence);
    }

    // Calculate current streak.
    $currentPeriod = $frequency === 'weekly'
        ? $today->format('o-W')
        : $today->format('Y-m-d');

    $previousPeriod = $frequency === 'weekly'
        ? $today->modify('-1 week')->format('o-W')
        : $today->modify('-1 day')->format('Y-m-d');

    $lastPeriod = end($periods);

    if (
        $lastPeriod !== $currentPeriod &&
        $lastPeriod !== $previousPeriod
    ) {
        $currentStreak = 0;
    } else {
        $currentStreak = 0;

        for ($i = count($periods) - 1; $i >= 0; $i--) {
            if ($currentStreak === 0) {
                $currentStreak = 1;
                continue;
            }

            $previous = new DateTimeImmutable($periods[$i]);
            $next = new DateTimeImmutable($periods[$i + 1]);

            if ($frequency === 'weekly') {
                $previous = $previous->modify('monday this week');
                $next = $next->modify('monday this week');
            }

            $difference = $previous->diff($next)->days;

            $expectedDifference = $frequency === 'weekly' ? 7 : 1;

            if ($difference === $expectedDifference) {
                $currentStreak++;
            } else {
                break;
            }
        }
    }

    return [
        'current' => $currentStreak,
        'longest' => $longest
    ];
}