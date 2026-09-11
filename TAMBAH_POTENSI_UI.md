@workspace Act as an Expert Frontend Developer and UI/UX Designer. I want to completely redesign my "Tambah Potensi" (Add Potential) form. 

Currently, the form is a long, overwhelming single column of inputs. I want to organize it into logical sections, apply the "Glassmorphism" design system we established, and add highly interactive, human-centric micro-interactions.

**1. Form Restructuring & Grouping (Grid Layout):**
Instead of a single long list, organize the form fields into 3 elegant Glassmorphism cards (panels) displayed in a CSS Grid/Flexbox layout:

* **Card 1: Informasi Perusahaan (Company Info)**
    * Nama Usaha / Perusahaan (Required)
    * NPWP
    * Segmen (Dropdown)
    * Uraian / Bidang Usaha
    * Tanggal Input (Auto-filled to today, but editable)
* **Card 2: Estimasi & Program (Estimations)**
    * Estimasi Tenaga Kerja (Number input)
    * Estimasi Upah (Rupiah formatted input)
    * Estimasi Iuran (Rupiah formatted input)
    * Program (Checkbox group: JKK, JKM, JHT, JP, JKP)
    * Status Tindak Lanjut (Dropdown)
* **Card 3: Lokasi & Catatan (Location & Notes)**
    * Alamat Lengkap (Textarea)
    * Latitude & Longitude (Read-only or grouped side-by-side)
    * Button: "Pilih Lokasi di Peta" (Make this button prominent with an icon)
    * Catatan (Textarea)

**2. Glassmorphism & BPJS Styling:**
* **Backgrounds:** Use frosted glass panels (`bg-white/10 backdrop-blur-md border border-white/20`) for the cards, casting a soft shadow.
* **Inputs:** Clean inputs with subtle borders. Upon focus, the input should have a smooth, glowing cyan ring (`#05ADDC`) and a slight scale-up effect (`scale: 1.01`).
* **Labels:** Use floating labels or very clean, slightly muted text for labels so they don't distract from the input values.
* **Primary Button:** The "Simpan" (Save) button should use the BPJS Green (`#3AB44A`), be fully rounded, and have a magnetic hover effect.

**3. Interactive Map Modal:**
* When the user clicks "Pilih Lokasi di Peta", instead of navigating away, open a sleek Glassmorphism Modal containing the Map. 
* Once a point is picked, gracefully close the modal and auto-fill the Latitude and Longitude fields with a small green checkmark animation indicating success.

**4. Real-time Validation Feedback:**
* Provide real-time inline validation. If a required field is empty when they leave it (blur), show a subtle red glow. If valid, show a very subtle green border.

**Execution Plan:**
1. I have tagged my `TambahPotensi` component file.
2. First, restructure the JSX/HTML layout to group the inputs into the 3 cards mentioned above using Tailwind CSS Grid/Flexbox.
3. Second, apply the Glassmorphism styles and focus states to the inputs.
4. Third, implement the Modal logic for the "Pilih Lokasi di Peta" button.