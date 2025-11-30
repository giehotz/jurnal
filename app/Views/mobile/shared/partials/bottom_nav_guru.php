<?php
$uri = service('uri');
$uri->setSilent(true); // Prevent exception if segment doesn't exist
$currentSegment = $uri->getSegment(2) ?? 'dashboard'; 
?>
<div id="mobile-bottom-nav" class="fixed bottom-0 left-0 right-0 w-full bg-white border-t border-gray-100 px-6 py-2 pb-4 flex justify-between items-end z-50 rounded-t-2xl shadow-[0_-5px_20px_-5px_rgba(0,0,0,0.03)]">
    <!-- Home -->
    <a href="<?= base_url('guru/dashboard') ?>" class="flex flex-col items-center gap-1 w-14 <?= $currentSegment === 'dashboard' ? 'nav-active' : 'nav-inactive hover:text-blue-500' ?>">
        <i class="fas fa-home text-lg"></i>
        <span class="text-[9px] font-medium">Home</span>
    </a>

    <!-- Jurnal -->
    <a href="<?= base_url('guru/jurnal') ?>" class="flex flex-col items-center gap-1 w-14 <?= $currentSegment === 'jurnal' && $uri->getSegment(3) !== 'create' ? 'nav-active' : 'nav-inactive hover:text-blue-500' ?>">
        <i class="fas fa-book text-lg"></i>
        <span class="text-[9px] font-medium">Jurnal</span>
    </a>

    <!-- Add Jurnal (Quick Action) -->
    <div class="relative -top-5">
        <a href="<?= base_url('guru/jurnal/create') ?>"
            class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center shadow-xl shadow-blue-200 text-white hover:scale-105 transition">
            <i class="fas fa-plus text-lg"></i>
        </a>
    </div>

    <!-- Notif -->
    <a href="#" class="flex flex-col items-center gap-1 w-14 nav-inactive hover:text-blue-500">
        <i class="fas fa-bell text-lg"></i>
        <span class="text-[9px] font-medium">Notif</span>
    </a>

    <!-- Profile -->
    <a href="<?= base_url('guru/profile') ?>" class="flex flex-col items-center gap-1 w-14 <?= $currentSegment === 'profile' ? 'nav-active' : 'nav-inactive hover:text-blue-500' ?>">
        <i class="fas fa-user text-lg"></i>
        <span class="text-[9px] font-medium">Profil</span>
    </a>
</div>
