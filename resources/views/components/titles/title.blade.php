<div class="text-white p-3 mb-3 bg-custom rounded d-flex align-items-center justify-content-between">
    <h1 class="fw-bold fs-4 m-0">{{ $title }}</h1>
    @isset($slot)
        <div class="ms-auto">
            {{ $slot }}
        </div>
    @endisset
</div>
