<?php

if (!function_exists('generateSignatureTable')) {
    /**
     * Generate HTML table for signatures with headmaster and teacher information
     * 
     * @param array|null $headmasterData Headmaster data containing at least 'nama' and 'nip'
     * @param array|null $teacherData Teacher data containing at least 'nama' and 'nip'
     * @return string HTML table as string
     */
    function generateSignatureTable($headmasterData = null, $teacherData = null)
    {
        // Start building the HTML table
        $html = '<table style="width: 100%; border-collapse: collapse; margin-top: 40px;">';
        $html .= '<tr>';
        
        // Column 1: Headmaster information
        $html .= '<td style="width: 45%; vertical-align: top;">';
        if ($headmasterData && isset($headmasterData['nama'])) {
            $html .= 'Mengetahui,<br>';
            $html .= 'Kepala Sekolah<br>';
            $html .= '<div style="margin-top: 60px;">';
            $html .= '<strong>' . esc($headmasterData['nama']) . '</strong><br>';
            if (isset($headmasterData['nip'])) {
                $html .= 'NIP: ' . esc($headmasterData['nip']);
            }
            $html .= '</div>';
        }
        $html .= '</td>';
        
        // Column 2: Empty space
        $html .= '<td style="width: 10%;"></td>';
        
        // Column 3: Teacher information
        $html .= '<td style="width: 45%; vertical-align: top;">';
        if ($teacherData && isset($teacherData['nama'])) {
            $html .= 'Guru Mata Pelajaran,<br>';
            $html .= '<div style="margin-top: 60px;">,<br>';
            $html .= '<strong>' . esc($teacherData['nama']) . '</strong><br>';
            if (isset($teacherData['nip'])) {
                $html .= 'NIP: ' . esc($teacherData['nip']);
            }
            $html .= '</div>';
        }
        $html .= '</td>';
        
        $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }
}