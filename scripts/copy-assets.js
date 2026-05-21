const fs = require('fs');
const path = require('path');

// This script copies only the Bootstrap files used by the PHP pages.
const root = path.resolve(__dirname, '..');
const vendorDir = path.join(root, 'public', 'vendor', 'bootstrap');

// Source files come from node_modules after running npm install.
const files = [
  [
    path.join(root, 'node_modules', 'bootstrap', 'dist', 'css', 'bootstrap.min.css'),
    path.join(vendorDir, 'bootstrap.min.css'),
  ],
  [
    path.join(root, 'node_modules', 'bootstrap', 'dist', 'js', 'bootstrap.bundle.min.js'),
    path.join(vendorDir, 'bootstrap.bundle.min.js'),
  ],
];

fs.mkdirSync(vendorDir, { recursive: true });

for (const [source, target] of files) {
  // Give a clear error if somebody runs the build before installing packages.
  if (!fs.existsSync(source)) {
    throw new Error(`No existe ${source}. Ejecuta npm install primero.`);
  }

  // Keep public/vendor updated with the installed Bootstrap version.
  fs.copyFileSync(source, target);
}

console.log('Bootstrap copiado a public/vendor/bootstrap');
