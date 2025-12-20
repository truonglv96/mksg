<footer class="bg-white border-t border-gray-200 px-6 py-4">
    <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-600">
        <div class="mb-2 md:mb-0">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Admin Panel') }}. All rights reserved.</p>
        </div>
        <div class="flex items-center space-x-4">
            <a href="#" class="hover:text-primary-600 transition-colors">
                <i class="fas fa-question-circle mr-1"></i>
                Trợ giúp
            </a>
            <a href="#" class="hover:text-primary-600 transition-colors">
                <i class="fas fa-file-alt mr-1"></i>
                Tài liệu
            </a>
            <span class="text-gray-400">|</span>
            <span>Version 1.0.0</span>
        </div>
    </div>
</footer>

