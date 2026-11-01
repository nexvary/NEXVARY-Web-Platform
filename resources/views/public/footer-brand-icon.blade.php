{{-- Inline brand marks; decorative alongside the visible service name. --}}
<svg class="nc-icon nc-brand-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
@switch($brand)
@case('facebook')
<circle cx="12" cy="12" r="11" fill="#1877f2"/><path fill="#fff" d="M13.6 22v-8h2.7l.4-3.1h-3.1V9c0-.9.3-1.5 1.6-1.5h1.7V4.7c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3v2H7.4V14h2.8v8z"/>
@break
@case('youtube')
<rect x="1" y="4" width="22" height="16" rx="5" fill="#ff0033"/><path fill="#fff" d="m10 8 6 4-6 4z"/>
@break
@case('x')
<rect x="1" y="1" width="22" height="22" rx="5" fill="#112235" stroke="#69b7ff"/><path fill="#f3f5f7" d="M17.3 5h2.2l-6.1 7 7.2 7h-5.7l-4.5-4.4L6.5 19H4.3l5.3-6.1L2.8 5h5.8l4.1 4.7L17.3 5Zm-.8 12.5h1.3L7.2 6.5H5.8z"/>
@break
@endswitch
</svg>
