<?php
    /**
     * @Author : Anton Zinovyev
     */

    return [
        'lang'         => 'en',
        'plural'       => function ($number, array $words) {
            if (!is_numeric($number)) {
                return $words[0];
            }

            $number = abs($number);

            return $number === 1 ? $words[0] : $words[1];
        },
        'translations' => [
            'test translation of :var: (variable|variables)' => 'test retranslation of :var: (variable|variables)',
        ],
    ];