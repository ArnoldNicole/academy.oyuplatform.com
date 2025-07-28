<?php

use App\Models\CompulsoryFee;
use App\Models\FeesAdvance;
use App\Models\FeesClassType;

if (!function_exists('student_compulsory_fee_payments')) {
    function total_student_compulsory_fee_payments($fees_type_id, $student_id): int
    {
        return CompulsoryFee::whereHas('installment_fee', function ($query) use ($fees_type_id) {
            $query->where('fees_type_id', $fees_type_id);
        })->where('student_id', $student_id)
            ->sum('amount');
    }
}

if (!function_exists('balance_student_compulsory_fee_payments')) {
    function balance_student_compulsory_fee_payments(int $fees_type_id, int $student_id, int $expected): int
    {
        $paid = total_student_compulsory_fee_payments(fees_type_id: $fees_type_id, student_id: $student_id);
        return $expected - $paid;
    }
}

if (!function_exists('random_color_code')) {
    function random_color_code(): string
    {
        return '#' . substr(md5(rand()), 0, 6);
    }
}

if (!function_exists('abbreviate_subject')) {
    function abbreviate_subject($subject_name)
    {
        $popular_subjects_with_abreviations = ['Mathematics' => 'Mat', 'Physics' => 'Phy', 'Chemistry' => 'Chem', 'Biology' => 'Bio', 'Computer Science' => 'CS', 'Physical Education' => 'PE', 'History' => 'Hist', 'Geography' => 'Geo', 'Economics' => 'Eco', 'Accounting' => 'Acc', 'Business Studies' => 'BST', 'English' => 'Eng', 'Hindi' => 'Hin', 'Sanskrit' => 'San', 'French' => 'Fre', 'German' => 'Ger', 'Spanish' => 'Spa', 'KiswaHili' => 'Swa', 'Science' => 'Sci', 'Social Studies' => 'Sst', 'Art' => 'Art', 'Music' => 'Mus', 'Dance' => 'Dan', 'Drama' => 'Dra', 'Christian Religious Education' => 'CRE',];
        $keys = array_keys($popular_subjects_with_abreviations);
        foreach ($keys as $key) {
            if (str_contains(strtolower($subject_name), strtolower($key))) {
                return strtoupper($popular_subjects_with_abreviations[$key]);
            }
        }
        return strtoupper($subject_name);
    }
}