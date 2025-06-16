# PE Media Portal WordPress Theme

A modern WordPress theme with Webpack integration for asset optimization.

## Features

- Modern WordPress theme structure
- Webpack integration for asset optimization
- SCSS support
- JavaScript bundling
- Image optimization
- Responsive design
- Mobile-friendly navigation

## Development Setup

1. Install dependencies:
```bash
npm install
```

2. Development mode (with watch):
```bash
npm run dev
```

3. Production build:
```bash
npm run build
```

## Theme Structure

```
pe-mp-theme/
├── dist/               # Compiled assets
│   ├── css/           # Compiled CSS files
│   ├── js/            # Compiled JavaScript files
│   └── images/        # Optimized images
├── src/               # Source files
│   ├── js/           # JavaScript source files
│   └── scss/         # SCSS source files
├── style.css         # Theme stylesheet
├── functions.php     # Theme functions
├── header.php        # Header template
├── footer.php        # Footer template
├── front-page.php    # Front page template
├── package.json      # NPM dependencies
└── webpack.config.js # Webpack configuration
```

## Development Workflow

1. Make changes to SCSS files in `src/scss/`
2. Make changes to JavaScript files in `src/js/`
3. Run `npm run dev` to watch for changes
4. For production, run `npm run build`

## Requirements

- Node.js (v14 or higher)
- WordPress 5.0 or higher
- PHP 7.4 or higher 