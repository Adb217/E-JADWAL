<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#3F0B16">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Hanken+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
  tailwind.config = {
    theme: { extend: {
      fontFamily: {
        sans: ['"Hanken Grotesk"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        display: ['Fraunces', 'Georgia', 'serif'],
      },
      colors: {
        maroon: { DEFAULT: '#6B1425', dark: '#3F0B16' },
        gold:   { DEFAULT: '#B8924A', soft: '#EBDDBC' },
        cream:  '#F4EDEB',
        paper:  '#F7F6F4',
        ink:    '#1D1719',
        line:   '#E6E1DE',
      },
      borderColor: { DEFAULT: '#E6E1DE' },
    }},
  }
</script>

<style type="text/tailwindcss">
  @layer base {
    body { @apply bg-paper font-sans text-ink antialiased; }
    :focus-visible { outline: 2px solid #B8924A; outline-offset: 2px; }
    ::selection { background: #EBDDBC; }
  }
  @layer components {
    .inp   { @apply mt-1.5 w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-[15px] placeholder:text-gray-400 focus:border-maroon focus:outline-none focus:ring-4 focus:ring-maroon/10; }
    .lbl   { @apply text-[13px] font-semibold text-ink/80; }
    .btn   { @apply inline-flex items-center justify-center gap-2 rounded-lg bg-maroon px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-maroon-dark active:scale-[.98]; }
    .btn-ghost { @apply inline-flex items-center justify-center gap-2 rounded-lg border border-line bg-white px-5 py-2.5 text-sm font-semibold text-ink transition hover:border-maroon/40 hover:text-maroon; }
    .card  { @apply rounded-xl border border-line bg-white p-5; }
    .card-link { @apply transition hover:border-maroon/30 hover:shadow-[0_10px_28px_-14px_rgba(63,11,22,.35)]; }
    .card-live { @apply border-maroon/40 ring-1 ring-maroon/15; }
    .chip  { @apply inline-flex items-center gap-1.5 rounded-full bg-cream px-2.5 py-1 text-xs font-medium text-maroon; }
  }
</style>
<style>
  [x-cloak]{display:none !important}
  .tnum{font-variant-numeric:tabular-nums}
  .bg-grid{background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);background-size:56px 56px}
</style>