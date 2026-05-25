@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-300 bg-white text-slate-800 focus:border-cyan-500 focus:ring-cyan-500 rounded-xl shadow-sm']) }}>
