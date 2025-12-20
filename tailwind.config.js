/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  safelist: [
    // Backgrounds
    'bg-red-500', 'bg-orange-500', 'bg-amber-500', 'bg-yellow-500',
    'bg-lime-500', 'bg-green-500', 'bg-emerald-500', 'bg-teal-500',
    'bg-cyan-500', 'bg-sky-500', 'bg-blue-500', 'bg-indigo-500',
    'bg-violet-500', 'bg-purple-500', 'bg-fuchsia-500', 'bg-pink-500',
    'bg-rose-500', 'bg-slate-500', 'bg-gray-500', 'bg-zinc-500',
    'bg-neutral-500', 'bg-stone-500',
    // Borders
    'border-red-600', 'border-orange-600', 'border-amber-600', 'border-yellow-600',
    'border-lime-600', 'border-green-600', 'border-emerald-600', 'border-teal-600',
    'border-cyan-600', 'border-sky-600', 'border-blue-600', 'border-indigo-600',
    'border-violet-600', 'border-purple-600', 'border-fuchsia-600', 'border-pink-600',
    'border-rose-600', 'border-slate-600', 'border-gray-600', 'border-zinc-600',
    'border-neutral-600', 'border-stone-600',
  ],
  theme: {
    extend: {
      colors: {
        'post-it-yellow': '#FFEB3B',
        'post-it-green': '#C8E6C9',
        'post-it-blue': '#BBDEFB',
        'post-it-pink': '#F8BBD0',
        'sticky': '#8B7355',
      }
    },
  },
  plugins: [],
}

