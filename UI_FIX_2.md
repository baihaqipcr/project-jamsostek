@workspace Act as a Principal UI/UX Designer and Frontend Specialist. The current Dashboard UI for "Pencatatan Potensi KSI" looks flat, muted, and dull (especially when there is no data). I want to transform it to be significantly brighter, more vibrant, and visually alive while adhering to BPJS branding.

Please refactor the layout, CSS/Tailwind styles, and empty state components with these enhancements:

**1. Ambient Background & High-Light Glassmorphism:**
* Replace the flat background with an active mesh gradient: soft light-gray base (`#F8FAFC`) layered with subtle, blurred ambient glow blobs in Cyan (`#05ADDC` at 15% opacity) and Lime (`#D6E65D` at 20% opacity).
* Update all cards (Potensi aktif, Estimasi iuran, Status pantauan) to crisp Glassmorphism panels: `bg-white/80 backdrop-blur-md border border-white/90 shadow-xl shadow-slate-200/50 rounded-2xl transition-all hover:shadow-2xl hover:-translate-y-0.5`.

**2. Vibrant Header Banner:**
* Update the hero welcome banner background to a rich gradient transitioning from BPJS Green (`#3AB44A`) to Vibrant Cyan (`#05ADDC`).
* Add a subtle abstract wave pattern overlay or glassmorphic stat chips (`bg-white/15 backdrop-blur-lg border border-white/30 text-white`) for "Total Potensi" and "Tenaga Kerja".

**3. Bright Summary Cards & Typography:**
* Include vibrant accent icons (e.g., Lucide `TrendingUp`, `Wallet`, `Filter`) housed inside soft glowing circle badges (`bg-[#05ADDC]/10 text-[#05ADDC]`) at the top-right of each card.
* Elevate numeric contrast: use bold typography (`text-3xl font-extrabold text-slate-900`).

**4. "Alive" Empty State Experience:**
* Replace the plain "Belum ada data potensi" text in the table with a dedicated, modern Empty State component.
* Include a soft glowing icon graphic (e.g., Folder/Database illustration), a clear title ("Belum Ada Data Potensi"), a friendly helper text, and two quick-action buttons: `[+ Tambah Potensi]` and `[📥 Impor dari Excel]`.

**5. Sidebar Accent:**
* Upgrade the active menu item ("Daftar Potensi") to stand out with a bright Cyan accent or Lime glowing border (`border-l-4 border-[#D6E65D] bg-emerald-800/80 shadow-md`).

Please review my dashboard component and provide the updated code.