/* =========================================================
   Nurfauzan Hanif — Portfolio · interactions + i18n (EN/ID)
   ========================================================= */
(function () {
  "use strict";

  /* ---------- Language state ---------- */
  let LANG = "en";
  const L = (v) =>
    (v && typeof v === "object" && !Array.isArray(v) && ("en" in v || "id" in v))
      ? (v[LANG] != null ? v[LANG] : v.en)
      : v;

  /* UI strings used from JS (modal + category labels) */
  const UI = {
    en: { tech: "Tech stack", repo: "View repository",
          cat: { web: "Web Development", data: "Data & Analytics", community: "Community" } },
    id: { tech: "Teknologi", repo: "Lihat repositori",
          cat: { web: "Pengembangan Web", data: "Data & Analitik", community: "Komunitas" } }
  };
  const tr = (k) => (UI[LANG] && UI[LANG][k] != null) ? UI[LANG][k] : UI.en[k];
  const catLabel = (c) => (UI[LANG].cat[c] || UI.en.cat[c]);

  /* Indonesian dictionary for static [data-i18n] elements (English lives in the HTML) */
  const ID = {
    "nav.about": "Tentang", "nav.experience": "Pengalaman", "nav.work": "Karya",
    "nav.skills": "Keahlian", "nav.contact": "Kontak", "cta.talk": "Mari ngobrol",

    "hero.eyebrow": "Pekanbaru, Indonesia — terbuka untuk bekerja",
    "hero.roles": 'Software Engineer <span class="sep">·</span> Analis Data',
    "hero.lede": '<span class="tagline">Lifelong learner</span>',
    "cta.viewwork": "Lihat karya pilihan", "cta.cv": "Unduh CV",
    "badge.major": "Teknik Informatika",
    "stat.gpa": "IPK", "stat.projects": "Proyek pilihan",
    "stat.courses": "Mata kuliah (asisten)", "stat.office": "Kantor Wilayah II",

    "sec.about": "Tentang",
    "about.lead": "Lulusan Teknik Informatika dengan naluri membangun — dari menyiapkan basis data dan membangun aplikasi hingga mengubah data menjadi keputusan.",
    "about.p1": "<strong>PHP</strong>, <strong>Python</strong>, dan <strong>JavaScript</strong> termasuk perkakas sehari-hari saya — tetapi saya tidak terpaku pada satu bahasa atau stack, dan siap mempelajari apa pun yang dibutuhkan sebuah masalah. Saya senang membangun secara menyeluruh: API back-end, antarmuka web dan mobile, hingga alur data. Minat saya mencakup pengembangan web dan mobile, kecerdasan buatan, dan analisis data.",
    "about.p2": "Aktif di berbagai organisasi kampus dan masyarakat serta berpengalaman sebagai asisten dosen, saya membawa pendekatan yang kolaboratif dan berkepala dingin ke setiap tim — serta semangat tulus untuk terus belajar.",
    "fact.edu.k": "Pendidikan", "fact.edu.v": "S1 Teknik Informatika", "fact.edu.sub": "Universitas Islam Riau · IPK 3.76",
    "fact.now.k": "Saat ini", "fact.now.v": "Asisten — Regional Strategy &amp; Finance", "fact.now.sub": "PT Bank Rakyat Indonesia, Tbk — Kantor Wilayah II Pekanbaru",
    "fact.loc.k": "Domisili", "fact.loc.sub": "Indonesia · terbuka untuk relokasi",
    "fact.lang.k": "Bahasa", "fact.lang.v": "Indonesia — Mahir", "fact.lang.sub": "Inggris — Dasar",
    "thesis.tag": "Skripsi",
    "thesis.title": "Analisis Sentimen Berbasis Aspek terhadap Diskursus Generasi Emas 2045 di Media Sosial menggunakan Latent Dirichlet Allocation dan Bidirectional Encoder Representations from Transformers",

    "sec.experience": "Pengalaman",
    "exp1.when": "Nov 2025 — Sekarang", "exp1.role": "Asisten — Regional Strategy &amp; Finance",
    "exp1.org": "PT Bank Rakyat Indonesia, Tbk — Kantor Wilayah II Pekanbaru",
    "exp1.desc": "Membangun dasbor pemantauan kinerja untuk kantor wilayah dan kantor cabang menggunakan Power BI. Merancang dan membangun dasbor pemantauan dari nol dengan Laravel, Vue.js, Inertia.js, dan PostgreSQL. Membuat prakiraan pangsa pasar 2026 dan 2027 untuk mendukung perencanaan strategis wilayah.",
    "exp2.when": "Okt 2023 — Jun 2025", "exp2.role": "Asisten Dosen",
    "exp2.desc": "Mendampingi proses belajar untuk sebelas mata kuliah: Basis Data, Pengenalan Pemrograman, Struktur Data, Pemrograman Berbasis Komponen, Kecerdasan Buatan, Dasar Pemrograman Mobile, Desain &amp; Analisis Algoritma, Basis Data Grafik, Pembangunan Aplikasi Seluler Native, Logika Samar, dan Pembelajaran Mesin.",
    "exp3.role": "Ketua", "exp3.org": "Study Club Programming PRIMATECH — Universitas Islam Riau",
    "exp3.desc": "Memimpin study club pemrograman kampus — menyelenggarakan sesi, membimbing anggota, dan mengoordinasikan kegiatan.",
    "exp4.role": "Dewan Pengawas Organisasi",
    "exp4.desc": "Mengawasi tata kelola organisasi dan mendukung akuntabilitas program.",
    "exp5.when": "Mei 2024", "exp5.role": "Ketua Divisi Acara",
    "exp5.desc": "Memimpin divisi acara untuk kegiatan kemah bakti mahasiswa.",

    "sec.work": "Karya Pilihan",
    "work.sub": "Dua belas proyek di bidang pengembangan web, analisis data, dan inisiatif komunitas. Klik kartu mana pun untuk cerita lengkapnya.",
    "filter.all": "Semua", "filter.web": "Aplikasi Web", "filter.data": "Data &amp; Dasbor", "filter.community": "Komunitas",

    "sec.skills": "Keahlian &amp; Teknologi",
    "skill.web.h": "Pengembangan Web",
    "skill.web.p": "Membangun aplikasi web secara menyeluruh dengan PHP dan JavaScript — dari basis data dan API hingga antarmuka responsif — menggunakan framework seperti Laravel, Vue.js, dan React.js.",
    "skill.data.h": "Analisis Data",
    "skill.data.p": "Mengeksplorasi, meramalkan, dan memvisualisasikan data dengan Python — dari dasbor Power BI (DAX, pemodelan data) hingga peramalan deret waktu, web scraping, dan peta interaktif.",
    "skill.mobile.h": "Pengembangan Mobile",
    "skill.mobile.p": "Membuat aplikasi lintas platform Android &amp; iOS dengan React Native — antarmuka responsif dan fungsional dari satu basis kode.",
    "stack.label": "Perkakas &amp; teknologi",
    "soft.lead.h": "Kepemimpinan",
    "soft.lead.p": "Terbiasa memimpin tim di lingkungan akademik dan organisasi — mengarahkan, memotivasi, dan mendukung tim menuju tujuan bersama.",
    "soft.comm.h": "Komunikasi",
    "soft.comm.p": "Menyampaikan informasi secara jelas dan efektif, baik lisan maupun tulisan, kepada berbagai pihak.",
    "soft.team.h": "Kerja Sama Tim",
    "soft.team.p": "Bekerja baik lintas latar belakang — mendengarkan, memberi masukan konstruktif, dan menyelesaikan tugas kelompok dengan optimal.",

    "contact.title": 'Mari bangun sesuatu<br /><span class="ital">yang berarti.</span>',
    "contact.lede": "Terbuka untuk peran pengembangan full-stack dan analisis data, proyek freelance, dan kolaborasi. Cara tercepat menghubungi saya adalah lewat email.",
    "c.phone": "Telepon", "c.location": "Lokasi",
    "footer.note": "Dirancang &amp; dibuat dengan cermat · ", "footer.top": "Kembali ke atas"
  };

  /* ---------- Cover glyphs ---------- */
  const GLYPHS = {
    truck: '<path d="M3 6.5h11v9H3z"/><path d="M14 9.5h4l3 3v3h-7z"/><circle cx="7" cy="18" r="1.7"/><circle cx="17.4" cy="18" r="1.7"/>',
    boxes: '<path d="M3 8l9-4 9 4-9 4-9-4z"/><path d="M3 8v8l9 4 9-4V8"/><path d="M12 12v8"/>',
    medical: '<rect x="4" y="4" width="16" height="16" rx="4.5"/><path d="M12 8.2v7.6M8.2 12h7.6"/>',
    code: '<path d="M9 8l-4 4 4 4M15 8l4 4-4 4"/>',
    analytics: '<path d="M4 4.5v15h15.5"/><rect x="7.4" y="12" width="2.3" height="4.6" rx=".4" fill="currentColor" stroke="none" opacity=".26"/><rect x="11.5" y="9.4" width="2.3" height="7.2" rx=".4" fill="currentColor" stroke="none" opacity=".26"/><rect x="15.6" y="6.6" width="2.3" height="10" rx=".4" fill="currentColor" stroke="none" opacity=".26"/><path d="M6.8 11.4l4-2.6 3 1.6 4.2-3.6"/>',
    dashboard: '<circle cx="12" cy="12" r="7.6"/><path d="M12 4.4a7.6 7.6 0 0 1 6.6 3.8L12 12z" fill="currentColor" stroke="none" opacity=".26"/><circle cx="12" cy="12" r="2.7"/>',
    forecast: '<path d="M4 4.5v15h15.5"/><path d="M7 15l3-3 3 1.5 4-5.5"/><path d="M13.5 9.5h3.5V13"/>',
    map: '<path d="M12 20.5c3.8-4 5.6-6.9 5.6-9.7a5.6 5.6 0 1 0-11.2 0c0 2.8 1.8 5.7 5.6 9.7z"/><circle cx="12" cy="10.8" r="2.1"/>',
    scrape: '<path d="M4 5.5h16l-5.5 6.8v5.2l-5 2.5v-7.7z"/>',
    trading: '<path d="M4 4.5v15h15.5"/><path d="M9 7.5v9"/><rect x="7.7" y="9.5" width="2.6" height="4.5" rx=".3" fill="currentColor" stroke="none" opacity=".28"/><path d="M15 7v8.5"/><rect x="13.7" y="10.5" width="2.6" height="4" rx=".3" fill="currentColor" stroke="none" opacity=".28"/>'
  };

  /* ---------- Project data (translatable fields carry {en, id}) ---------- */
  const PROJECTS = [
    {
      id: "bri-web",
      title: { en: "BRI Performance Web Dashboard", id: "Dashboard Kinerja BRI (Web)" },
      cat: ["data", "web"],
      role: { en: "Full-stack Developer", id: "Pengembang Full-stack" },
      context: { en: "BRI Regional Office II Pekanbaru", id: "BRI Kantor Wilayah II Pekanbaru" },
      year: "2025",
      feature: true,
      cover: { glyph: "analytics", num: "05" },
      blurb: {
        en: "A production-grade banking dashboard built from scratch — real-time monitoring of branch performance across Riau & the Riau Islands.",
        id: "Dasbor perbankan siap produksi yang dibangun dari nol — pemantauan kinerja cabang secara real-time di Riau & Kepulauan Riau."
      },
      tech: ["Laravel", "Inertia.js", "Vue.js 3", "PostgreSQL", "Tailwind CSS", "Chart.js"],
      repo: "https://github.com/nurfznhanif/DashboardAnalytic.git",
      note: {
        en: "Live screenshots are withheld to respect the confidentiality of internal banking data.",
        id: "Tangkapan layar aplikasi tidak ditampilkan demi menjaga kerahasiaan data internal perbankan."
      },
      desc: {
        en: [
          "As a full-stack developer at BRI Regional Office II Pekanbaru, I designed and built a web-based performance-monitoring dashboard from scratch using Laravel, Vue.js, Inertia.js, and PostgreSQL. The system provides real-time monitoring of branch performance across Riau and the Riau Islands.",
          "Key features include interactive dashboards for third-party funds (DPK) and loans, trend analysis with daily / monthly / yearly comparison, branch-level performance tracking with key metrics (SML%, NPL%, achievement %), product segmentation, and automated data processing from Excel uploads.",
          "It handles large datasets efficiently through optimized queries and enforces role-based access control across user levels — an end-to-end, production-ready enterprise application, from database design through frontend implementation."
        ],
        id: [
          "Sebagai pengembang full-stack di BRI Kantor Wilayah II Pekanbaru, saya merancang dan membangun dasbor pemantauan kinerja berbasis web dari nol menggunakan Laravel, Vue.js, Inertia.js, dan PostgreSQL. Sistem ini menyediakan pemantauan real-time terhadap kinerja kantor cabang di Riau dan Kepulauan Riau.",
          "Fitur utamanya meliputi dasbor interaktif untuk Dana Pihak Ketiga (DPK) dan Kredit, analisis tren dengan perbandingan harian / bulanan / tahunan, pelacakan kinerja tingkat cabang dengan metrik utama (SML%, NPL%, persentase pencapaian), segmentasi produk, dan pemrosesan data otomatis dari unggahan Excel.",
          "Sistem ini menangani dataset besar secara efisien melalui query yang optimal dan menerapkan kontrol akses berbasis peran — aplikasi enterprise siap produksi, dari perancangan basis data hingga implementasi frontend."
        ]
      }
    },
    {
      id: "logistik",
      title: { en: "Logistics & Cargo Management", id: "Manajemen Logistik & Kargo" },
      cat: ["web"],
      role: { en: "Full-stack Developer", id: "Pengembang Full-stack" },
      context: { en: "Team of 2", id: "Tim 2 orang" },
      year: "2025",
      cover: { glyph: "truck", num: "01" },
      blurb: {
        en: "An end-to-end export–import platform with multi-role access, analytics, payroll, and PDF generation.",
        id: "Platform ekspor–impor menyeluruh dengan akses multi-peran, analitik, penggajian, dan pembuatan PDF."
      },
      tech: ["Laravel 12", "Inertia.js", "Vue.js 3", "MySQL", "Tailwind CSS", "Spatie Permission", "DomPDF", "Ziggy"],
      repo: "https://github.com/tegarraihann/cs-billing.git",
      desc: {
        en: [
          "As a full-stack developer in a two-person team, I built a logistics and cargo management system using Laravel 12, Inertia.js, and Vue.js 3. The platform supports export–import operations with multi-role access — customer and vendor management, sales-order tracking, profit-and-loss calculation, employee payroll, and an analytics dashboard.",
          "The main challenge was designing an efficient database for high transaction volumes and implementing role-based access control. Through intensive discussion and clear task division, we delivered a solution with optimized indexing and comprehensive validation.",
          "The result is an end-to-end logistics platform with PDF generation, real-time notifications, and responsive design — work that deepened my understanding of enterprise-grade development in the logistics sector."
        ],
        id: [
          "Sebagai pengembang full-stack dalam tim dua orang, saya membangun sistem manajemen logistik dan kargo menggunakan Laravel 12, Inertia.js, dan Vue.js 3. Platform ini mendukung operasi ekspor–impor dengan akses multi-peran — manajemen pelanggan dan vendor, pelacakan pesanan penjualan, perhitungan laba rugi, penggajian karyawan, dan dasbor analitik.",
          "Tantangan utamanya adalah merancang basis data yang efisien untuk volume transaksi tinggi serta menerapkan kontrol akses berbasis peran. Melalui diskusi intensif dan pembagian tugas yang jelas, kami menghadirkan solusi dengan pengindeksan yang optimal dan validasi yang komprehensif.",
          "Hasilnya adalah platform logistik end-to-end dengan pembuatan PDF, notifikasi real-time, dan desain responsif — memperdalam pemahaman saya tentang pengembangan tingkat enterprise di sektor logistik."
        ]
      }
    },
    {
      id: "bri-powerbi",
      title: { en: "BRI Performance Dashboard", id: "Dashboard Kinerja BRI" },
      subtitle: "Power BI",
      cat: ["data"],
      role: { en: "Data Analyst", id: "Analis Data" },
      context: { en: "BRI Regional Office II Pekanbaru", id: "BRI Kantor Wilayah II Pekanbaru" },
      year: "2025",
      cover: { glyph: "dashboard", num: "06" },
      blurb: {
        en: "A Power BI command centre visualising funds, loans, and branch-level risk metrics with YoY and budget-plan analysis.",
        id: "Pusat kendali Power BI yang memvisualisasikan dana, kredit, dan metrik risiko tingkat cabang dengan analisis YoY & RKA."
      },
      tech: ["Power BI", "DAX", "Excel", "Data Modeling"],
      repo: null,
      note: {
        en: "Live screenshots are withheld to respect the confidentiality of internal banking data.",
        id: "Tangkapan layar dasbor tidak ditampilkan demi menjaga kerahasiaan data internal perbankan."
      },
      desc: {
        en: [
          "As a data analyst at BRI Regional Office II Pekanbaru, I designed and built a comprehensive performance-monitoring dashboard in Power BI. It visualises key banking metrics — total third-party funds (DPK) and total loans — with year-over-year growth, month-to-date tracking, and budget-plan (RKA) comparison.",
          "Features include interactive date and branch filters, trend charts for funds and loans, segmentation analysis (Savings, Current, CASA, Deposits for funds; Micro, Consumer, Small, Medium, Commercial for loans), and a branch-level performance table showing SML% and NPL% ratios.",
          "The dashboard enables real-time monitoring across 22+ branches in Riau and the Riau Islands, supporting data-driven decisions for regional strategy."
        ],
        id: [
          "Sebagai analis data di BRI Kantor Wilayah II Pekanbaru, saya merancang dan membangun dasbor pemantauan kinerja yang komprehensif di Power BI. Dasbor ini memvisualisasikan metrik perbankan utama — Dana Pihak Ketiga (DPK) dan Kredit — dengan pertumbuhan tahunan (YoY), pelacakan MtD, dan perbandingan Rencana Kerja Anggaran (RKA).",
          "Fiturnya meliputi filter tanggal dan cabang interaktif, grafik tren untuk DPK dan Kredit, analisis segmentasi (Tabungan, Giro, Casa, Deposito untuk DPK; Mikro, Consumer, Small, Medium, Commercial untuk Kredit), serta tabel kinerja tingkat cabang yang menampilkan rasio SML% dan NPL%.",
          "Dasbor ini memungkinkan pemantauan real-time terhadap lebih dari 22 kantor cabang di Riau dan Kepulauan Riau, mendukung pengambilan keputusan berbasis data untuk perencanaan strategis wilayah."
        ]
      }
    },
    {
      id: "forecast",
      title: { en: "Market Share Forecasting", id: "Forecasting Pangsa Pasar" },
      subtitle: { en: "Deposits & Loans", id: "Simpanan & Pinjaman" },
      cat: ["data"],
      role: { en: "Data Analyst", id: "Analis Data" },
      context: { en: "BRI Regional Office II Pekanbaru", id: "BRI Kantor Wilayah II Pekanbaru" },
      year: "2026",
      cover: { glyph: "forecast", num: "07" },
      blurb: {
        en: "A Python forecasting engine projecting deposits & loans to 2027 — comparing four models across industry size and BRI's share.",
        id: "Mesin forecasting berbasis Python yang memproyeksikan simpanan & pinjaman hingga 2027 — membandingkan empat model untuk skala industri dan porsi BRI."
      },
      tech: ["Python", "Pandas", "statsmodels", "SARIMA", "Prophet", "openpyxl"],
      repo: "https://github.com/nurfznhanif/Forcest-Aspirasi2027.git",
      note: {
        en: "Forecast outputs are internal to BRI and kept confidential.",
        id: "Hasil proyeksi bersifat internal BRI dan dijaga kerahasiaannya."
      },
      desc: {
        en: [
          "As a data analyst at BRI Regional Office II Pekanbaru, I built a Python forecasting engine to project deposits (third-party funds) and loans through December 2027 — for both the industry (market size) and BRI's share — at branch, provincial, and combined Riau & Riau Islands levels, supporting data-driven target-setting.",
          "I implemented and compared four forecasting methods — Holt-Winters (triple exponential smoothing), ARIMA/SARIMA, trend-and-seasonal regression, and Prophet — across two scenarios: forecasting directly from history, and forecasting from monthly growth patterns.",
          "The main challenge was keeping results stable on relatively short monthly series prone to unrealistic projections. I applied parameter bounds, damped trends, a tiered fallback mechanism, and automatic validation to reject implausible results. The output is a formatted Excel workbook with charts comparing methods and scenarios."
        ],
        id: [
          "Sebagai analis data di BRI Kantor Wilayah II Pekanbaru, saya membangun mesin peramalan (forecasting engine) berbasis Python untuk memproyeksikan Simpanan (Dana Pihak Ketiga) dan Pinjaman hingga Desember 2027 — baik untuk skala industri (ukuran pasar) maupun porsi BRI — pada level cabang, provinsi, hingga gabungan Riau dan Kepulauan Riau, guna mendukung penyusunan target berbasis data.",
          "Saya mengimplementasikan dan membandingkan empat metode peramalan — Holt-Winters (triple exponential smoothing), ARIMA/SARIMA, regresi tren dan musiman, serta Prophet — dalam dua skenario: peramalan langsung dari data historis dan peramalan berbasis pola pertumbuhan bulanan.",
          "Tantangan utamanya adalah menjaga kestabilan hasil pada deret waktu bulanan yang relatif pendek dan rentan menghasilkan proyeksi tidak wajar. Saya menerapkan pembatasan parameter, tren teredam (damped trend), mekanisme cadangan berjenjang, serta validasi otomatis untuk menolak hasil yang tidak masuk akal. Keluarannya berupa workbook Excel terformat dengan grafik pembanding antarmetode dan skenario."
        ]
      }
    },
    {
      id: "mapping",
      title: { en: "Regional Information Map", id: "Peta Informasi Kabupaten/Kota" },
      subtitle: "BRI RO 2 Pekanbaru",
      cat: ["data", "web"],
      role: { en: "Data Analyst", id: "Analis Data" },
      context: { en: "BRI Regional Office II Pekanbaru", id: "BRI Kantor Wilayah II Pekanbaru" },
      year: "2026",
      cover: { glyph: "map", num: "08" },
      blurb: {
        en: "A serverless interactive map profiling 19 regencies/cities and 22 branches — sourced entirely from official public data.",
        id: "Peta interaktif tanpa server yang memprofilkan 19 kabupaten/kota dan 22 cabang — bersumber sepenuhnya dari data publik resmi."
      },
      tech: ["JavaScript", "Leaflet", "Chart.js", "Tabulator", "Python", "GitHub Actions"],
      repo: "https://github.com/nurfznhanif/Mapping-RSF.git",
      desc: {
        en: [
          "As a data analyst at BRI Regional Office II Pekanbaru, I built an interactive web map dashboard profiling the 19 regencies/cities across Regional Office 2 Pekanbaru (Riau & the Riau Islands), alongside the distribution of 22 branch offices.",
          "The dashboard is static and serverless — deployable for free on GitHub Pages — with the map shaded by GRDP and a strict principle that every figure comes from official sources (BPS, OJK, and BKPM). I built the interface with Leaflet for the map, Chart.js for charts, and Tabulator for tables, plus Excel and PDF export.",
          "On the data side, I created an automated Python pipeline that pulls and processes data from the BPS Web API (population, GRDP, leading sectors), Satu Data BKPM (investment realisation and MSMEs), and the OJK Data Portal (third-party funds per regency/city). The main challenge was consolidating varied official sources into one consistent client-side dataset while keeping the dashboard strictly data-driven. I also set up a GitHub Actions workflow to refresh BPS data monthly."
        ],
        id: [
          "Sebagai analis data di BRI Kantor Wilayah II Pekanbaru, saya mengembangkan dashboard peta interaktif berbasis web yang menyajikan profil 19 kabupaten/kota di wilayah kerja Regional Office 2 Pekanbaru (Riau dan Kepulauan Riau) beserta sebaran 22 kantor cabang.",
          "Dashboard ini bersifat statis dan tanpa server sehingga dapat di-deploy gratis (GitHub Pages), dengan peta yang diwarnai berdasarkan PDRB serta prinsip bahwa seluruh angka bersumber dari data resmi (BPS, OJK, dan BKPM). Saya membangun antarmuka menggunakan Leaflet untuk peta, Chart.js untuk grafik, dan Tabulator untuk tabel, dilengkapi ekspor ke Excel dan PDF.",
          "Di sisi data, saya membuat alur otomatis berbasis Python yang menarik dan memproses data dari BPS Web API (penduduk, PDRB, sektor unggulan), Satu Data BKPM (realisasi investasi dan UMKM), serta Portal Data OJK (Dana Pihak Ketiga per kabupaten/kota). Tantangan utamanya adalah mengonsolidasikan beragam sumber data resmi menjadi satu dataset sisi-klien yang konsisten sekaligus menjaga dashboard tetap berbasis data. Saya juga menyiapkan workflow GitHub Actions agar data BPS diperbarui otomatis setiap bulan."
        ]
      }
    },
    {
      id: "trading",
      title: { en: "Stock Analysis & Trading Decision Support", id: "Analisis Saham & Pendukung Keputusan Trading" },
      cat: ["data", "web"],
      role: { en: "Full-stack Developer", id: "Pengembang Full-stack" },
      context: { en: "Personal project", id: "Proyek pribadi" },
      year: "2026",
      cover: { glyph: "trading", num: "09" },
      blurb: {
        en: "A decision-support system for IDX swing trading — blending technical, sentiment, and fundamental signals, summarised by an LLM.",
        id: "Sistem pendukung keputusan swing trading saham IDX — memadukan sinyal teknikal, sentimen, dan fundamental, dirangkum oleh LLM."
      },
      tech: ["Python", "Streamlit", "FastAPI", "Pandas", "yfinance", "SQLite", "LLM"],
      repo: "https://github.com/nurfznhanif/Trade.git",
      note: {
        en: "An analysis aid, not financial advice.",
        id: "Alat bantu analisis, bukan nasihat keuangan."
      },
      desc: {
        en: [
          "As a personal project, I built a decision-support system for swing trading Indonesian stocks (IDX) that blends technical analysis, news sentiment, and fundamental data, with a final buy/hold/sell decision summarised by a large language model (LLM). It is designed as an analysis aid, not financial advice.",
          "I built a data pipeline that pulls stock prices via yfinance and news via Google News RSS, computes sentiment scores, and generates technical signals (moving averages, RSI, ATR) combined with fundamental gates and macro indicators. To validate strategies, I built a point-in-time backtest engine (anti-lookahead bias) with trailing stops and realistic transaction costs, a paper-trading simulation, and a trade journal tracking P&L and risk-based position sizing.",
          "Everything is presented through an interactive Streamlit dashboard, with a backend API to support a mobile app. The main challenge was avoiding lookahead bias in backtests and combining heterogeneous signals into a consistent recommendation."
        ],
        id: [
          "Sebagai proyek pribadi, saya mengembangkan sistem pendukung keputusan untuk swing trading saham Indonesia (IDX) yang memadukan analisis teknikal, sentimen berita, dan data fundamental, dengan keputusan akhir beli/tahan/jual yang dirangkum oleh model bahasa besar (LLM). Sistem ini dirancang sebagai alat bantu analisis, bukan nasihat keuangan.",
          "Saya membangun alur data yang mengambil harga saham melalui yfinance dan berita melalui Google News RSS, menghitung skor sentimen, serta menghasilkan sinyal teknikal (moving average, RSI, ATR) yang dikombinasikan dengan pagar fundamental dan indikator makro. Untuk memvalidasi strategi, saya membangun mesin backtest berbasis point-in-time (anti-lookahead bias) lengkap dengan trailing stop dan biaya transaksi realistis, simulasi paper trading, serta jurnal transaksi yang melacak laba/rugi dan ukuran posisi berbasis risiko.",
          "Seluruh hasil disajikan melalui dashboard interaktif berbasis Streamlit, dilengkapi backend API untuk mendukung aplikasi mobile. Tantangan utamanya adalah menghindari bias lookahead pada backtest serta menggabungkan berbagai sinyal heterogen menjadi rekomendasi yang konsisten."
        ]
      }
    },
    {
      id: "scraping",
      title: { en: "Batam Property Price Scraper", id: "Scraping Data Harga Properti Batam" },
      cat: ["data"],
      role: { en: "Data Engineer", id: "Data Engineer" },
      context: { en: "Property market research", id: "Riset pasar properti" },
      year: "2026",
      cover: { glyph: "scrape", num: "10" },
      blurb: {
        en: "A Python scraper aggregating residential listings from six property platforms into one analysis-ready Excel dataset.",
        id: "Scraper Python yang mengagregasi iklan properti residensial dari enam platform menjadi satu dataset Excel siap analisis."
      },
      tech: ["Python", "httpx", "BeautifulSoup", "curl_cffi", "openpyxl"],
      repo: "https://github.com/nurfznhanif/Scrap-HargaRumahBatam.git",
      note: {
        en: "Agent phone numbers are handled as sensitive personal data.",
        id: "Nomor telepon agen diperlakukan sebagai data pribadi yang sensitif."
      },
      desc: {
        en: [
          "A scraping project that aggregates residential property listings in Batam, Riau Islands, from six platforms (rumah123, Brighton, rumahbatam, 99.co, Pinhome, and Lamudi) into a single Excel file for market and price analysis.",
          "I used Python with a different extraction method per platform — from reading Next.js data structures and parsing JSON-LD markup to browser (TLS) impersonation to get past certain sites' protections. The system produces a combined file plus per-site exports, with two sheets: a full listing and a statistical summary (data completeness, price ranges, and duplication indicators). The data spans 20+ columns, including price per square metre, property specs, agent contact, and capture time.",
          "The main challenge was that each site has a different structure and anti-scraping mechanism, so I implemented rate-limit handling, flagging of listings that overlap across platforms, and special treatment of agent phone numbers as sensitive personal data."
        ],
        id: [
          "Proyek scraping yang mengagregasi data iklan properti residensial di Batam, Kepulauan Riau, dari enam platform properti (rumah123, Brighton, rumahbatam, 99.co, Pinhome, dan Lamudi) menjadi satu berkas Excel untuk keperluan analisis pasar dan harga.",
          "Saya menggunakan Python dengan metode ekstraksi berbeda untuk tiap platform — mulai dari membaca struktur data Next.js, mem-parsing markup JSON-LD, hingga teknik impersonasi browser (TLS) untuk menembus proteksi situs tertentu. Sistem ini menghasilkan berkas gabungan sekaligus ekspor per situs, dengan dua sheet berisi daftar lengkap iklan dan ringkasan statistik (kelengkapan data, rentang harga, serta indikasi duplikasi). Data mencakup lebih dari 20 kolom, termasuk harga per meter persegi, spesifikasi properti, kontak agen, dan waktu pengambilan.",
          "Tantangan utamanya adalah setiap situs memiliki struktur dan mekanisme anti-scraping yang berbeda, sehingga saya menerapkan penanganan rate limit, penandaan iklan yang tumpang tindih antarplatform, serta perlakuan khusus terhadap nomor telepon agen sebagai data pribadi yang sensitif."
        ]
      }
    },
    {
      id: "medis",
      title: { en: "Medical Assessment Form System", id: "Sistem Formulir Pengkajian Medis" },
      cat: ["web"],
      role: { en: "Full-stack Developer", id: "Pengembang Full-stack" },
      context: { en: "Obstetric outpatient clinic", id: "Klinik rawat jalan kebidanan" },
      year: "2024",
      cover: { glyph: "medical", num: "02" },
      blurb: {
        en: "A digital nursing-assessment platform with automatic BMI & risk scoring and clinical-standard report printing.",
        id: "Platform pengkajian keperawatan digital dengan hitung BMI & skor risiko otomatis serta cetak laporan sesuai standar klinis."
      },
      tech: ["PHP 8.2", "Yii2", "PostgreSQL", "Bootstrap 5", "JavaScript"],
      repo: "https://github.com/nurfznhanif/Web-MedicalForm.git",
      desc: {
        en: [
          "I developed a nursing-assessment system for an outpatient obstetric clinic using PHP 8.2, the Yii2 framework, and PostgreSQL. It lets nurses register patients, complete digital nursing-assessment forms, automatically compute BMI and risk scores, and print structured assessment reports.",
          "The main challenge was designing a flexible JSON-based database structure to store complex form data, and implementing a fall-risk calculation across multiple medical parameters.",
          "By optimizing queries and adding comprehensive form validation, I delivered an easy-to-use medical platform with auto-save, a responsive Bootstrap 5 design, and a printing system that produces clinically compliant documents."
        ],
        id: [
          "Saya mengembangkan sistem pengkajian keperawatan untuk klinik rawat jalan kebidanan menggunakan PHP 8.2, framework Yii2, dan PostgreSQL. Sistem ini memungkinkan perawat mendaftarkan pasien, mengisi formulir pengkajian keperawatan digital, menghitung BMI dan penilaian risiko secara otomatis, serta mencetak laporan pengkajian yang terstruktur.",
          "Tantangan utamanya adalah merancang struktur basis data berbasis JSON yang fleksibel untuk menyimpan data formulir yang kompleks serta menerapkan perhitungan risiko jatuh dengan berbagai parameter medis.",
          "Dengan mengoptimalkan query dan menerapkan validasi formulir yang komprehensif, saya menghadirkan platform medis yang mudah digunakan, dilengkapi auto-save, desain responsif Bootstrap 5, dan sistem pencetakan dokumen medis sesuai standar klinis."
        ]
      }
    },
    {
      id: "inventory",
      title: { en: "Inventory & Stock Management", id: "Manajemen Inventori & Stok" },
      cat: ["web"],
      role: { en: "Full-stack Developer", id: "Pengembang Full-stack" },
      context: { en: "Warehouse operations", id: "Operasi gudang" },
      year: "2024",
      cover: { glyph: "boxes", num: "03" },
      blurb: {
        en: "Real-time stock tracking with vendor management, purchase orders, low-stock alerts, and analytics reporting.",
        id: "Pelacakan stok real-time dengan manajemen vendor, pesanan pembelian, notifikasi stok menipis, dan pelaporan analitik."
      },
      tech: ["CodeIgniter 4", "MySQL", "Tailwind CSS", "JavaScript", "Font Awesome"],
      repo: "https://github.com/nurfznhanif/Web-Warehouse.git",
      desc: {
        en: [
          "I developed a comprehensive inventory management system using CodeIgniter 4, focused on tracking incoming and outgoing goods, vendor management, and real-time stock reporting. It includes CRUD for product categories, vendor management, purchase-order tracking, and stock-movement monitoring through a responsive interface.",
          "The main challenge was designing an efficient database to track inventory movements accurately while implementing real-time low-stock notifications.",
          "By optimizing queries and adding a caching layer, I delivered a platform that handles high transaction volumes with strong performance — complete with an analytics dashboard and reporting tools for business decisions."
        ],
        id: [
          "Saya mengembangkan sistem manajemen inventori yang komprehensif menggunakan CodeIgniter 4, dengan fokus pada pelacakan barang masuk dan keluar, manajemen vendor, dan pelaporan stok secara real-time. Sistem ini mencakup CRUD untuk kategori produk, manajemen vendor, pelacakan pesanan pembelian, dan pemantauan pergerakan stok melalui antarmuka yang responsif.",
          "Tantangan utamanya adalah merancang basis data yang efisien untuk melacak pergerakan inventori secara akurat sekaligus menerapkan notifikasi real-time untuk stok yang menipis.",
          "Dengan mengoptimalkan query dan menerapkan mekanisme caching, saya menghadirkan platform yang mampu menangani volume transaksi tinggi dengan performa kuat — dilengkapi dasbor analitik dan alat pelaporan untuk keputusan bisnis."
        ]
      }
    },
    {
      id: "geopark",
      title: { en: "Geopark Sawahlunto", id: "Geopark Sawahlunto" },
      cat: ["web"],
      role: { en: "Backend Developer", id: "Pengembang Backend" },
      context: { en: "Team of 2", id: "Tim 2 orang" },
      year: "2024",
      images: ["Assets/projects/geopark-1.jpg", "Assets/projects/geopark-2.jpg"],
      photo: true,
      blurb: {
        en: "Backend & API for a tourism website — admin authentication, dynamic CRUD content, and a destination directory.",
        id: "Backend & API untuk situs pariwisata — autentikasi admin, konten dinamis CRUD, dan direktori destinasi wisata."
      },
      tech: ["Laravel", "MySQL", "REST API", "Blade"],
      repo: null,
      desc: {
        en: [
          "As the backend developer in a two-person team, I built the MySQL database, developed the API, and supported the Geopark Sawahlunto website using Laravel. I implemented admin authentication, CRUD features for dynamic content, and a range of API endpoints to serve the frontend.",
          "Our main challenge was differing views on API structure and development priorities — through consistent communication and technical compromise, we delivered on time. The result was a website with a content-management system and a tourist-destination directory.",
          "The experience taught me the value of effective collaboration in a small team, and how to balance flexibility with solid technical principles."
        ],
        id: [
          "Sebagai pengembang backend dalam tim dua orang, saya membangun basis data MySQL, mengembangkan API, dan mendukung website Geopark Sawahlunto menggunakan Laravel. Saya mengimplementasikan autentikasi admin, fitur CRUD untuk konten dinamis, dan berbagai endpoint API untuk mendukung frontend.",
          "Tantangan utama kami adalah perbedaan pendapat mengenai struktur API dan prioritas pengembangan — melalui komunikasi yang konsisten dan kompromi teknis, kami menyelesaikan proyek tepat waktu. Hasilnya adalah website dengan sistem manajemen konten dan daftar destinasi wisata.",
          "Pengalaman ini mengajarkan pentingnya kolaborasi yang efektif dalam tim kecil, serta cara menyeimbangkan fleksibilitas dengan prinsip teknis yang solid."
        ]
      }
    },
    {
      id: "pemira",
      title: { en: "Student Election System", id: "Sistem Pemira Mahasiswa" },
      subtitle: { en: "Faculty of Law, UNRI", id: "Fakultas Hukum, UNRI" },
      cat: ["web", "community"],
      role: { en: "Full-stack Developer", id: "Pengembang Full-stack" },
      context: { en: "Universitas Riau", id: "Universitas Riau" },
      year: "2024",
      images: ["Assets/projects/pemira-1.jpg", "Assets/projects/pemira-2.jpg"],
      photo: true,
      blurb: {
        en: "A secure online voting system with token-based ballots and real-time results held until polls close.",
        id: "Sistem pemungutan suara online yang aman dengan token dan hasil real-time yang ditahan hingga pemilihan selesai."
      },
      tech: ["CodeIgniter", "MySQL", "PHP", "Chart.js"],
      repo: null,
      desc: {
        en: [
          "I developed an online voting system for the Faculty of Law at Universitas Riau using CodeIgniter. I was responsible for designing the MySQL database, building the authentication system, and developing core features such as token-based voting and real-time result visualization.",
          "One challenge was a disagreement over the authentication method and how results should be displayed. Through open discussion, we agreed to withhold results until voting had closed — protecting the integrity of the process."
        ],
        id: [
          "Saya mengembangkan sistem pemungutan suara online untuk Fakultas Hukum Universitas Riau menggunakan CodeIgniter. Saya bertanggung jawab merancang basis data MySQL, membangun sistem autentikasi, dan mengembangkan fitur inti seperti pemungutan suara berbasis token dan visualisasi hasil secara real-time.",
          "Salah satu tantangan adalah perbedaan pendapat mengenai metode autentikasi dan bagaimana hasil ditampilkan. Melalui diskusi terbuka, kami sepakat menunda penampilan hasil sampai pemungutan suara selesai — menjaga integritas prosesnya."
        ]
      }
    },
    {
      id: "ppdb",
      title: { en: "School Website & Online Admissions", id: "Website Sekolah & PPDB Online" },
      subtitle: "SD 003 YKWI",
      cat: ["web", "community"],
      role: { en: "Full-stack Developer", id: "Pengembang Full-stack" },
      context: { en: "Community service · lecturer-led", id: "Pengabdian masyarakat · dipimpin dosen" },
      year: "2024",
      images: ["Assets/projects/ppdb-1.jpg", "Assets/projects/ppdb-2.jpg"],
      photo: true,
      blurb: {
        en: "Three integrated systems: a school-profile site, online admissions with document verification, and geofenced teacher attendance.",
        id: "Tiga sistem terintegrasi: situs profil sekolah, PPDB online dengan verifikasi dokumen, dan presensi guru berbasis geofencing."
      },
      tech: ["Laravel", "MySQL", "Geofencing", "PPDB"],
      repo: null,
      desc: {
        en: [
          "As a full-stack developer on a lecturer-led community-service project, I built three integrated systems for SD 003 YKWI Pekanbaru. I created a school-profile website featuring vision and mission, an activity gallery, and an announcements blog.",
          "For online student admissions (PPDB), I designed a digital registration form with document verification and an admin dashboard. I also built a location-based teacher attendance system with monthly reports.",
          "When the team's original single-network attendance approach proved impractical, I proposed geofencing as a more flexible alternative — a solution that shaped the final system."
        ],
        id: [
          "Sebagai pengembang full-stack dalam proyek pengabdian masyarakat yang dipimpin dosen, saya mengembangkan tiga sistem terintegrasi untuk SD 003 YKWI Pekanbaru. Saya membangun website profil sekolah yang menampilkan visi dan misi, galeri kegiatan, dan blog pengumuman.",
          "Untuk penerimaan siswa baru online (PPDB), saya merancang formulir pendaftaran digital dengan verifikasi dokumen dan dasbor admin. Saya juga mengembangkan sistem presensi guru berbasis lokasi dengan laporan kehadiran bulanan.",
          "Saat pendekatan awal presensi yang terbatas pada satu jaringan dinilai kurang praktis, saya mengusulkan solusi geofencing sebagai alternatif yang lebih fleksibel — solusi yang membentuk sistem final."
        ]
      }
    }
  ];

  /* ---------- Helpers ---------- */
  const $ = (s, c) => (c || document).querySelector(s);
  const $$ = (s, c) => Array.from((c || document).querySelectorAll(s));

  function coverMarkup(cover) {
    return (
      '<div class="pc-cover">' +
        '<svg class="cov-glyph" viewBox="0 0 24 24" aria-hidden="true">' + GLYPHS[cover.glyph] + '</svg>' +
        '<span class="cov-num">' + cover.num + '</span>' +
      '</div>'
    );
  }

  function cardMarkup(p) {
    const media = p.images
      ? '<div class="pc-media' + (p.photo ? ' photo' : '') + '"><img src="' + p.images[0] + '" alt="' + L(p.title) + ' preview" loading="lazy" /></div>'
      : '<div class="pc-media">' + coverMarkup(p.cover) + '</div>';

    const badge = '<span class="pc-badge">' + catLabel(p.cat[0]) + '</span>';
    const plus = '<span class="pc-plus"><svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg></span>';

    const maxTags = p.feature ? 5 : 3;
    const shown = p.tech.slice(0, maxTags).map(t => '<span class="pc-tag">' + t + '</span>').join("");
    const extra = p.tech.length > maxTags ? '<span class="pc-tag more">+' + (p.tech.length - maxTags) + '</span>' : "";

    const sub = L(p.subtitle);
    const title = L(p.title) + (sub ? ' <span style="color:var(--muted);font-size:.72em">— ' + sub + '</span>' : '');

    return (
      '<article class="project-card' + (p.feature ? ' is-feature' : '') + '" data-cat="' + p.cat.join(" ") + '" data-id="' + p.id + '" tabindex="0" role="button" aria-label="Open ' + L(p.title) + ' details">' +
        '<div class="pc-media-wrap" style="position:relative">' + media + badge + plus + '</div>' +
        '<div class="pc-body">' +
          '<div class="pc-top"><h3 class="pc-title">' + title + '</h3><span class="pc-role">' + L(p.role) + '</span></div>' +
          '<p class="pc-blurb">' + L(p.blurb) + '</p>' +
          '<div class="pc-tags">' + shown + extra + '</div>' +
        '</div>' +
      '</article>'
    );
  }

  const grid = $("#projectsGrid");
  function renderProjects() {
    if (grid) grid.innerHTML = PROJECTS.map(cardMarkup).join("");
  }

  /* ---------- Filters ---------- */
  let currentFilter = "all";
  function applyFilter(f, animate) {
    currentFilter = f;
    $$(".project-card").forEach(card => {
      const match = f === "all" || card.dataset.cat.split(" ").includes(f);
      if (animate) {
        card.classList.add("filtering");
        setTimeout(() => {
          card.classList.toggle("is-hidden", !match);
          requestAnimationFrame(() => card.classList.remove("filtering"));
        }, 180);
      } else {
        card.classList.toggle("is-hidden", !match);
      }
    });
  }
  const filters = $$(".filter");
  filters.forEach(btn => {
    btn.addEventListener("click", () => {
      filters.forEach(b => { b.classList.remove("is-active"); b.setAttribute("aria-selected", "false"); });
      btn.classList.add("is-active");
      btn.setAttribute("aria-selected", "true");
      applyFilter(btn.dataset.filter, true);
    });
  });

  /* ---------- Modal ---------- */
  const modal = $("#projectModal");
  const modalScroll = $("#modalScroll");
  let lastFocus = null;
  let galleryTimer = null;

  function repoMarkup(url) {
    if (!url) return "";
    return '<a href="' + url + '" target="_blank" rel="noopener" class="btn btn-primary m-repo">' +
      '<svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 0 0-3.16 19.49c.5.09.68-.22.68-.48v-1.7c-2.78.6-3.37-1.34-3.37-1.34-.45-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.89 1.53 2.34 1.09 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.27.1-2.65 0 0 .84-.27 2.75 1.02a9.5 9.5 0 0 1 5 0c1.91-1.29 2.75-1.02 2.75-1.02.55 1.38.2 2.4.1 2.65.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.68-4.57 4.93.36.31.68.92.68 1.85v2.74c0 .27.18.58.69.48A10 10 0 0 0 12 2Z" fill="currentColor" stroke="none"/></svg>' +
      tr("repo") + "</a>";
  }

  function galleryMarkup(p) {
    if (!p.images) return '<div class="m-cover">' + coverMarkup(p.cover) + '</div>';
    const imgs = p.images.map((src, i) =>
      '<img src="' + src + '" alt="' + L(p.title) + ' screenshot ' + (i + 1) + '" class="' + (i === 0 ? "is-active" : "") + '" />').join("");
    if (p.images.length === 1) return '<div class="m-gallery">' + imgs + '</div>';
    const dots = p.images.map((_, i) => '<span class="m-dot' + (i === 0 ? " is-active" : "") + '" data-i="' + i + '"></span>').join("");
    return (
      '<div class="m-gallery">' + imgs +
        '<div class="m-dots">' + dots + '</div>' +
        '<div class="m-nav">' +
          '<button data-dir="-1" aria-label="Previous image"><svg viewBox="0 0 24 24"><path d="M15 6l-6 6 6 6"/></svg></button>' +
          '<button data-dir="1" aria-label="Next image"><svg viewBox="0 0 24 24"><path d="M9 6l6 6-6 6"/></svg></button>' +
        '</div>' +
      '</div>'
    );
  }

  function noteMarkup(note) {
    if (!note) return "";
    return '<div class="m-note"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v4h1"/></svg><span>' + L(note) + '</span></div>';
  }

  function openModal(id) {
    const p = PROJECTS.find(x => x.id === id);
    if (!p) return;
    lastFocus = document.activeElement;

    const cats = p.cat.map(c => catLabel(c)).join(" · ");
    const meta =
      '<span><b>' + L(p.role) + '</b></span>' +
      (p.context ? '<span>' + L(p.context) + '</span>' : '') +
      '<span>' + p.year + '</span>';
    const desc = L(p.desc).map(d => '<p>' + d + '</p>').join("");
    const tech = p.tech.map(t => '<span>' + t + '</span>').join("");
    const sub = L(p.subtitle);
    const title = L(p.title) + (sub ? ' <span style="color:var(--muted);font-weight:300">— ' + sub + '</span>' : '');

    modalScroll.innerHTML =
      '<div class="m-hero">' + galleryMarkup(p) + '</div>' +
      '<div class="m-body">' +
        '<span class="m-cat">' + cats + '</span>' +
        '<h2 class="m-title" id="modalTitle">' + title + '</h2>' +
        '<div class="m-meta">' + meta + '</div>' +
        '<div class="m-desc">' + desc + '</div>' +
        '<p class="m-section-label">' + tr("tech") + '</p>' +
        '<div class="m-tech">' + tech + '</div>' +
        (p.repo ? '<div class="m-actions">' + repoMarkup(p.repo) + '</div>' : '') +
        noteMarkup(p.note) +
      '</div>';

    modal.classList.add("is-open");
    modal.setAttribute("aria-hidden", "false");
    document.documentElement.style.overflow = "hidden";
    modalScroll.scrollTop = 0;
    setupGallery(p);
    const closeBtn = $(".modal-close", modal);
    if (closeBtn) closeBtn.focus();
  }

  function setupGallery(p) {
    if (galleryTimer) { clearInterval(galleryTimer); galleryTimer = null; }
    if (!p.images || p.images.length < 2) return;
    const imgs = $$(".m-gallery img", modalScroll);
    const dots = $$(".m-dot", modalScroll);
    let idx = 0;
    const show = (n) => {
      idx = (n + imgs.length) % imgs.length;
      imgs.forEach((im, i) => im.classList.toggle("is-active", i === idx));
      dots.forEach((d, i) => d.classList.toggle("is-active", i === idx));
    };
    $$(".m-nav button", modalScroll).forEach(b =>
      b.addEventListener("click", () => { show(idx + parseInt(b.dataset.dir, 10)); restart(); }));
    dots.forEach(d => d.addEventListener("click", () => { show(parseInt(d.dataset.i, 10)); restart(); }));
    const restart = () => { if (galleryTimer) clearInterval(galleryTimer); galleryTimer = setInterval(() => show(idx + 1), 5000); };
    modal._galleryShow = show;
    modal._galleryIdx = () => idx;
    restart();
  }

  function closeModal() {
    modal.classList.remove("is-open");
    modal.setAttribute("aria-hidden", "true");
    document.documentElement.style.overflow = "";
    if (galleryTimer) { clearInterval(galleryTimer); galleryTimer = null; }
    if (lastFocus) lastFocus.focus();
  }

  if (grid) {
    grid.addEventListener("click", (e) => {
      const card = e.target.closest(".project-card");
      if (card) openModal(card.dataset.id);
    });
    grid.addEventListener("keydown", (e) => {
      if ((e.key === "Enter" || e.key === " ") && e.target.classList.contains("project-card")) {
        e.preventDefault();
        openModal(e.target.dataset.id);
      }
    });
  }

  modal.addEventListener("click", (e) => { if (e.target.hasAttribute("data-close")) closeModal(); });
  document.addEventListener("keydown", (e) => {
    if (!modal.classList.contains("is-open")) return;
    if (e.key === "Escape") closeModal();
    if (modal._galleryShow) {
      if (e.key === "ArrowRight") modal._galleryShow(modal._galleryIdx() + 1);
      if (e.key === "ArrowLeft") modal._galleryShow(modal._galleryIdx() - 1);
    }
  });

  /* ---------- Language toggle ---------- */
  const root = document.documentElement;
  const i18nEls = $$("[data-i18n]");
  const EN_CACHE = new Map();
  i18nEls.forEach(el => EN_CACHE.set(el, el.innerHTML));
  const langBtn = $("#langToggle");

  function applyLang(lang) {
    LANG = (lang === "id") ? "id" : "en";
    root.setAttribute("lang", LANG);
    i18nEls.forEach(el => {
      const k = el.getAttribute("data-i18n");
      if (LANG === "id") { if (ID[k] != null) el.innerHTML = ID[k]; }
      else { el.innerHTML = EN_CACHE.get(el); }
    });
    if (langBtn) $$("span[data-lang]", langBtn).forEach(s => s.classList.toggle("is-active", s.dataset.lang === LANG));
    renderProjects();
    applyFilter(currentFilter, false);
  }

  function setLang(lang) {
    if (modal.classList.contains("is-open")) closeModal();
    applyLang(lang);
    try { localStorage.setItem("nh-lang", LANG); } catch (e) {}
  }

  const savedLang = (() => { try { return localStorage.getItem("nh-lang"); } catch (e) { return null; } })();
  applyLang(savedLang === "id" ? "id" : "en");   /* also does the initial project render */
  if (langBtn) langBtn.addEventListener("click", () => setLang(LANG === "id" ? "en" : "id"));

  /* ---------- Theme toggle ---------- */
  const themeBtn = $("#themeToggle");
  const stored = (() => { try { return localStorage.getItem("nh-theme"); } catch (e) { return null; } })();
  const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
  const setTheme = (t) => {
    root.setAttribute("data-theme", t);
    if (themeBtn) themeBtn.setAttribute("aria-pressed", String(t === "dark"));
    const meta = $('meta[name="theme-color"]');
    if (meta) meta.setAttribute("content", t === "dark" ? "#15130F" : "#F5F1E9");
  };
  setTheme(stored || (prefersDark ? "dark" : "light"));
  if (themeBtn) themeBtn.addEventListener("click", () => {
    const next = root.getAttribute("data-theme") === "dark" ? "light" : "dark";
    setTheme(next);
    try { localStorage.setItem("nh-theme", next); } catch (e) {}
  });

  /* ---------- Header + scroll progress ---------- */
  const header = $(".site-header");
  const progress = $(".scroll-progress span");
  const onScroll = () => {
    const y = window.scrollY || window.pageYOffset;
    if (header) header.classList.toggle("is-stuck", y > 12);
    if (progress) {
      const h = document.documentElement.scrollHeight - window.innerHeight;
      progress.style.width = (h > 0 ? (y / h) * 100 : 0) + "%";
    }
  };
  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  /* ---------- Mobile menu ---------- */
  const menuToggle = $("#menuToggle");
  const mobileMenu = $("#mobileMenu");
  const toggleMenu = (open) => {
    const isOpen = open !== undefined ? open : !menuToggle.classList.contains("is-open");
    menuToggle.classList.toggle("is-open", isOpen);
    mobileMenu.classList.toggle("is-open", isOpen);
    menuToggle.setAttribute("aria-expanded", String(isOpen));
    mobileMenu.setAttribute("aria-hidden", String(!isOpen));
  };
  if (menuToggle) menuToggle.addEventListener("click", () => toggleMenu());
  if (mobileMenu) $$("a", mobileMenu).forEach(a => a.addEventListener("click", () => toggleMenu(false)));

  /* ---------- Reveal on scroll ---------- */
  const revealEls = $$(".reveal");
  if ("IntersectionObserver" in window) {
    const ro = new IntersectionObserver((entries) => {
      entries.forEach((en) => {
        if (en.isIntersecting) {
          const el = en.target;
          const sib = Array.from(el.parentElement.children).filter(c => c.classList.contains("reveal"));
          const delay = Math.min(sib.indexOf(el), 5) * 80;
          setTimeout(() => el.classList.add("is-visible"), delay);
          ro.unobserve(el);
        }
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -8% 0px" });
    revealEls.forEach(el => ro.observe(el));
  } else {
    revealEls.forEach(el => el.classList.add("is-visible"));
  }

  /* ---------- Active nav (scrollspy) ---------- */
  const navLinks = $$(".nav a");
  const sections = navLinks.map(a => document.querySelector(a.getAttribute("href"))).filter(Boolean);
  if ("IntersectionObserver" in window && sections.length) {
    const so = new IntersectionObserver((entries) => {
      entries.forEach(en => {
        if (en.isIntersecting) {
          const id = en.target.id;
          navLinks.forEach(a => a.classList.toggle("is-active", a.getAttribute("href") === "#" + id));
        }
      });
    }, { threshold: 0.25, rootMargin: "-40% 0px -50% 0px" });
    sections.forEach(s => so.observe(s));
  }

  /* ---------- Year ---------- */
  const yearEl = $("#year");
  if (yearEl) yearEl.textContent = new Date().getFullYear();
})();
