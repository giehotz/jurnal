<?php

namespace App\Controllers;

class TestHelper extends BaseController
{
    protected $helpers = ['laporan'];

    public function index()
    {
        // Sample data for demonstration
        $headmasterData = [
            'nama' => 'Drs. Andi Budiman, M.Pd.',
            'nip' => '19650815 199003 1 001'
        ];
        
        $teacherData = [
            'nama' => 'Dra. Siti Rahayu, M.M.',
            'nip' => '19720520 199802 2 002'
        ];
        
        // Using the helper function
        $signatureHtml = generateSignatureTable($headmasterData, $teacherData);
        
        // Pass to view
        return view('test_signature', ['signatureHtml' => $signatureHtml]);
    }
    
    public function withoutHeadmaster()
    {
        // Sample data with only teacher
        $teacherData = [
            'nama' => 'Budi Santoso, S.Pd.',
            'nip' => '19801205 200501 1 003'
        ];
        
        // Using the helper function without headmaster data
        $signatureHtml = generateSignatureTable(null, $teacherData);
        
        // Pass to view
        return view('test_signature', ['signatureHtml' => $signatureHtml]);
    }
}