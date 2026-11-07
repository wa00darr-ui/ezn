@props(['id', 'title'])
<dialog id="{{ $id }}" aria-labelledby="{{ $id }}-title" {{ $attributes }}>
  <div class="dialog-head">
    <h2 id="{{ $id }}-title">{{ $title }}</h2>
    <button type="button" class="icon-btn" data-close aria-label="إغلاق">×</button>
  </div>
  {{ $slot }}
</dialog>
