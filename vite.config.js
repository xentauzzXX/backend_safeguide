import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    build: {
        outDir: "public/build", // Menghasilkan file build di dalam folder public/build
        emptyOutDir: true, // Bersihkan folder build sebelum proses baru
        manifest: true, // Menghasilkan file manifest.json untuk Laravel
        rollupOptions: {
            input: ["resources/css/app.css", "resources/js/app.js"], // File utama yang di-build
        },
    },
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"], // Masukkan file entry point
            refresh: true, // Otomatis refresh halaman saat ada perubahan file
        }),
    ],
});
