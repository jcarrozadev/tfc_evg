<?php
    $today = new DateTime();
    $day_of_week = (int) $today->format('N');

    if ($day_of_week >= 6) {
        $today->modify('next monday');
    }

    $monday = clone $today;
    $day_of_week = (int) $monday->format('N');
    $difference_to_monday = ($day_of_week == 1) ? 0 : (1 - $day_of_week);
    $monday->modify("$difference_to_monday days");
    $monday_formatted = $monday->format('d/m/Y');

    $friday = clone $monday;
    $friday->modify('+4 days');
    $friday_formatted = $friday->format('d/m/Y');

    $weekly_date_range = "$monday_formatted | $friday_formatted";
?>

<div class="text-white p-3 mb-3 bg-custom rounded d-flex align-items-center">
    <h1 class="fw-bold fs-4 m-0 w-100">{{ $title }} - {{ $weekly_date_range }}</h1>
</div>
