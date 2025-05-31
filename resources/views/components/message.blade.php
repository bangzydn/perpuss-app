@if (@Session::has('success'))
<div id="flash-message" class="bg-green-500 border-green-600 p-4 mb-3 rounded-sm shadow-lg text-white font-black ">
    {{ Session::get('success') }}
</div>
<script>
        setTimeout(function () {
            const flash = document.getElementById('flash-message');
            if (flash) {
                flash.style.transition = 'opacity 0.5s ease';
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 500);
            }
        }, 3000); // 3 detik
    </script>    
@endif

@if (@Session::has('error'))
<div class="bg-red-500 border-red-600 p-4 mb-3 rounded-sm shadow-lg text-white font-black">
    {{ Session::get('error') }}
</div>    
@endif