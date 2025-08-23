<button {{ $attributes->merge([
  'type' => 'submit',
  'class' => 'inline-flex justify-center items-center rounded-lg bg-gradient-to-r from-indigo-500 to-rose-500 px-4 py-2 text-white transition-all duration-500 ease-linear hover:bg-gradient-to-r hover:from-indigo-600 hover:to-rose-600'
]) }}>
  {{ $slot }}
</button>