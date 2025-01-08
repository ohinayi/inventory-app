import preset from '../../../../vendor/filament/filament/tailwind.config.preset'
import daisyui from 'daisyui'

export default {
    presets: [preset],
    plugins: [daisyui],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
