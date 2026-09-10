@workspace Act as a Senior Full-Stack Engineer. I am building an Excel Import feature for "Pencatatan Potensi Jamsostek Bidang KSI". To make it easy for users, I need a feature to "Download Excel Template".

Please help me create the backend endpoint and the frontend function/button to generate and download a highly formatted `.xlsx` template.

**1. Template Structure & Columns:**
The Excel file must have the following columns in the exact order, matching my form schema:
1. Tanggal Input (Format: YYYY-MM-DD)
2. Nama Usaha / Perusahaan (Wajib)
3. NPWP (Opsional)
4. Segmen (Opsional)
5. Uraian / Bidang Usaha
6. Alamat Lengkap (Wajib)
7. Latitude (Opsional - biarkan kosong jika tidak tahu)
8. Longitude (Opsional - biarkan kosong jika tidak tahu)
9. Estimasi Tenaga Kerja (Angka)
10. Estimasi Upah (Angka)
11. Estimasi Iuran (Angka)
12. Program JKK, JKM, JHT, JP, JKP (Opsional - pisahkan dengan koma)
13. Status Tindak Lanjut
14. Catatan

**2. Formatting & Styling Requirements (Crucial):**
I want this template to look professional, not just a plain CSV. Please use a library like `exceljs` (or similar depending on my backend framework) to apply these styles:
*   **Header Row:** Freeze the top row. Give it a solid background color (e.g., BPJS Green or dark teal) with bold white text. Center the text alignment.
*   **Column Widths:** Auto-adjust or set sensible widths for each column (e.g., "Nama Usaha" and "Alamat" should be wide, "Latitude" can be medium).
*   **Helper Row / Notes:** Add a second row right below the header (or use Excel cell comments/notes) that provides brief instructions. For example, under Latitude/Longitude write: "(Opsional) Kosongkan jika belum survei lokasi", and under Programs write: "(Opsional) Isi dengan JKK, JKM, JHT".

**3. Frontend Implementation:**
*   Create a reusable button component `DownloadTemplateButton`.
*   When clicked, it should call the backend endpoint, handle the blob/file download gracefully, and trigger the file save in the user's browser as `Template_Import_Potensi_KSI.xlsx`.

**Execution Plan for Copilot:**
1. I have attached my backend routing file and the frontend view where the button will be placed.
2. Please write the Backend Controller code to generate this styled `.xlsx` file.
3. Then, write the Frontend API call and Button component.