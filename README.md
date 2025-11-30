# CodeIgniter 4 Application Starter

## What is CodeIgniter?

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds a composer-installable app starter.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Installation & updates

`composer create-project codeigniter4/appstarter` then `composer update` whenever
there is a new release of the framework.

When updating, check the release notes to see if there are any changes you might need to apply
to your `app` folder. The affected files can be copied or merged from
`vendor/codeigniter4/framework/app`.

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

```
JURNAL
├─ add_profile_fields.php
├─ ADMINLTE_IMPLEMENTATION.md
├─ app
│  ├─ .htaccess
│  ├─ Common.php
│  ├─ Config
│  │  ├─ App.php
│  │  ├─ Autoload.php
│  │  ├─ Boot
│  │  │  ├─ development.php
│  │  │  ├─ production.php
│  │  │  └─ testing.php
│  │  ├─ Cache.php
│  │  ├─ Constants.php
│  │  ├─ ContentSecurityPolicy.php
│  │  ├─ Cookie.php
│  │  ├─ Cors.php
│  │  ├─ CURLRequest.php
│  │  ├─ Database.php
│  │  ├─ DocTypes.php
│  │  ├─ Email.php
│  │  ├─ Encryption.php
│  │  ├─ Events.php
│  │  ├─ Exceptions.php
│  │  ├─ Feature.php
│  │  ├─ Filters.php
│  │  ├─ ForeignCharacters.php
│  │  ├─ Format.php
│  │  ├─ Generators.php
│  │  ├─ Honeypot.php
│  │  ├─ Images.php
│  │  ├─ Kint.php
│  │  ├─ Logger.php
│  │  ├─ Migrations.php
│  │  ├─ Mimes.php
│  │  ├─ Modules.php
│  │  ├─ Optimize.php
│  │  ├─ Pager.php
│  │  ├─ Paths.php
│  │  ├─ Publisher.php
│  │  ├─ Routes.php
│  │  ├─ Routing.php
│  │  ├─ Security.php
│  │  ├─ Services.php
│  │  ├─ Session.php
│  │  ├─ Toolbar.php
│  │  ├─ UserAgents.php
│  │  ├─ Validation.php
│  │  └─ View.php
│  ├─ Controllers
│  │  ├─ Admin
│  │  │  ├─ Dashboard.php
│  │  │  ├─ Export.php
│  │  │  ├─ Kelas.php
│  │  │  ├─ Laporan.php
│  │  │  ├─ Mapel.php
│  │  │  ├─ Monitoring.php
│  │  │  ├─ Settings.php
│  │  │  └─ UserManagement.php
│  │  ├─ Auth.php
│  │  ├─ BaseController.php
│  │  ├─ Guru
│  │  │  ├─ Dashboard.php
│  │  │  ├─ Jurnal.php
│  │  │  ├─ Profile.php
│  │  │  └─ TestHelper.php
│  │  ├─ Home.php
│  │  ├─ JurnalNew.php
│  │  └─ TimeTest.php
│  ├─ create.php
│  ├─ Database
│  │  ├─ Migrations
│  │  │  ├─ 2025-10-20-001_CreateUsersTable.php
│  │  │  ├─ 2025-10-20-002_CreateKelasTable.php
│  │  │  ├─ 2025-10-20-003_CreateMataPelajaranTable.php
│  │  │  ├─ 2025-10-20-004_CreateJurnalTable.php
│  │  │  ├─ 2025-10-20-005_CreateJurnalP5Table.php
│  │  │  ├─ 2025-10-20-006_CreateJurnalAsesmenTable.php
│  │  │  ├─ 2025-10-20-007_CreateJurnalLampiranTable.php
│  │  │  ├─ 2025-10-21-085841_CreateKelasTable.php
│  │  │  ├─ 2025-10-23-006_CreateJurnalNewTable.php
│  │  │  ├─ 2025-10-23-006_CreateJurnalTableFromCSV.php
│  │  │  ├─ 2025-10-23-111155_CreateJurnalNewTable.php
│  │  │  ├─ 2025-10-23-111158_CreateJurnalLampiranTable.php
│  │  │  └─ 2025-10-24-001_AddProfileFieldsToUsersTable.php
│  │  └─ Seeds
│  │     ├─ JurnalAsesmenSeeder.php
│  │     ├─ JurnalLampiranSeeder.php
│  │     ├─ JurnalNewSeeder.php
│  │     ├─ JurnalP5Seeder.php
│  │     ├─ JurnalSeeder.php
│  │     ├─ KelasSeeder.php
│  │     ├─ KepalaSekolahSeeder.php
│  │     ├─ MainSeeder.php
│  │     ├─ MapelSeeder.php
│  │     └─ UserSeeder.php
│  ├─ edit.php
│  ├─ Filters
│  │  ├─ AuthFilter.php
│  │  └─ RoleFilter.php
│  ├─ Helpers
│  │  └─ tanggal_helper.php
│  ├─ index.html
│  ├─ Language
│  │  └─ en
│  │     └─ Validation.php
│  ├─ Libraries
│  ├─ Models
│  │  ├─ Jurnal.php
│  │  ├─ JurnalAsesmenModel.php
│  │  ├─ JurnalDetailModel.php
│  │  ├─ JurnalLampiranModel.php
│  │  ├─ JurnalModel.php
│  │  ├─ JurnalNewModel.php
│  │  ├─ JurnalP5Model.php
│  │  ├─ KelasModel.php
│  │  ├─ MapelModel.php
│  │  ├─ MataPelajaranModel.php
│  │  └─ UserModel.php
│  ├─ ThirdParty
│  └─ Views
│     ├─ admin
│     │  ├─ dashboard
│     │  │  ├─ index.php
│     │  │  └─ new_index.php
│     │  ├─ dashboard.php
│     │  ├─ export
│     │  │  ├─ import.php
│     │  │  └─ index.php
│     │  ├─ jurnal
│     │  ├─ kelas
│     │  │  ├─ create.php
│     │  │  ├─ edit.php
│     │  │  ├─ index.php
│     │  │  ├─ list.php
│     │  │  └─ view.php
│     │  ├─ kelas.php
│     │  ├─ kepala_sekolah
│     │  │  └─ edit.php
│     │  ├─ laporan
│     │  │  ├─ export.php
│     │  │  ├─ guru.php
│     │  │  ├─ index.php
│     │  │  ├─ jurnal.php
│     │  │  ├─ pdf
│     │  │  │  ├─ guru.php
│     │  │  │  ├─ jurnal.php
│     │  │  │  ├─ kelas.php
│     │  │  │  └─ mapel.php
│     │  │  └─ statistik.php
│     │  ├─ layouts
│     │  │  ├─ footer.php
│     │  │  ├─ header.php
│     │  │  ├─ sidebar.php
│     │  │  └─ template.php
│     │  ├─ mapel
│     │  │  ├─ create.php
│     │  │  ├─ edit.php
│     │  │  └─ index.php
│     │  ├─ monitoring
│     │  │  ├─ detail.php
│     │  │  ├─ jurnal.php
│     │  │  ├─ pdf.php
│     │  │  ├─ pdf_list.php
│     │  │  └─ rekap.php
│     │  ├─ profile
│     │  │  └─ edit.php
│     │  ├─ settings
│     │  │  └─ index.php
│     │  └─ users
│     │     ├─ create.php
│     │     ├─ edit.php
│     │     ├─ import.php
│     │     ├─ index.php
│     │     └─ list.php
│     ├─ auth
│     │  └─ login.php
│     ├─ errors
│     │  ├─ cli
│     │  │  ├─ error_404.php
│     │  │  ├─ error_exception.php
│     │  │  └─ production.php
│     │  └─ html
│     │     ├─ debug.css
│     │     ├─ debug.js
│     │     ├─ error_400.php
│     │     ├─ error_404.php
│     │     ├─ error_exception.php
│     │     └─ production.php
│     ├─ guru
│     │  ├─ dashboard.php
│     │  ├─ jurnal
│     │  │  ├─ create.php
│     │  │  ├─ edit.php
│     │  │  ├─ export_pdf.php
│     │  │  ├─ generate_pdf.php
│     │  │  ├─ index.php
│     │  │  ├─ list.php
│     │  │  ├─ pdf.php
│     │  │  ├─ pdf_template.php
│     │  │  └─ view.php
│     │  ├─ profile
│     │  │  ├─ change_password.php
│     │  │  ├─ edit.php
│     │  │  └─ index.php
│     │  └─ test_helper.php
│     ├─ layouts
│     │  └─ main.php
│     ├─ templates
│     │  ├─ footer.php
│     │  └─ header.php
│     └─ welcome_message.php
├─ blueprint.md
├─ builds
├─ check_database.php
├─ check_jurnal_structure.php
├─ check_related_tables.php
├─ composer.json
├─ composer.lock
├─ create_admin_user.php
├─ flow.md
├─ flowadmindashboard.md
├─ implementaiAdminLTE.md
├─ jurnal.csv
├─ jurnalguru (4).sql
├─ jurnalguru.sql
├─ jurnal_new.csv
├─ LICENSE
├─ php.ini
├─ PHP81-CONFIGURATION.md
├─ preload.php
├─ project-jurnalGuru.zip
├─ public
│  ├─ .htaccess
│  ├─ .user.ini
│  ├─ AdminLTE
│  │  ├─ .browserslistrc
│  │  ├─ .bundlewatch.config.json
│  │  ├─ .editorconfig
│  │  ├─ .eslintignore
│  │  ├─ .eslintrc.json
│  │  ├─ .lgtm.yml
│  │  ├─ .npmignore
│  │  ├─ .prettierrc
│  │  ├─ .stylelintignore
│  │  ├─ .stylelintrc.json
│  │  ├─ ACCESSIBILITY-COMPLIANCE.md
│  │  ├─ CHANGELOG.md
│  │  ├─ CODE_OF_CONDUCT.md
│  │  ├─ composer.json
│  │  ├─ eslint.config.js
│  │  ├─ index.html
│  │  ├─ LICENSE
│  │  ├─ package-lock.json
│  │  ├─ package.json
│  │  ├─ README 2.md
│  │  ├─ README.md
│  │  ├─ src
│  │  │  ├─ assets
│  │  │  │  └─ img
│  │  │  │     ├─ AdminLTEFullLogo.png
│  │  │  │     ├─ AdminLTELogo.png
│  │  │  │     ├─ avatar.png
│  │  │  │     ├─ avatar2.png
│  │  │  │     ├─ avatar3.png
│  │  │  │     ├─ avatar4.png
│  │  │  │     ├─ avatar5.png
│  │  │  │     ├─ boxed-bg.jpg
│  │  │  │     ├─ boxed-bg.png
│  │  │  │     ├─ credit
│  │  │  │     │  ├─ american-express.png
│  │  │  │     │  ├─ cirrus.png
│  │  │  │     │  ├─ mastercard.png
│  │  │  │     │  ├─ paypal.png
│  │  │  │     │  ├─ paypal2.png
│  │  │  │     │  └─ visa.png
│  │  │  │     ├─ default-150x150.png
│  │  │  │     ├─ icons.png
│  │  │  │     ├─ photo1.png
│  │  │  │     ├─ photo2.png
│  │  │  │     ├─ photo3.jpg
│  │  │  │     ├─ photo4.jpg
│  │  │  │     ├─ prod-1.jpg
│  │  │  │     ├─ prod-2.jpg
│  │  │  │     ├─ prod-3.jpg
│  │  │  │     ├─ prod-4.jpg
│  │  │  │     ├─ prod-5.jpg
│  │  │  │     ├─ user1-128x128.jpg
│  │  │  │     ├─ user2-160x160.jpg
│  │  │  │     ├─ user3-128x128.jpg
│  │  │  │     ├─ user4-128x128.jpg
│  │  │  │     ├─ user5-128x128.jpg
│  │  │  │     ├─ user6-128x128.jpg
│  │  │  │     ├─ user7-128x128.jpg
│  │  │  │     └─ user8-128x128.jpg
│  │  │  ├─ config
│  │  │  │  ├─ assets.config.mjs
│  │  │  │  ├─ astro.config.mjs
│  │  │  │  ├─ postcss.config.mjs
│  │  │  │  └─ rollup.config.js
│  │  │  ├─ html
│  │  │  │  ├─ .eslintrc.json
│  │  │  │  ├─ .prettierrc.js
│  │  │  │  ├─ .tsconfig.json
│  │  │  │  ├─ components
│  │  │  │  │  ├─ dashboard
│  │  │  │  │  │  ├─ _footer.astro
│  │  │  │  │  │  ├─ _sidenav.astro
│  │  │  │  │  │  └─ _topbar.astro
│  │  │  │  │  ├─ docs
│  │  │  │  │  │  ├─ browser-support.mdx
│  │  │  │  │  │  ├─ color-mode.mdx
│  │  │  │  │  │  ├─ components
│  │  │  │  │  │  │  ├─ main-header.mdx
│  │  │  │  │  │  │  └─ main-sidebar.mdx
│  │  │  │  │  │  ├─ faq.mdx
│  │  │  │  │  │  ├─ how-to-contribute.mdx
│  │  │  │  │  │  ├─ introduction.mdx
│  │  │  │  │  │  └─ license.mdx
│  │  │  │  │  ├─ javascript
│  │  │  │  │  │  └─ treeview.mdx
│  │  │  │  │  ├─ _head.astro
│  │  │  │  │  └─ _scripts.astro
│  │  │  │  ├─ env.d.ts
│  │  │  │  ├─ pages
│  │  │  │  │  ├─ docs
│  │  │  │  │  │  ├─ browser-support.astro
│  │  │  │  │  │  ├─ color-mode.astro
│  │  │  │  │  │  ├─ components
│  │  │  │  │  │  │  ├─ main-header.astro
│  │  │  │  │  │  │  └─ main-sidebar.astro
│  │  │  │  │  │  ├─ faq.astro
│  │  │  │  │  │  ├─ how-to-contribute.astro
│  │  │  │  │  │  ├─ introduction.astro
│  │  │  │  │  │  ├─ javascript
│  │  │  │  │  │  │  └─ treeview.astro
│  │  │  │  │  │  ├─ layout.astro
│  │  │  │  │  │  └─ license.astro
│  │  │  │  │  ├─ examples
│  │  │  │  │  │  ├─ lockscreen.astro
│  │  │  │  │  │  ├─ login-v2.astro
│  │  │  │  │  │  ├─ login.astro
│  │  │  │  │  │  ├─ register-v2.astro
│  │  │  │  │  │  └─ register.astro
│  │  │  │  │  ├─ forms
│  │  │  │  │  │  └─ general.astro
│  │  │  │  │  ├─ generate
│  │  │  │  │  │  └─ theme.astro
│  │  │  │  │  ├─ index.astro
│  │  │  │  │  ├─ index2.astro
│  │  │  │  │  ├─ index3.astro
│  │  │  │  │  ├─ layout
│  │  │  │  │  │  ├─ collapsed-sidebar.astro
│  │  │  │  │  │  ├─ fixed-complete.astro
│  │  │  │  │  │  ├─ fixed-footer.astro
│  │  │  │  │  │  ├─ fixed-header.astro
│  │  │  │  │  │  ├─ fixed-sidebar.astro
│  │  │  │  │  │  ├─ layout-custom-area.astro
│  │  │  │  │  │  ├─ layout-rtl.astro
│  │  │  │  │  │  ├─ logo-switch.astro
│  │  │  │  │  │  ├─ sidebar-mini.astro
│  │  │  │  │  │  └─ unfixed-sidebar.astro
│  │  │  │  │  ├─ tables
│  │  │  │  │  │  └─ simple.astro
│  │  │  │  │  ├─ UI
│  │  │  │  │  │  ├─ general.astro
│  │  │  │  │  │  ├─ icons.astro
│  │  │  │  │  │  └─ timeline.astro
│  │  │  │  │  └─ widgets
│  │  │  │  │     ├─ cards.astro
│  │  │  │  │     ├─ info-box.astro
│  │  │  │  │     └─ small-box.astro
│  │  │  │  └─ public
│  │  │  │     ├─ assets
│  │  │  │     │  └─ img
│  │  │  │     │     ├─ AdminLTEFullLogo.png
│  │  │  │     │     ├─ AdminLTELogo.png
│  │  │  │     │     ├─ avatar.png
│  │  │  │     │     ├─ avatar2.png
│  │  │  │     │     ├─ avatar3.png
│  │  │  │     │     ├─ avatar4.png
│  │  │  │     │     ├─ avatar5.png
│  │  │  │     │     ├─ boxed-bg.jpg
│  │  │  │     │     ├─ boxed-bg.png
│  │  │  │     │     ├─ credit
│  │  │  │     │     │  ├─ american-express.png
│  │  │  │     │     │  ├─ cirrus.png
│  │  │  │     │     │  ├─ mastercard.png
│  │  │  │     │     │  ├─ paypal.png
│  │  │  │     │     │  ├─ paypal2.png
│  │  │  │     │     │  └─ visa.png
│  │  │  │     │     ├─ default-150x150.png
│  │  │  │     │     ├─ icons.png
│  │  │  │     │     ├─ photo1.png
│  │  │  │     │     ├─ photo2.png
│  │  │  │     │     ├─ photo3.jpg
│  │  │  │     │     ├─ photo4.jpg
│  │  │  │     │     ├─ prod-1.jpg
│  │  │  │     │     ├─ prod-2.jpg
│  │  │  │     │     ├─ prod-3.jpg
│  │  │  │     │     ├─ prod-4.jpg
│  │  │  │     │     ├─ prod-5.jpg
│  │  │  │     │     ├─ user1-128x128.jpg
│  │  │  │     │     ├─ user2-160x160.jpg
│  │  │  │     │     ├─ user3-128x128.jpg
│  │  │  │     │     ├─ user4-128x128.jpg
│  │  │  │     │     ├─ user5-128x128.jpg
│  │  │  │     │     ├─ user6-128x128.jpg
│  │  │  │     │     ├─ user7-128x128.jpg
│  │  │  │     │     └─ user8-128x128.jpg
│  │  │  │     ├─ css
│  │  │  │     │  ├─ adminlte.css
│  │  │  │     │  ├─ adminlte.css.map
│  │  │  │     │  ├─ adminlte.min.css
│  │  │  │     │  ├─ adminlte.min.css.map
│  │  │  │     │  ├─ adminlte.rtl.css
│  │  │  │     │  ├─ adminlte.rtl.css.map
│  │  │  │     │  ├─ adminlte.rtl.min.css
│  │  │  │     │  └─ adminlte.rtl.min.css.map
│  │  │  │     └─ js
│  │  │  │        ├─ adminlte.js
│  │  │  │        ├─ adminlte.js.map
│  │  │  │        ├─ adminlte.min.js
│  │  │  │        └─ adminlte.min.js.map
│  │  │  ├─ scss
│  │  │  │  ├─ adminlte.scss
│  │  │  │  ├─ mixins
│  │  │  │  │  ├─ _animations.scss
│  │  │  │  │  └─ _scrollbar.scss
│  │  │  │  ├─ pages
│  │  │  │  │  ├─ _lockscreen.scss
│  │  │  │  │  └─ _login_and_register.scss
│  │  │  │  ├─ parts
│  │  │  │  │  ├─ _components.scss
│  │  │  │  │  ├─ _core.scss
│  │  │  │  │  ├─ _extra-components.scss
│  │  │  │  │  ├─ _miscellaneous.scss
│  │  │  │  │  └─ _pages.scss
│  │  │  │  ├─ _accessibility.scss
│  │  │  │  ├─ _app-content.scss
│  │  │  │  ├─ _app-footer.scss
│  │  │  │  ├─ _app-header.scss
│  │  │  │  ├─ _app-main.scss
│  │  │  │  ├─ _app-sidebar.scss
│  │  │  │  ├─ _app-wrapper.scss
│  │  │  │  ├─ _bootstrap-variables.scss
│  │  │  │  ├─ _callouts.scss
│  │  │  │  ├─ _cards.scss
│  │  │  │  ├─ _compact-mode.scss
│  │  │  │  ├─ _direct-chat.scss
│  │  │  │  ├─ _docs.scss
│  │  │  │  ├─ _dropdown.scss
│  │  │  │  ├─ _info-box.scss
│  │  │  │  ├─ _miscellaneous.scss
│  │  │  │  ├─ _mixins.scss
│  │  │  │  ├─ _progress-bars.scss
│  │  │  │  ├─ _root.scss
│  │  │  │  ├─ _small-box.scss
│  │  │  │  ├─ _table.scss
│  │  │  │  ├─ _timeline.scss
│  │  │  │  ├─ _toasts.scss
│  │  │  │  ├─ _variables-dark.scss
│  │  │  │  └─ _variables.scss
│  │  │  ├─ ts
│  │  │  │  ├─ accessibility.ts
│  │  │  │  ├─ adminlte.ts
│  │  │  │  ├─ card-widget.ts
│  │  │  │  ├─ direct-chat.ts
│  │  │  │  ├─ fullscreen.ts
│  │  │  │  ├─ layout.ts
│  │  │  │  ├─ push-menu.ts
│  │  │  │  ├─ treeview.ts
│  │  │  │  └─ util
│  │  │  │     └─ index.ts
│  │  │  └─ utils
│  │  │     └─ index.js
│  │  └─ tsconfig.json
│  ├─ favicon.ico
│  ├─ faviconq.ico
│  ├─ favicon_io.zip
│  ├─ index.php
│  ├─ robots.txt
│  └─ uploads
│     └─ profile_pictures
│        ├─ 1761271456_424e9cd7ef390bfbaa3a.png
│        ├─ 1761271479_65a8312c01dcc962560c.png
│        ├─ 1761271554_58c01b0b833864b6a2a9.png
│        ├─ 1761271597_e54058a7078e6d523b0c.png
│        ├─ 1761271621_46f00edc62415ac42077.png
│        ├─ 1761271625_a3519413147fe176a3f6.png
│        ├─ 1761271629_f90390eee067c9823537.png
│        ├─ 1761271749_7cea130e598b7c96c93f.png
│        └─ index.html
├─ README.md
├─ show_tables.php
├─ spark
├─ tests
│  ├─ .htaccess
│  ├─ database
│  │  └─ ExampleDatabaseTest.php
│  ├─ index.html
│  ├─ README.md
│  ├─ session
│  │  └─ ExampleSessionTest.php
│  ├─ unit
│  │  └─ HealthTest.php
│  └─ _support
│     ├─ Database
│     │  ├─ Migrations
│     │  │  └─ 2020-02-22-222222_example_migration.php
│     │  └─ Seeds
│     │     └─ ExampleSeeder.php
│     ├─ Libraries
│     │  └─ ConfigReader.php
│     └─ Models
│        └─ ExampleModel.php
├─ test_db_connection.php
└─ writable
   ├─ .htaccess
   └─ index.html

```