**Panduan Implementasi Langkah demi Langkah:**

## **1. Tentukan Palet Warna Sendiri**
- Analisis mood dan karakter brand
- Pilih warna primer yang bukan biru (coba: ungu, hijau zamrud, merah terakota, atau teal)
- Buat 3-5 warna sekunder yang harmonis
- Siapkan neutral colors dengan undertone yang sesuai (abu-abu hangat atau dingin)
- Tentukan warna untuk states: success, warning, error, info dengan nuansa berbeda dari default

## **2. Gunakan Font Kustom**
- Pilih font judul dengan karakter kuat (contoh: serif modern atau display font)
- Pilih font isi yang readable namun berbeda (sans-serif dengan kepribadian)
- Atur hierarchy typography: h1-h6, body, caption
- Sesuaikan line-height, letter-spacing, dan font-weight untuk setiap level

## **3. Atur Radius Sudut**
- Tentukan filosofi radius: sharp (0-4px), soft (8-12px), atau rounded (16px+)
- Buat sistem konsisten: kecil untuk input, sedang untuk tombol, besar untuk card
- Bisa juga gunakan radius asimetris atau bentuk custom

## **4. Buat Ulang Gaya Komponen**
**Untuk tombol:**
- Buat class `.btn-custom`, `.btn-primary-custom`, dll
- Desain variant: solid, outline, ghost, link
- Tambahkan states: hover, active, disabled
- Include icon positions dan loading states

**Untuk card:**
- Buat class `.card-custom` dengan struktur berbeda
- Variasikan header, body, footer styling
- Eksperimen dengan media areas dan overlay

## **5. Atur Spacing System**
- Tentukan base unit (contoh: 4px atau 8px)
- Buat scale: xs, sm, md, lg, xl, xxl
- Terapkan konsisten di padding, margin, gap
- Gunakan untuk mengatur white space layout

## **6. Batasi Penggunaan Komponen Bawaan**
- Gunakan hanya grid system Bootstrap untuk layout
- Buat navigation, forms, modals, alerts dari nol
- Manfaatkan flexbox/grid untuk komponen custom
- Dokumentasi komponen custom di internal design system

## **7. Tambahkan Icon Set Berbeda**
- Pilih icon library yang estetik berbeda (Lucide, Phosphor, atau custom SVG)
- Tentukan size system untuk icon (sm, md, lg)
- Buat utility classes untuk icon placement
- Sertakan icon di komponen dengan cara yang konsisten

## **8. Konsistensi Visual Elements**
**Shadow:**
- Buat 3-4 level shadow untuk depth berbeda
- Sesuaikan dengan elevation system

**Border:**
- Tentukan ketebalan dan style untuk berbagai use cases
- Buat variasi untuk dividers, outlines, decorative borders

**Background:**
- Siapkan solid colors, gradients, patterns
- Buat utility untuk background variants
- Eksperimen dengan overlay dan blending modes

## **9. Implementasi Terstruktur:**
```
styles/
├── base/
│   ├── _colors.scss    # Custom color palette
│   ├── _typography.scss # Custom fonts
│   └── _spacing.scss   # Custom spacing
├── components/
│   ├── _buttons.scss   # Custom buttons
│   ├── _cards.scss     # Custom cards
│   └── (komponen lain) # Other custom components
├── utilities/
│   ├── _shadows.scss   # Custom shadows
│   └── _borders.scss   # Custom borders
└── main.scss           # Main import file
```

## **10. Testing Checklist:**
- [ ] Warna konsisten di semua komponen
- [ ] Font terbaca di semua device
- [ ] Spacing memberikan visual rhythm yang baik
- [ ] Tombol dan interaksi jelas
- [ ] Icon konsisten dalam style dan size
- [ ] Shadow memberikan depth yang tepat
- [ ] Tidak ada komponen yang terlihat seperti Bootstrap default
- [ ] Responsif di semua breakpoints
- [ ] Accessibility terjaga (kontras warna, focus states)
- [ ] Performance tidak terganggu

## **Tips Tambahan:**
- **Mulai mobile-first** dengan custom design
- **Buat style guide** sebagai referensi tim
- **Gunakan CSS custom properties** untuk themeability
- **Test dengan user** untuk feedback visual
- **Iterate berdasarkan feedback** dan analytics
