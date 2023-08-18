# Desa Wisata Template

## Todo?

- Get repository `Download as ZIP`
- Extract and open folder
- Run `composer install` on terminal
- Rename file `env` to `.env`
- Inside file `.env`, set `CI_ENVIRONMENT = development`
- Insde file `.env`, set `app.baseURL = 'http://localhost:8080/'`
- Run `php spark serve` on terminal

Template Route at `\App\Config\Route.php`
Template Controller at `\app\Controller\Home.php`

### Views

- `landing_page.php` : view for Landing Page
- `web\` : view for WebGIS
- `web\layouts` : template layouts for WebGIS
- `profile` : view for Profile Page
- `auth` : view for authentication (login and register)

### Files

- `public/assets` : Template for Frontend file assets (CSS, JS, etc). Check template [here](https://zuramai.github.io/mazer/demo/index.html) (based on Bootstrap 5).
- `public/css` : CSS for Landing Page (`landing-page/`) and WebGIS (`web.css`).
- `public/js` : JS for Landing Page (`landing-page.js`) and WebGIS (`web.js`).
- `public/media` : `icon` for image assets, `photos` for store pictures, `videos` for store videos.

### Content

| Functionality        | Detail                                                    |
| -------------------- | --------------------------------------------------------- |
| Landing Page         | Show Landing Page at start of App                         |
| Authentication Page  | Pages for Login and Register                              |
| Error Page           | Custom pages for errors (403, 404, 500)                   |
| WebGIS Home          | Show WebGIS home with gallery                             |
| WebGIS Object        | Object menu: `List`, `Around You`, `Search By`            |
| WebGIS Object Detail | Object detail with: `Review`, `Gallery`, `Video`          |
| Nearby Section       | Show nearby search and nearby result. Hide object list    |
| Direction Section    | Show table for route steps                                |
| Profile              | Section for Profile: `Manage`, `Update`, `ChangePassword` |
